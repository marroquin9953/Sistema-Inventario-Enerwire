<?php
/*
* ProductEngine.php - Main component file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product;

use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\Barcodes\Repositories\BarcodesRepository;
use App\Yantrana\Components\Category\Repositories\CategoryRepository;
use App\Yantrana\Components\Product\Interfaces\ProductEngineInterface;
use App\Yantrana\Components\Product\Repositories\ProductRepository;
use App\Yantrana\Components\Suppliers\Repositories\SuppliersRepository;
use App\Yantrana\Components\TaxPreset\Repositories\TaxPresetRepository;

class ProductEngine extends BaseEngine implements ProductEngineInterface
{
    /**
     * @var  ProductRepository - Product Repository
     */
    protected $productRepository;

    /**
     * @var  CategoryRepository - Category Repository
     */
    protected $categoryRepository;

    /**
     * @var  SuppliersRepository - Suppliers Repository
     */
    protected $suppliersRepository;

    /**
     * @var  BarcodesRepository - Barcodes Repository
     */
    protected $barcodesRepository;

    /**
     * @var  TaxPresetRepository - TaxPreset Repository
     */
    protected $taxPresetRepository;

    /**
     * Constructor
     *
     * @param  ProductRepository  $productRepository - Product Repository
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        SuppliersRepository $suppliersRepository,
        BarcodesRepository $barcodesRepository,
        TaxPresetRepository $taxPresetRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->suppliersRepository = $suppliersRepository;
        $this->barcodesRepository = $barcodesRepository;
        $this->taxPresetRepository = $taxPresetRepository;
    }

    /**
     * Create a combo key
     *
     * @param  array  $data
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    protected function createComboKey($data = [])
    {
        if (! __isEmpty($data)) {
            arsort($data);

            return sha1(serialize($data));
        }

        return false;
    }

    /**
     * Check if array contains duplicates
     *
     * @param  array  $array
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function arrayContainsDuplicate($array)
    {
        return count($array) != count(array_unique($array));
    }

    /**
     * Product datatable source
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareProductDataTableSource()
    {
        $productCollection = $this->productRepository->fetchProductDataTableSource();
        $requireColumns = [
            '_id',
            '_uid',
            'name',
            'category' => function ($key) {
                return $key['category_name'];
            },
            'status' => function ($key) {
                $categoryStatus = $key['category_status'];
                if ($key['category_status'] == 2) {
                    return configItem('products.status', $key['category_status']).' (From Category)';
                } else {
                    return configItem('products.status', $key['status']);
                }
            //return configItem('products.status', $status);
            },
            'short_description' => function ($key) {
                return str_limit($key['short_description'], configItem('string_limit'));
            },
            'can_edit' => function () {
                return canAccess('manage.product.write.update');
            },
            'can_delete' => function () {
                return canAccess('manage.product.write.delete');
            },
        ];

        return $this->dataTableResponse($productCollection, $requireColumns);
    }

    /**
     * Product delete process
     *
     * @param  mix  $productIdOrUid
     * @return  array
     *---------------------------------------------------------------- */
    public function processProductDelete($productIdOrUid)
    {
        $product = $this->productRepository
            ->fetch($productIdOrUid);

        if (__isEmpty($product)) {
            return $this->engineReaction(18, null, __tr('Product not found.'));
        }

        $updateData = [
            'status' => 3, // Deleted
        ];

        if ($this->productRepository->deleteProduct($product, $updateData)) {
            return $this->engineReaction(1, null, __tr('Product deleted successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Product not deleted.'));
    }

    protected function getCategoryAndSuppliers()
    {
        $categoryCollection = $this->categoryRepository->fetchAllCategory();
        $categories = [];

        // Check if category exist
        if (! __isEmpty($categoryCollection)) {
            foreach ($categoryCollection as $key => $category) {
                $categories[] = [
                    'id' => $category->_id,
                    'name' => $category->name,
                ];
            }
        }

        $supplierCollection = $this->suppliersRepository->fetchAllSuppliers();
        $suppliers = [];

        // Check if supplier exist
        if (! __isEmpty($supplierCollection)) {
            foreach ($supplierCollection as $key => $supplier) {
                $suppliers[] = [
                    'id' => $supplier->_id,
                    'name' => $supplier->name,
                ];
            }
        }

        return [
            'categories' => $categories,
            'suppliers' => $suppliers,
        ];
    }

    /**
     * Product Add Support Data
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareProductSupportData()
    {
        $labelData = [];

        $optionLabels = $this->productRepository->fetchLabels();
        if (! __isEmpty($optionLabels)) {
            foreach ($optionLabels as $key => $label) {
                $labelData[] = [
                    'id' => $label->_id,
                    'name' => $label->name,
                ];
            }
        }

        $presets = $this->taxPresetRepository->fetchAll();
        $taxPresets = [];
        if (! __isEmpty($presets)) {
            foreach ($presets as $key => $preset) {
                $taxPresets[] = [
                    'id' => $preset->_id,
                    'name' => $preset->title,
                ];
            }
        }

        $categoryCollection = $this->categoryRepository->fetchAllCategory();
        $categories = [];

        // Check if category exist
        if (! __isEmpty($categoryCollection)) {
            foreach ($categoryCollection as $key => $category) {
                if ($category->status == 1) {
                    $categories[] = [
                        'id' => $category->_id,
                        'name' => $category->name,
                    ];
                }
            }
        }

        return $this->engineReaction(1, [
            'currency' => getCurrency(),
            'currency_symbol' => getCurrencySymbol(),
            'labelData' => $labelData,
            'categories' => $categories,
            'taxPresets' => $taxPresets,
        ]);
    }

    /**
     * Product create
     *
     * @param  array  $inputData
     * @return  array
     *---------------------------------------------------------------- */
    public function processProductCreate($inputData)
    {
        $reactionCode = $this->productRepository
            ->processTransaction(function () use ($inputData) {
                if ($product = $this->productRepository->storeProduct($inputData)) {
                    $optionLabelIds = $this->productRepository->fetchLabels()->pluck('_id')->toArray();
                    $productId = $product->_id;
                    $optionLabels = [];
                    foreach ($inputData['optionLabels'] as $key => $input) {
                        foreach ($input['values'] as $valueKey => $value) {
                            if (
                                is_numeric($value['label_name'])
                                and ! in_array($value['label_name'], $optionLabelIds)
                            ) {
                                $optionLabels[] = $value['label_name'];
                            } elseif (
                                is_string($value['label_name'])
                                and ! in_array($value['label_name'], $optionLabelIds)
                            ) {
                                $optionLabels[] = $value['label_name'];
                            }
                        }
                    }

                    $uniqueOptionLabels = array_unique($optionLabels);

                    $storedLabels = $this->productRepository->storeOptionLabels($uniqueOptionLabels, $productId);

                    foreach ($inputData['optionLabels'] as $labelKey => $input) {
                        foreach ($input['values'] as $valueKey => $value) {
                            if (array_key_exists($value['label_name'], $storedLabels)) {
                                $inputData['optionLabels'][$labelKey]['values'][$valueKey]['label_id'] = $storedLabels[$value['label_name']];
                            } elseif (
                                is_numeric($value['label_name'])
                                and in_array($value['label_name'], $optionLabelIds)
                            ) {
                                $inputData['optionLabels'][$labelKey]['values'][$valueKey]['label_id'] = $value['label_name'];
                            }
                        }
                    }

                    $comboKeys = [];
                    if (! __isEmpty($inputData['optionLabels'])) {
                        foreach ($inputData['optionLabels'] as $labelKey => $optLabelValue) {
                            $comboKeyContainer = [];
                            foreach ($optLabelValue['values'] as $key => $value) {
                                if (isset($value['label_id'])) {
                                    $comboKeyContainer[] = [$value['label_id']];
                                }
                                if (isset($value['value_name'])) {
                                    $comboKeyContainer[] = [$value['value_name']];
                                }
                            }
                            $comboKeyContainer[] = [$productId];
                            $comboKeys[] = $this->createComboKey($comboKeyContainer);
                        }
                    }

                    if (! __isEmpty($comboKeys)) {
                        if ($this->arrayContainsDuplicate($comboKeys) == true) {
                            return $this->productRepository->transactionResponse(2, null, __tr('Product Combination already exist Or Combinations might be left blank. So please check the combinations values.'));
                        }
                    }

                    $newOptionLabels = $this->productRepository->storeOptionLabelsAndValues($inputData['optionLabels'], $productId, $product->name);

                    if (
                        is_array($newOptionLabels)
                        and array_key_exists('error', $newOptionLabels)
                    ) {
                        return $this->productRepository->transactionResponse(2, null, $newOptionLabels['msssage']);
                    }

                    return $this->productRepository->transactionResponse(1, null, __tr('Product added successfully.'));
                }

                return $this->productRepository->transactionResponse(2, null, __tr('Product not added.'));
            });

        return $this->engineReaction($reactionCode);
    }

    private function getKeyValues($array)
    {
        if (empty($array)) {
            return [];
        }

        $data = [];

        foreach ($array as $key => $a) {
            $data[$key] = [
                'key' => $a,
                'value' => $a,
            ];
        }

        return $data;
    }

    /**
     * Product prepare update data
     *
     * @param  mix  $productIdOrUid
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareProductUpdateData($productIdOrUid)
    {
        $product = $this->productRepository->fetchWithLabelsAndValue($productIdOrUid);

        // Check if product exist
        if (__isEmpty($product)) {
            return $this->engineReaction(18, null, __tr('Product not found.'));
        }

        $productData = [
            '_id' => $product->_id,
            'name' => $product->name,
            'product_id' => $product->product_id,
            'short_description' => $product->short_description,
            'price' => $product->price,
            'category_id' => $product->categories__id,
            'status' => ($product->status == 1) ? true : false,
            'tax_preset' => isset($product->tax_presets__id)
                ? $product->tax_presets__id : null,
        ];

        $barcodeOps = $barcodesArray = [];

        $c = 0;

        // Check if product options are availabe
        if (! __isEmpty($product->combinations)) {
            foreach ($product->combinations as $combiKey => $combination) {
                $barcodes = array_pluck($combination->combinationBarcodes, 'barcode');

                $productData['optionLabels'][] = [
                    '_id' => $combination->_id,
                    'title' => $combination->title,
                    'product_id' => $combination->product_id,
                    'barcodes' => $barcodes,
                    'price' => (float) $combination->price,
                    'selling_price' => (float) $combination->sale_price,
                ];

                $barcodesArray[] = $barcodes;
                $barcodeOps[$combiKey] = $this->getKeyValues(array_unique($barcodes));

                // Check if product options value exist
                if (! __isEmpty($combination->combinationOptions)) {
                    foreach ($combination->combinationOptions as $combiOpt => $combiOption) {
                        $productData['optionLabels'][$combiKey]['values'][] = [
                            'value_name' => array_get($combiOption, 'optionValue.name'),
                            'value_id' => array_get($combiOption, 'optionValue._id'),
                            'label_name' => array_get($combiOption, 'optionValue.productOptionLabel._id'),
                        ];
                    }
                } elseif (__isEmpty($combination->combinationOptions)) {
                    $productData['optionLabels'][$combiKey]['values'][] = [
                        'value_name' => '',
                        'value_id' => '',
                        'label_name' => '',
                    ];
                }
            }
        }

        $labelData = [];
        $optionLabels = $this->productRepository->fetchLabels();
        if (! __isEmpty($optionLabels)) {
            foreach ($optionLabels as $key => $label) {
                $labelData[] = [
                    'id' => $label->_id,
                    'name' => $label->name,
                ];
            }
        }

        $categoryCollection = $this->categoryRepository->fetchAllCategory();
        $categories = [];

        // Check if category exist
        if (! __isEmpty($categoryCollection)) {
            foreach ($categoryCollection as $key => $category) {
                $categories[] = [
                    'id' => $category->_id,
                    'name' => ($category->status == 1) ? $category->name : $category->name.' (inactive)',
                ];
            }
        }

        //tax presets
        $presets = $this->taxPresetRepository->fetchAll();

        $taxPresets = [];
        if (! __isEmpty($presets)) {
            foreach ($presets as $key => $preset) {
                $taxPresets[] = [
                    'id' => $preset->_id,
                    'name' => $preset->title,
                ];
            }
        }

        return $this->engineReaction(1, [
            'updateData' => $productData,
            'labelData' => $labelData,
            'barcodesOp' => $barcodeOps,
            'barcodesArray' => array_flatten($barcodesArray),
            'currency' => getCurrency(),
            'currency_symbol' => getCurrencySymbol(),
            'categories' => $categories,
            'taxPresets' => $taxPresets,
        ]);
    }

    /**
     * Product prepare update data
     *
     * @param  mix  $productIdOrUid
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareDetails($productIdOrUid)
    {
        $product = $this->productRepository->fetchDetails($productIdOrUid);

        // Check if product exist
        if (__isEmpty($product)) {
            return $this->engineReaction(18, null, __tr('Product not found.'));
        }

        $productData = [
            'name' => $product->name,
            'product_id' => $product->product_id,
            'short_description' => $product->short_description,
            'price' => moneyFormat($product->price, true),
            'category_name' => $product->category_name,
            'status' => techItemString($product->status),
            'created_at' => formatDateTime($product->created_at),
            'updated_at' => formatDateTime($product->updated_at),
        ];

        // Check if product options are available
        if (! __isEmpty($product->combinations)) {
            foreach ($product->combinations as $combiKey => $combination) {
                $productData['optionLabels'][] = [
                    '_id' => $combination->_id,
                    'title' => $combination->title,
                    'product_id' => $combination->product_id,
                    'barcode' => $combination->barcode,
                    'price' => moneyFormat($combination->price, true),
                    'sale_price' => moneyFormat($combination->sale_price, true),
                ];

                // Check if product options value exist
                if (! __isEmpty($combination->combinationOptions)) {
                    foreach ($combination->combinationOptions as $combiOpt => $combiOption) {
                        $productData['optionLabels'][$combiKey]['values'][] = [
                            'value_name' => array_get($combiOption, 'optionValue.name'),
                            'value_id' => array_get($combiOption, 'optionValue._id'),
                            'label_name' => array_get($combiOption, 'optionValue.productOptionLabel.name'),
                        ];
                    }
                } elseif (__isEmpty($combination->combinationOptions)) {
                    $productData['optionLabels'][$combiKey]['values'][] = [
                        'value_name' => '',
                        'value_id' => '',
                        'label_name' => '',
                    ];
                }
            }
        }

        $labelData = [];
        $optionLabels = $this->productRepository->fetchLabels();
        if (! __isEmpty($optionLabels)) {
            foreach ($optionLabels as $key => $label) {
                $labelData[] = [
                    'id' => $label->_id,
                    'name' => $label->name,
                ];
            }
        }

        return $this->engineReaction(1, [
            'details' => $productData,
        ]);
    }

    /**
     * Product process update
     *
     * @param  mix  $productIdOrUid
     * @param  array  $inputData
     * @return  array
     *---------------------------------------------------------------- */
    public function processProductUpdate($productIdOrUid, $inputData)
    {
        $product = $this->productRepository->fetch($productIdOrUid);

        if (__isEmpty($product)) {
            return $this->engineReaction(18, null, __tr('Product not found.'));
        }

        $isProductUpdated = false;

        $updateData = [
            'name' => $inputData['name'],
            'product_id' => $inputData['product_id'],
            'short_description' => $inputData['short_description'],
            'categories__id' => $inputData['category_id'],
            'status' => ($inputData['status'] == 1) ? 1 : 2,
            'tax_presets__id' => isset($inputData['tax_preset'])
                ? $inputData['tax_preset'] : null,
        ];

        if ($this->productRepository->updateProduct($product, $updateData)) {
            $isProductUpdated = true;
        }

        $optionLabelIds = $this->productRepository->fetchLabels()->pluck('_id')->toArray();

        $newValueData = $combinationData = [];

        $productCombinationIds = array_flatten(array_pluck($inputData['optionLabels'], '_id'));
        $barcodes = array_flatten(array_pluck($inputData['optionLabels'], 'barcodes'));

        $combinationsBarcodes = $this->productRepository
            ->fetchCombinationBarcodes(
                $productCombinationIds,
                $barcodes
            )->toArray();

        $existingCombinationBarcodes = [];

        if (! __isEmpty($combinationsBarcodes)) {
            foreach ($combinationsBarcodes as $combinationsBarcode) {
                $existingCombinationBarcodes[$combinationsBarcode['barcode']] = $combinationsBarcode;
            }
        }

        $existingBarcdes = $newBarcodesData = $inputedBarcodes = [];

        foreach ($inputData['optionLabels'] as $key => $input) {
            if (array_key_exists('_id', $input)) {
                $combinationData[] = [
                    '_id' => $input['_id'],
                    'product_id' => $input['product_id'],
                    'title' => $input['title'],
                    'price' => $input['price'],
                    'sale_price' => $input['selling_price'],
                ];
            }

            foreach ($input['barcodes'] as $code) {
                if (! array_key_exists($code, $existingCombinationBarcodes)) {
                    if (array_key_exists('_id', $input)) {
                        $newBarcodesData[] = [
                            'product_combinations__id' => $input['_id'],
                            'barcode' => $code,
                        ];
                    }
                }
            }

            if (isset($input['values']) and ! __isEmpty($input['values'])) {
                foreach ($input['values'] as $valueKey => $value) {
                    if (isset($value['label_name'])) {
                        if (
                            is_numeric($value['label_name'])
                            and ! in_array($value['label_name'], $optionLabelIds)
                        ) {
                            $newValueData[] = $value['label_name'];
                        } elseif (
                            is_string($value['label_name'])
                            and ! in_array($value['label_name'], $optionLabelIds)
                        ) {
                            $newValueData[] = $value['label_name'];
                        }
                    }
                }
            }
        }

        if (! __isEmpty($newBarcodesData)) {
            if ($this->barcodesRepository->insert($newBarcodesData)) {
                activityLog(16, $product->_id, 2, $product->name);
                $isProductUpdated = true;
            }
        }

        if (! __isEmpty($combinationData)) {
            if ($this->productRepository->updateCombinations($combinationData)) {
                activityLog(22, $product->_id, 2, $product->name);
                $isProductUpdated = true;
            }
        }

        $productId = $product->_id;
        $storedLabels = [];
        if (! __isEmpty($newValueData)) {
            $uniqueOptionLabels = array_unique($newValueData);
            $storedLabels = $this->productRepository->storeOptionLabels($uniqueOptionLabels, $productId);
            activityLog(10, $product->_id, 2, $product->name);
            $isProductUpdated = true;
        }

        $combinationStoreData = $combinationUpdateData = [];
        foreach ($inputData['optionLabels'] as $labelKey => $input) {
            if (isset($input['values'])) {
                foreach ($input['values'] as $valueKey => $value) {
                    if (isset($value['label_name'])) {
                        if (array_key_exists($value['label_name'], $storedLabels)) {
                            $inputData['optionLabels'][$labelKey]['values'][$valueKey]['label_id'] = $storedLabels[$value['label_name']];
                        } elseif (
                            is_numeric($value['label_name'])
                            and in_array($value['label_name'], $optionLabelIds)
                        ) {
                            $inputData['optionLabels'][$labelKey]['values'][$valueKey]['label_id'] = $value['label_name'];
                        }
                    }
                }
            }

            if (array_key_exists('_id', $input)) {
                $combinationUpdateData[] = array_pull($inputData['optionLabels'], $labelKey);
            } elseif (! array_key_exists('_id', $input)) {
                $combinationStoreData[] = array_pull($inputData['optionLabels'], $labelKey);
            }
        }

        $mergeCombinationData = array_merge($combinationUpdateData, $combinationStoreData);

        $comboKeys = [];
        if (! __isEmpty($mergeCombinationData)) {
            foreach ($mergeCombinationData as $comboKey => $comboData) {
                $comboKeyContainer = [];
                foreach ($comboData['values'] as $key => $value) {
                    if (isset($value['label_id'])) {
                        $comboKeyContainer[] = [$value['label_id']];
                    }
                    if (isset($value['value_name'])) {
                        $comboKeyContainer[] = [$value['value_name']];
                    }
                }
                $comboKeyContainer[] = [$productId];

                $comboKeys[] = $this->createComboKey($comboKeyContainer);
            }
        }

        if (! __isEmpty($comboKeys)) {
            if ($this->arrayContainsDuplicate($comboKeys) == true) {
                return $this->engineReaction(2, null, __tr('Product Combination already exist Or Combinations might be left blank. So please check the combinations values.'));
            }
        }

        $productId = $product->_id;
        if (! __isEmpty($combinationStoreData)) {
            $this->productRepository->storeOptionLabelsAndValues($combinationStoreData, $productId, $product->name);
            $isProductUpdated = true;
        }

        // Check if product options are available
        if (! __isEmpty($combinationUpdateData)) {
            if ($this->productRepository
                ->storeOrUpdateProductOptions($combinationUpdateData, $product->_id)
            ) {
                $isProductUpdated = true;
            }
        }

        if ($isProductUpdated) {
            return $this->engineReaction(1, null, __tr('Product updated successfully.'));
        }

        return $this->engineReaction(14, null, __tr('Product not updated.'));
    }

    /**
     * Process Product Option Combination Delete
     *
     * @param  mix  $productId
     * @param  mix  $combinationId
     * @return  array
     *---------------------------------------------------------------- */
    public function processProductCombinationDelete($productId, $combinationId)
    {
        $product = $this->productRepository->fetch($productId);

        // Check if product exist
        if (__isEmpty($product)) {
            return $this->engineReaction(18, null, __tr('Product not found.'));
        }

        $productCombination = $this->productRepository->fetchCombination($combinationId);

        // Check if product exist
        if (__isEmpty($productCombination)) {
            return $this->engineReaction(18, null, __tr('Product combination not found.'));
        }

        $productOptionValues = $this->productRepository->fetchCombinationOptions($combinationId);

        $optionValueIds = $productOptionValues->pluck('product_option_values__id');

        $combinationUpdateData = [
            'status' => 3, // Deleted
        ];

        if ($this->productRepository->deleteCombination($productCombination, $combinationUpdateData)) {
            if ($this->productRepository->deleteOptionValues($optionValueIds)) {
                if ($this->productRepository->deleteCombinationOptions($productCombination->_id, $optionValueIds)) {
                    return $this->engineReaction(1, null, __tr('Combination deleted successfully.'));
                }
            }
        }

        return $this->engineReaction(2, null, __tr('Combination not deleted.'));
    }

    /**
     * Process Product Option Value Delete
     *
     * @param  mix  $productId
     * @param  mix  $valueId
     * @return  array
     *---------------------------------------------------------------- */
    public function processProductOptionValueDelete($productId, $comboId, $valueId)
    {
        $product = $this->productRepository->fetch($productId);

        // Check if product exist
        if (__isEmpty($product)) {
            return $this->engineReaction(18, null, __tr('Product not found.'));
        }

        $productCombination = $this->productRepository->fetchCombination($comboId);

        if (__isEmpty($productCombination)) {
            return $this->engineReaction(18, null, __tr('Product combination does not exist.'));
        }

        $optionValue = $this->productRepository->fetchOptionValue($valueId);

        // Check if optionValue exist
        if (__isEmpty($optionValue)) {
            return $this->engineReaction(18, null, __tr('Option value not found.'));
        }

        $optionUpdateData = [
            'status' => 2, // Deleted
        ];

        if ($this->productRepository->deleteOptionValue($optionValue, $optionUpdateData)) {
            if ($this->productRepository->deleteCombinationOptions($productCombination->_id, [$optionValue->_id])) {
                return $this->engineReaction(1, null, __tr('Option value deleted successfully.'));
            }
        }

        return $this->engineReaction(2, null, __tr('Option value not deleted.'));
    }
}

<?php
/*
* ProductRepository.php - Repository file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\Barcodes\Models\BarcodesModel;
use App\Yantrana\Components\Product\Interfaces\ProductRepositoryInterface;
use App\Yantrana\Components\Product\Models\ProductCombinationModel;
use App\Yantrana\Components\Product\Models\ProductCombinationOptionModel;
use App\Yantrana\Components\Product\Models\ProductModel;
use App\Yantrana\Components\Product\Models\ProductOptionLabelModel;
use App\Yantrana\Components\Product\Models\ProductOptionValueModel;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * Search Product from DB
     *
     * @param  string  $searchTerm
     * @return  array|object
     *---------------------------------------------------------------- */
    public function searchProducts($searchTerm, $myLocationIds = [])
    {
        return ProductModel::leftJoin('product_combinations', 'products._id', '=', 'product_combinations.products__id')
            ->leftJoin('stock_transactions', 'product_combinations._id', '=', 'stock_transactions.product_combinations__id')
            ->leftJoin('locations', 'stock_transactions.locations__id', '=', 'locations._id')
            ->leftJoin('barcodes', 'barcodes.product_combinations__id', '=', 'product_combinations._id')
            ->leftJoin('categories', 'products.categories__id', '=', 'categories._id')
            ->shodh($searchTerm, ['products.name', 'product_combinations.title', 'product_combinations.product_id', 'barcodes.barcode', 'locations.name'])
            ->where(function ($query) use ($myLocationIds) {
                if (! canAccess('admin')) {
                    return $query->whereIn('stock_transactions.locations__id', $myLocationIds);
                } else {
                    return $query;
                }
            })
            ->select(
                __nestedKeyValues([
                    'products.*',
                    'product_combinations' => [
                        '_id AS product_combination_id',
                        'title',
                        'product_id',
                    ],
                    'stock_transactions' => [
                        '_id AS stock_tran_id',
                        'product_combinations__id',
                        'locations__id',
                        'suppliers__id',
                    ],
                    'barcodes' => [
                        'barcode',
                    ],
                    'categories' => [
                        '_id As category_id',
                        'status AS category_status',
                        'name AS category_name',
                    ],
                    'locations' => [
                        'name AS location_name',
                        'status AS location_status',
                    ],
                ])
            )
            ->get();
    }

    /**
     * Fetch the record of Product
     *
     * @param    int || string $idOrUid
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetch($idOrUid)
    {
        if (is_numeric($idOrUid)) {
            return ProductModel::where('_id', $idOrUid)->first();
        }

        return ProductModel::where('_uid', $idOrUid)->first();
    }

    /**
     * Fetch product datatable source
     *
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchProductDataTableSource()
    {
        $dataTableConfig = [
            'searchable' => [
                'products.name',
                'products.short_description',
            ],
        ];

        return ProductModel::leftJoin('categories', 'products.categories__id', '=', 'categories._id')
            ->whereNotIn('products.status', [3])
            ->select(
                __nestedKeyValues([
                    'products.*',
                    'categories' => [
                        '_id AS category_id',
                        'status AS category_status',
                        'name AS category_name',
                    ],
                ])
            )
            ->dataTables($dataTableConfig)->toArray();
    }

    /**
     * Delete $product record and return response
     *
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function deleteProduct($product, $updateData)
    {
        // Check if $product deleted
        if ($product->modelUpdate($updateData)) {
            activityLog(9, $product->_id, 3, $product->name);

            return true;
        }

        return false;
    }

    /**
     * Store new product record and return response
     *
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function storeProduct($inputData)
    {
        $keyValues = [
            'name',
            'short_description',
            'status' => 1,
            'user_authorities__id' => getUserAuthorityId(),
            'categories__id' => $inputData['category_id'],
            'tax_presets__id' => isset($inputData['tax_preset'])
                ? $inputData['tax_preset'] : null,
        ];

        $newProduct = new ProductModel();

        // Check if task testing record added then return positive response
        if ($newProduct->assignInputsAndSave($inputData, $keyValues)) {
            activityLog(9, $newProduct->_id, 1, $newProduct->name);

            return $newProduct;
        }

        return false;
    }

    /**
     * Update product record and return response
     *
     * @param  object  $product
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function updateProduct($product, $inputData)
    {
        // Check if $product updated then return positive response
        if ($product->modelUpdate($inputData)) {
            activityLog(9, $product->_id, 2, $product->name);

            return true;
        }

        return false;
    }

    /**
     * Store or Update Product Options
     *
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function storeOrUpdateProductOptions($inputData, $productId)
    {
        $userAuthorityId = getUserAuthorityId();
        $isDataUpdated = false;

        $optionValuesUpdateData = [];

        foreach ($inputData as $optionKey => $optionLabel) {
            $combinationOptions = [];
            if (isset($optionLabel['values'])) {
                foreach ($optionLabel['values'] as $key => $value) {
                    if ((array_key_exists('value_id', $value)) and (! __isEmpty($value['value_id']))) {
                        $optionValuesUpdateData[] = [
                            '_id' => $value['value_id'],
                            'name' => $value['value_name'],
                            'product_option_labels__id' => array_get($value, 'label_id'),
                        ];
                    } elseif (isset($value['label_id'])) {
                        $newProductOptionValue = new ProductOptionValueModel();
                        $optionValueKey = [
                            'status' => 1,
                            'product_option_labels__id' => $value['label_id'],
                            'name' => $value['value_name'],
                            'user_authorities__id' => $userAuthorityId,
                        ];

                        if ($newProductOptionValue->assignInputsAndSave($value, $optionValueKey)) {
                            $productoptionValueId = $newProductOptionValue->_id;
                            $combinationOptions[] = [
                                'status' => 1,
                                'product_option_values__id' => $productoptionValueId,
                                'product_combinations__id' => $optionLabel['_id'],
                            ];
                        }
                    }

                    if (! __isEmpty($optionValuesUpdateData)) {
                        ProductOptionValueModel::bunchUpdate($optionValuesUpdateData, '_id');
                        $isDataUpdated = true;
                    }
                }
            }
            $newProductCombinationOption = new ProductCombinationOptionModel();
            if (! __isEmpty($combinationOptions)) {
                if ($newProductCombinationOption->prepareAndInsert($combinationOptions)) {
                    $isDataUpdated = true;
                }
            }
        }

        if ($isDataUpdated) {
            return true;
        }

        return true;
    }

    /**
     * Fetch Product with Labels and values
     *
     * @param  number  $productId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchWithLabelsAndValue($productId)
    {
        $productModel = new ProductModel();

        if (is_numeric($productId)) {
            $productModel = $productModel->where('_id', $productId);
        } else {
            $productModel = $productModel->where('_uid', $productId);
        }

        return $productModel->with([
            'combinations' => function ($query) {
                $query->whereNotIn('status', [3])->with(['combinationBarcodes', 'combinationOptions' => function ($q) {
                    $q->where('status', 1)->with(['optionValue' => function ($value) {
                        $value->where('status', 1)->with('productOptionLabel');
                    }]);
                }]);
            },
        ])->first();
    }

    /**
     * Fetch Product with Labels and values
     *
     * @param  number  $productId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchDetails($productId)
    {
        $productModel = ProductModel::leftJoin('categories', 'categories._id', '=', 'products.categories__id');

        if (is_numeric($productId)) {
            $productModel = $productModel->where('products._id', $productId);
        } else {
            $productModel = $productModel->where('products._uid', $productId);
        }

        return $productModel->with([
            'combinations' => function ($query) {
                $query->whereNotIn('status', [3])->with(['combinationOptions' => function ($q) {
                    $q->where('status', 1)->with(['optionValue' => function ($value) {
                        $value->where('status', 1)->with('productOptionLabel');
                    }]);
                }]);
            },
        ])->select(
            __nestedKeyValues([
                'categories' => ['name as category_name'],
                'products.*',
            ])
        )->first();
    }

    /**
     * Store Options Labels
     *
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function storeOptionLabels($inputData, $productId)
    {
        $userAuthorityId = getUserAuthorityId();
        $storedLabels = [];
        foreach ($inputData as $key => $input) {
            $newProductOptionLabel = new ProductOptionLabelModel();
            $optionLabelKey = [
                'status' => 1,
                'name' => $input,
                'type' => 1,
                'products__id' => $productId,
                'user_authorities__id' => $userAuthorityId,
            ];
            if ($newProductOptionLabel->assignInputsAndSave($input, $optionLabelKey)) {
                $storedLabels[$input] = $newProductOptionLabel->_id;
            }
        }

        return $storedLabels;
    }

    /**
     * Store Options Labels and values
     *
     * @param  array  $storeData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function storeOptionLabelsAndValues($storeData, $productId, $productName)
    {
        $userAuthorityId = getUserAuthorityId();
        $isAdded = false;

        foreach ($storeData as $key => $input) {
            $newProductCombination = new ProductCombinationModel();

            $combinationKeyValues = [
                'status' => 1,
                'products__id' => $productId,
                'product_id' => array_get($input, 'product_id'),
                // 'barcode'       => array_get($input, 'barcode'),
                'title' => array_get($input, 'title'),
                'price' => array_get($input, 'price'),
                'sale_price' => array_get($input, 'selling_price'),
            ];

            // Check if product combination is stored
            if ($newProductCombination->assignInputsAndSave($input, $combinationKeyValues)) {
                // stored product option values
                $productCombinationId = $newProductCombination->_id;

                /* Start store combination barcodes */
                $barcodesData = [];

                foreach ($input['barcodes'] as $barcode) {
                    $barcodesData[] = [
                        'barcode' => $barcode,
                        'product_combinations__id' => $productCombinationId,
                    ];
                }

                $barcodesModelInstance = new BarcodesModel();

                if (! __isEmpty($barcodesData)) {
                    if (! $barcodesModelInstance->prepareAndInsert($barcodesData)) {
                        return false;
                    }
                }
                /* End storing combination barcodes */

                $combinationOptions = [];

                foreach ($input['values'] as $key => $value) {
                    $newProductOptionValue = new ProductOptionValueModel();

                    if (isset($value['label_id'])) {
                        $optionValueKey = [
                            'status' => 1,
                            'product_option_labels__id' => $value['label_id'],
                            'name' => array_get($value, 'value_name'),
                            'user_authorities__id' => $userAuthorityId,
                        ];

                        if ($newProductOptionValue->assignInputsAndSave($value, $optionValueKey)) {
                            $productoptionValueId = $newProductOptionValue->_id;
                            $newProductCombinationOption = new ProductCombinationOptionModel();
                            $combinationOptions = [
                                'status' => 1,
                                'product_option_values__id' => $productoptionValueId,
                                'product_combinations__id' => $productCombinationId,
                            ];
                            $newProductCombinationOption->assignInputsAndSave($value, $combinationOptions);
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * Fetch Combination
     *
     * @param  number  $combinationId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchCombination($combinationId)
    {
        return ProductCombinationModel::where('_id', $combinationId)->first();
    }

    /**
     * Fetch Combination with options
     *
     * @param  number  $combinationId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchCombinationWithOptions($combinationId, $locationId, $supplierId, $type = null)
    {
        return ProductCombinationModel::where('product_combinations._id', $combinationId)
            ->with([
                'combinationOptions' => function ($q) {
                    $q->with(['optionValue' => function ($value) {
                        $value->with('productOptionLabel');
                    }]);
                },
                'stockTrasactions' => function ($tranQuery) use ($locationId, $supplierId) {
                    /*if (($type != 'null')
                                                and (!__isEmpty($type))
                                                and ($type == 7)) {*/
                    $tranQuery->leftJoin('bills', 'stock_transactions.bills__id', '=', 'bills._id')
                        ->where(function ($q) {
                            $q->where('bills.status', '!=', 1)
                                ->orWhere('bills.status', '=', null);
                        })->select('stock_transactions.*', 'bills._id AS bill_id', 'bills.status AS bill_status');
                    //}

                    if (! __isEmpty($locationId)) {
                        $tranQuery->where('locations__id', $locationId);
                    }
                    if (! __isEmpty($supplierId)) {
                        $tranQuery->where('suppliers__id', $supplierId);
                    }
                },
            ])
            ->first();
    }

    /**
     * Update Combination Data
     *
     * @param  array  $combinationData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function updateCombinations($combinationData)
    {
        return ProductCombinationModel::bunchUpdate($combinationData, '_id');
    }

    /**
     * Update Option Label
     *
     * @param  object  $combination
     * @param  array  $updateData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function updateCombination($combination, $updateData)
    {
        if ($combination->modelUpdate($updateData)) {
            return true;
        }

        return false;
    }

    /**
     * Delete Combination
     *
     * @param  number  $labelId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function deleteCombination($combination, $updateData)
    {
        if ($combination->modelUpdate($updateData)) {
            activityLog(10, $combination->_id, 3, $combination->title);

            return true;
        }

        return false;
    }

    /**
     * Delete Combination
     *
     * @param  number  $labelId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchCombinationOptions($combinationId)
    {
        return ProductCombinationOptionModel::where('product_combinations__id', $combinationId)->get();
    }

    /**
     * Fetch Option Value
     *
     * @param  number  $valueId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchOptionValue($valueId)
    {
        return ProductOptionValueModel::where('_id', $valueId)->first();
    }

    /**
     * Fetch Option Label
     *
     * @param  number  $labelId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchOptionLabel($labelId)
    {
        return ProductOptionLabelModel::where('_id', $labelId)->first();
    }

    /**
     * Fetch Option Labels
     *
     * @param  number  $labelId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchLabels()
    {
        return ProductOptionLabelModel::where('status', 1)->get();
    }

    /**
     * Delete Option Value
     *
     * @param  number  $valueId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function deleteOptionValue($optionValue, $updateData)
    {
        if ($optionValue->modelUpdate($updateData)) {
            activityLog(11, $optionValue->_id, 3, $optionValue->name);

            return true;
        }

        return false;
    }

    /**
     * Delete Option Values
     *
     * @param  number  $valueId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function deleteOptionValues($valueIds)
    {
        if (ProductOptionValueModel::whereIn('_id', $valueIds)->update(['status' => 2])) { // Deletd
            return true;
        }

        return false;
    }

    /**
     * Delete Option Values
     *
     * @param  number  $valueId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function deleteCombinationOptions($combinationId, $valueIds)
    {
        if (ProductCombinationOptionModel::where('product_combinations__id', $combinationId)->whereIn('product_option_values__id', $valueIds)->update(['status' => 2])) { // Deletd
            return true;
        }

        return false;
    }

    /**
     * Delete Option Label
     *
     * @param  number  $labelId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function deleteOptionLabel($optionLabel)
    {
        if ($optionLabel->delete()) {
            activityLog(10, $optionLabel->_id, 3, $optionLabel->name);

            return true;
        }

        return false;
    }

    /**
     * Update Option Label
     *
     * @param  object  $optionLabel
     * @param  array  $updateData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function updateOptionLabel($optionLabel, $updateData)
    {
        if ($optionLabel->modelUpdate($updateData)) {
            activityLog(10, $optionLabel->_id, 2, $optionLabel->name);

            return true;
        }

        return false;
    }

    /**
     * Update Option Value
     *
     * @param  array  $optionValue
     * @param  array  $updateData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function updateOptionValue($optionValue, $updateData)
    {
        if ($optionValue->modelUpdate($updateData)) {
            activityLog(11, $optionValue->_id, 2, $optionValue->name);

            return true;
        }

        return false;
    }

    /**
     * Fetch the record of Inventory
     *
     * @param    int || string $idOrUid
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProducts($listOptions, $categoryId, $productsIds = [], $myLocationIds = [], $options = [])
    {
        $modelInstance = ProductModel::leftJoin('categories', 'products.categories__id', '=', 'categories._id')
            ->where('products.status', 1)
            ->orderBy($listOptions['sortBy'], $listOptions['sortOrder'])
            ->leftJoin('product_combinations', 'products._id', '=', 'product_combinations.products__id')
            ->join('stock_transactions', 'product_combinations._id', '=', 'stock_transactions.product_combinations__id')
            ->groupBy('products._id')
            ->whereIn('stock_transactions.locations__id', $myLocationIds)
            ->where(function ($query) use ($categoryId) {
                if (! __isEmpty($categoryId)) {
                    return $query->where([
                        'products.categories__id' => $categoryId,
                    ]);
                }
            });

        // if (!__isEmpty($myLocationIds) and !canAccess('admin')) {
        //     $modelInstance->whereIn('locations__id', $myLocationIds);
        // }

        if (array_get($options, 'is_search_term_exist', false) or ! __isEmpty($productsIds)) {
            $modelInstance->whereIn('products._id', $productsIds);
        }

        return $modelInstance->select(
            __nestedKeyValues([
                'products' => [
                    '_id',
                    '_uid',
                    'status',
                    'name',
                    'categories__id',
                    'created_at',
                ],
                'categories' => [
                    '_id AS category_id',
                    'name AS category_name',
                    'status AS category_status',
                ],
                'product_combinations' => [
                    '_id AS product_combination_id',
                    'title',
                    'product_id',
                ],
                'stock_transactions' => [
                    '_id AS stock_tran_id',
                    'product_combinations__id',
                    'locations__id',
                ],
            ])
        )
            ->paginate(10);
    }

    /**
     * Fetch Combinations by product ids
     *
     * @param  array  $productIds
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCombinationByProductIds($productIds, $inventoryId, $myLocationIds)
    {
        return ProductCombinationModel::whereIn('products__id', $productIds)
            ->join('stock_transactions', 'product_combinations._id', '=', 'stock_transactions.product_combinations__id')
            ->leftJoin('bills', 'stock_transactions.bills__id', '=', 'bills._id')
            ->leftJoin('locations', 'stock_transactions.locations__id', '=', 'locations._id')
            ->leftJoin('suppliers', 'stock_transactions.suppliers__id', '=', 'suppliers._id')
            ->where('locations.status', 1)
            ->whereIn('locations._id', $myLocationIds)
            ->where('bills.status', 2) // Paid Bill
            ->orWhere('bills.status', '=', null)
            ->where(function ($query) use ($myLocationIds) {
                if (! canAccess('admin')) {
                    $query->whereIn('stock_transactions.locations__id', $myLocationIds);
                }
            })
            ->select(
                __nestedKeyValues([
                    'product_combinations' => [
                        '_id',
                        'products__id',
                        'product_id',
                        'status',
                        'title',
                        'price',
                        'sale_price',
                    ],
                    'stock_transactions' => [
                        '_id AS stock_transaction_id',
                        'quantity',
                        'type',
                        'sub_type',
                        'locations__id',
                        'total_price',
                        'total_amount',
                        'suppliers__id',
                        'product_combinations__id',
                        'currency_code',
                    ],
                    'locations' => [
                        '_id AS location_id',
                        'name',
                        'status AS location_status',
                    ],
                    'suppliers' => [
                        '_id AS supplier_id',
                        'name AS supplier_name',
                    ],
                    'bills' => [
                        '_id AS bill_id',
                        'status AS bill_status',
                    ],
                ])
            )
            ->where([
                'product_combinations.status' => 2,
                'locations.status' => 1,
            ])
            ->get();
    }

    /**
     * Fetch Combination Options by Combination Ids
     *
     * @param  array  $combinationIds
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCombinationOptionsByCombinationIds($combinationIds)
    {
        return ProductCombinationOptionModel::whereIn('product_combinations__id', $combinationIds)
            ->where('status', 1)
            ->with([
                'optionValue' => function ($value) {
                    $value->select(
                        '_id',
                        'product_option_labels__id',
                        'name'
                    )->with([
                        'productOptionLabel' => function ($label) {
                            $label->select(
                                '_id',
                                'name',
                                'type',
                                'products__id',
                                'user_authorities__id'
                            );
                        },
                    ]);
                },
            ])->get();
    }

    /**
     * Fetch Options Values by ids
     *
     * @param  array  $valueIds
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchOptionValuesByIds($valueIds)
    {
        return ProductOptionValueModel::whereIn('_id', $valueIds)->get();
    }

    /**
     * Fetch All Products with stock transactions
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAllProductWithStockTransactions()
    {
        return ProductModel::leftJoin('categories', 'products.categories__id', '=', 'categories._id')
            ->with([
                'combinations' => function ($query) {
                    $query->with('stockTrasactions');
                },
            ])
            ->select(
                __nestedKeyValues([
                    'products.*',
                    'categories' => [
                        'status as category_status',
                    ],
                ])
            )
            ->get();
    }

    /**
     * Fetch Combination with stock transactions
     *
     * @param  array  $combinationIds
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCombinationWithStockTransaction($combinationIds)
    {
        return ProductCombinationModel::whereIn('_id', $combinationIds)
            ->with(['stockTrasactions' => function ($query) {
                $query->leftJoin('bills', 'stock_transactions.bills__id', '=', 'bills._id')
                    ->where('bills.status', '!=', 1)
                    ->orWhere('bills.status', '=', null)
                    ->select('stock_transactions.*', 'bills._id AS bill_id', 'bills.status AS bill_status');
            }])->get();
    }

    /**
     * Fetch Transactins by combination id
     *
     * @param  array  $combinationIds
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchTransactions($combinationId, $locationId)
    {
        return ProductCombinationModel::where('_id', $combinationId)
            ->with([
                'combinationOptions' => function ($q) {
                    $q->with(['optionValue' => function ($value) {
                        $value->with('productOptionLabel');
                    }]);
                    $q->where('status', 1);
                },
                'stockTrasactions' => function ($tranQuery) use ($locationId) {
                    $tranQuery->where('locations__id', $locationId)
                        ->leftJoin('bills', 'stock_transactions.bills__id', '=', 'bills._id')
                        ->orderBy('stock_transactions.updated_at')
                        ->where(function ($q) {
                            $q->where('bills.status', '!=', 1)
                                ->orWhere('bills.status', '=', null);
                        })
                        ->select('stock_transactions.*', 'bills._id AS bill_id', 'bills.status AS bill_status')
                        ->with(['supplier', 'location']);
                },
            ])->first();
    }

    /**
     * Fetch Product with Labels and values
     *
     * @param  number  $productId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchProductForTransaction($productId, $myLocationIds)
    {
        $productModel = new ProductModel();

        if (is_numeric($productId)) {
            $productModel = $productModel->where('_id', $productId);
        } else {
            $productModel = $productModel->where('_uid', $productId);
        }

        return $productModel->with([
            'combinations' => function ($query) use ($myLocationIds) {
                $query->with(['combinationOptions' => function ($q) {
                    $q->where('status', 1);
                    $q->with(['optionValue' => function ($value) {
                        $value->with('productOptionLabel');
                    }]);
                }, 'stockTrasactions' => function ($tranQuery) use ($myLocationIds) {
                    if (! canAccess('admin')) {
                        $tranQuery->whereIn('locations__id', $myLocationIds);
                    }
                    $tranQuery->with(['supplier', 'location'])
                        ->leftJoin('bills', 'stock_transactions.bills__id', '=', 'bills._id')
                        ->orderBy('stock_transactions.updated_at')
                        ->where(function ($q) {
                            $q->where('bills.status', '!=', 1)
                                ->orWhere('bills.status', '=', null);
                        })
                        ->select('stock_transactions.*', 'bills._id AS bill_id', 'bills.status AS bill_status');
                }]);
            },
        ])->first();
    }

    /**
     * Fetch Product with Labels and values
     *
     * @param  number  $productId
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchCombinationBarcodes($productCombinationIds, $barcodesStrings)
    {
        return BarcodesModel::whereIn('product_combinations__id', $productCombinationIds)
            ->whereIn('barcode', $barcodesStrings)->get();
    }

    /**
     * Search Product from DB
     *
     * @param  string  $searchTerm
     * @return  array
     *---------------------------------------------------------------- */
    public function searchProductsForBilling($searchTerm)
    {
        return ProductModel::leftJoin('product_combinations', 'products._id', '=', 'product_combinations.products__id')
            ->leftJoin('barcodes', 'barcodes.product_combinations__id', '=', 'product_combinations._id')
            ->leftJoin('categories', 'products.categories__id', '=', 'categories._id')
            ->leftJoin('stock_transactions', 'product_combinations._id', '=', 'stock_transactions.product_combinations__id')
            ->leftJoin('locations', 'stock_transactions.locations__id', '=', 'locations._id')
            ->shodh($searchTerm, ['products.name', 'product_combinations.title', 'product_combinations.product_id', 'barcodes.barcode', 'locations.name'])
            ->where([
                'products.status' => 1,
                'categories.status' => 1,
            ])
            ->groupBy('products._id')
            ->pluck('products._id');
    }

    /**
     * fetch Combinations By Products Ids
     *
     * @param  array  $productIds
     * @return  array
     *---------------------------------------------------------------- */
    public function fetchCombinationsByProductsIds($productIds, $myLocationIds = [])
    {
        return ProductCombinationModel::whereNotIn('product_combinations.status', [3])
            ->whereIn('products__id', $productIds)
            ->join('products', 'product_combinations.products__id', '=', 'products._id')
            ->leftJoin('barcodes', 'barcodes.product_combinations__id', '=', 'product_combinations._id')
            ->with([
                'stockTrasactions' => function ($transQuery) use ($myLocationIds) {
                    $transQuery->with([
                        'location' => function ($locQuery) use ($myLocationIds) {
                            if (! __isEmpty($myLocationIds) and ! canAccess('admin')) {
                                $locQuery->whereIn('_id', $myLocationIds);
                            }
                            $locQuery->whereIn('status', [1, 3]);
                        },
                        'bill' => function ($billQuery) {
                            $billQuery->where('status', '!=', 1);
                        },
                    ])
                        ->has('location')
                        ->leftJoin('bills', 'stock_transactions.bills__id', '=', 'bills._id')
                        ->select('stock_transactions.*', 'bills._id AS bill_id', 'bills.status AS bill_status')
                        ->where('bills.status', '!=', 1)
                        ->orWhere('bills.status', '=', null);
                },
                'taxPreset',
                'combinationOptions' => function ($comboQuery) {
                    $comboQuery->where('status', 1)->with([
                        'optionValue' => function ($valueQuery) {
                            $valueQuery->where('status', 1)->with('productOptionLabel');
                        },
                    ]);
                },
            ])
            ->select(
                __nestedKeyValues([
                    'product_combinations' => ['*'],
                    'products' => [
                        '_id AS productId',
                        'name AS product_name',
                        'tax_presets__id',
                    ],
                    'barcodes' => [
                        'barcode',
                    ],
                ])
            )
            ->get();
    }

    /**
     * fetch Combinations By Products Ids
     *
     * @param  array  $productIds
     * @return  array
     *---------------------------------------------------------------- */
    public function fetchLockQuantityForProductCombination($productIds, $myLocationIds = [])
    {
        return ProductCombinationModel::whereNotIn('product_combinations.status', [3])
            ->whereIn('product_combinations.products__id', $productIds)
            ->join('products', 'product_combinations.products__id', '=', 'products._id')
            ->with([
                'stockTrasactions' => function ($transQuery) use ($myLocationIds) {
                    $transQuery->with([
                        'location' => function ($locQuery) use ($myLocationIds) {
                            if (! __isEmpty($myLocationIds) and ! canAccess('admin')) {
                                $locQuery->whereIn('_id', $myLocationIds);
                            }
                            $locQuery->where('status', 1);
                        }, 'bill',
                    ])
                        ->leftJoin('bills', 'stock_transactions.bills__id', '=', 'bills._id')
                        ->where('bills.status', 1)
                        ->select('stock_transactions.*', 'bills._id AS bill_id', 'bills.status AS bill_status');
                },
            ])
            ->select(
                __nestedKeyValues([
                    'product_combinations' => ['*'],
                    'products' => [
                        '_id AS productId',
                        'name AS product_name',
                        'tax_presets__id',
                    ],
                ])
            )->get();
    }

    /**
     * Fetch all inactive products
     *
     * @param  array  $productIds
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchInActiveProducts($productIds)
    {
        return ProductModel::whereIn('_id', $productIds)
            ->where('status', '=', 2)
            ->pluck('_id')
            ->toArray();
    }

    /**
     * Fetch Deleted Product Combinations
     *
     * @param  array  $combinationIds
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchDeletedProductCombination($combinationIds)
    {
        return ProductCombinationModel::whereIn('_id', $combinationIds)
            ->where('status', 3)
            ->pluck('_id');
    }

    /**
     * Update Products by category id
     *
     * @param  array  $combinationIds
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function updateProducts($categoryId, $updateData)
    {
        return ProductModel::where('categories__id', $categoryId)
            ->update($updateData);
    }
}

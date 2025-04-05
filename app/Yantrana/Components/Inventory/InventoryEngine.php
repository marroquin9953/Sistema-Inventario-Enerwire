<?php
/*
* InventoryEngine.php - Main component file
*
* This file is part of the Inventory component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Inventory;

use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\Category\Repositories\CategoryRepository;
use App\Yantrana\Components\Customer\Repositories\CustomerRepository;
use App\Yantrana\Components\Inventory\Interfaces\InventoryEngineInterface;
use App\Yantrana\Components\Inventory\Repositories\InventoryRepository;
use App\Yantrana\Components\Location\Repositories\LocationRepository;
use App\Yantrana\Components\Product\Repositories\ProductRepository;
use App\Yantrana\Components\Suppliers\Repositories\SuppliersRepository;
use Request;

class InventoryEngine extends BaseEngine implements InventoryEngineInterface
{
    /**
     * @var  InventoryRepository - Inventory Repository
     */
    protected $inventoryRepository;

    /**
     * @var  LocationRepository - Location Repository
     */
    protected $locationRepository;

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
     * @var  CustomerRepository - Customer Repository
     */
    protected $customerRepository;

    /**
     * Constructor
     *
     * @param  InventoryRepository  $inventoryRepository - Inventory Repository
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(
        InventoryRepository $inventoryRepository,
        LocationRepository $locationRepository,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        SuppliersRepository $suppliersRepository,
        CustomerRepository $customerRepository
    ) {
        $this->inventoryRepository = $inventoryRepository;
        $this->locationRepository = $locationRepository;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->suppliersRepository = $suppliersRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Unique multidim array.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    protected function uniqueMultidimArray($array, $key)
    {
        $tempArray = [];
        $i = 0;
        $keyArray = [];

        foreach ($array as $aKey => $val) {
            if (! in_array($val[$key], $keyArray)) {
                $keyArray[$aKey] = $val[$key];
                $tempArray[$aKey] = $val;
            }
            $i++;
        }

        return $tempArray;
    }

    /**
     * Calculate quantity based on type.
     *
     * @return int|float
     *---------------------------------------------------------------- */
    public function calculateQuantityByType($stockTransactions)
    {
        $quantity = 0;
        if (! __isEmpty($stockTransactions)) {
            $stockInQuatity = $stockOutQuatity = [];
            foreach ($stockTransactions as $key => $stockTransaction) {
                // Check if location exist
                if ($stockTransaction['type'] == 1) {
                    $stockOutQuatity[] = $stockTransaction['quantity'];
                } elseif ($stockTransaction['type'] == 2) {
                    $stockInQuatity[] = $stockTransaction['quantity'];
                }
            }
            $quantity = array_sum($stockInQuatity) - array_sum($stockOutQuatity);
        }
        // return quantity
        return $quantity;
    }

    /**
     * Prepare inventory list.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function prepareList()
    {
        $sortOrder = request()->input('sort_order');
        $sortBy = request()->input('sort_by');
        $sortOrder = (! __isEmpty($sortOrder)) ? $sortOrder : 'desc';
        $sortBy = (! __isEmpty($sortBy)) ? $sortBy : 'products.created_at';
        $searchTerm = request()->input('search_term');

        $supplierId = request()->input('supplier_id');
        $categoryId = request()->input('category_id');
        $inventoryId = request()->input('inventory_id');

        $listOptions = [
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'searchTerm' => $searchTerm,
        ];

        $inventoryId = ($inventoryId == 'null') ? null : $inventoryId;
        $categoryId = ($categoryId == 'null') ? null : $categoryId;
        $supplierId = ($supplierId == 'null') ? null : $supplierId;

        $myLocationIds = [];
        $myLocations = $this->locationRepository->fetchMyLocations(getUserAuthorityId());

        if (! __isEmpty($myLocations)) {
            $myLocationIds = $myLocations->pluck('_id')->toArray();
        }

        $productsIds = [];
        $isSearchTermExist = false;

        if ($listOptions['searchTerm'] != null and $listOptions['searchTerm'] != '') {
            $searchedProducts = $this->productRepository->searchProducts($listOptions['searchTerm']);
            $isSearchTermExist = true;
            $productsIds = array_unique($searchedProducts->pluck('_id')->toArray(), SORT_REGULAR);
        }

        // Fetch Product pagination data
        $productCollection = $this->productRepository->fetchProducts($listOptions, $categoryId, $productsIds, $myLocationIds, [
            'is_search_term_exist' => $isSearchTermExist,
        ]);

        // Make a pagination links for product collection
        $paginationLinks = sprintf($productCollection->withQueryString()->links());

        // Get all fetch product Ids
        $productIds = $productCollection->pluck('_id')->toArray();
        $productIds = array_unique($productIds, SORT_REGULAR);

        $combinationIds = [];
        // fetch all related combination by product ids
        $combinationCollection = $this->productRepository
            ->fetchCombinationByProductIds($productIds, $inventoryId, $myLocationIds);

        $combinationIds = $combinationCollection->pluck('_id')->toArray();
        $combinationIds = array_unique($combinationIds, SORT_REGULAR);

        $combinationPrices = [];
        $combinationWithStocks = $this->productRepository->fetchCombinationWithStockTransaction($combinationIds);

        // check if combination stocks exist
        if (! __isEmpty($combinationWithStocks)) {
            foreach ($combinationWithStocks as $key => $combinationWithStock) {
                $warehouses = $combinationWithStock->stockTrasactions->groupBy('locations__id');
                foreach ($warehouses as $warehousesKey => $warehousesValue) {
                    $combinationPrices[$combinationWithStock->_id][$warehousesKey] = $this->calculateQuantityByType($warehousesValue);
                }
            }
        }

        $productCombinations = [];
        foreach ($combinationCollection as $combinationKey => $combination) {
            $productCombinations[] = [
                'product_id' => $combination->product_id,
                'combination_id' => $combination->_id,
                'status' => $combination->status,
                'combination_title' => $combination->title,
                'totalStock' => $combinationPrices[$combination->_id][$combination->locations__id],
                'formattedPrice' => moneyFormat($combination->price, true),
                'formattedSalePrice' => moneyFormat($combination->sale_price, true),
                'location_id' => $combination->locations__id,
                'location' => $combination->name,
                'location_status' => $combination->location_status,
                'products__id' => $combination->products__id,
                'supplier_id' => $combination->supplier_id,
            ];
        }

        // Make a group of combination by product id
        $groupCombination = collect($productCombinations)->groupBy('products__id')->toArray();

        $combinationData = [];
        if (! __isEmpty($groupCombination)) {
            foreach ($groupCombination as $combinationKey => $combination) {
                $combinationData[$combinationKey] = collect($combination)->groupBy('location')->toArray();
            }
        }

        $combinationOptions = $this->productRepository->fetchCombinationOptionsByCombinationIds($combinationIds);
        $groupComboOptions = $combinationOptions->groupBy('product_combinations__id');

        $combinations = [];
        foreach ($groupComboOptions as $groupComboOptionsKey => $groupComboOptionsValue) {
            foreach ($groupComboOptionsValue as $optionKey => $option) {
                $combinations[$groupComboOptionsKey][] = [
                    'label_name' => $option->optionValue->productOptionLabel->name,
                    'value_name' => $option->optionValue->name,
                ];
            }
        }

        foreach ($combinationData as $comboKey => $comboValue) {
            foreach ($comboValue as $locationKey => $locationValue) {
                foreach ($locationValue as $key => $value) {
                    if (is_array($value)) {
                        $combinationData[$comboKey][$locationKey] = $this->uniqueMultidimArray($locationValue, 'combination_id');
                    }
                }
            }
        }

        foreach ($combinationData as $comboKey => $comboValue) {
            foreach ($comboValue as $locationKey => $locationValue) {
                foreach ($locationValue as $key => $value) {
                    if (is_array($value) and isset($combinations[$value['combination_id']])) {
                        $combinationData[$comboKey][$locationKey][$key]['combinations'] = $combinations[$value['combination_id']];
                    }
                    $combinationData[$comboKey][$locationKey]['totalStock'] = collect($locationValue)->sum('totalStock');
                }
            }
        }

        $productData = [];
        $productIdsArray = [];
        foreach ($productCollection as $productKey => $productValue) {
            if (isset($combinationData[$productValue->_id])) {
                if (! in_array($productValue->_id, $productIdsArray)) {
                    $productData[] = [
                        'productTitle' => $productValue->name,
                        'productId' => $productValue->_uid,
                        'productsId' => $productValue->_id,
                        'productStatus' => $productValue->status,
                        'categoryStatus' => $productValue->category_status,
                        'categoryName' => $productValue->category_name,
                        'totalStockInAllLocation' => collect($combinationData[$productValue->_id])->sum('totalStock'),
                        'combinationData' => $combinationData[$productValue->_id],
                    ];
                }

                $productIdsArray[] = $productValue->_id;
            }
        }

        // Fetch category and suppliers and prepare list
        $categoryCollection = $this->categoryRepository->fetchAllCategory();
        $categories = [];

        // Check if category exist
        if (! __isEmpty($categoryCollection)) {
            foreach ($categoryCollection as $key => $category) {
                $formattedStatus = '';
                if ($category->status == 2) {
                    $formattedStatus = '(Inactive)';
                } elseif ($category->status == 3) {
                    $formattedStatus = '(Deleted)';
                }

                $categories[] = [
                    'id' => $category->_id,
                    'name' => $category->name.' '.$formattedStatus,
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

        $inventories = [];
        $invetorySelectList = configItem('invetory');
        foreach ($invetorySelectList as $invetoryKey => $invetory) {
            $inventories[] = [
                'id' => $invetoryKey,
                'name' => $invetory,
            ];
        }

        return $this->engineReaction(1, [
            'invetoryData' => $productData,
            'paginationLinks' => $paginationLinks,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'inventories' => $inventories,
            'sortBy' => $sortBy,
            'sortOrder' => ($sortOrder == 'desc') ? 'asc' : 'desc',
            'productCollection' => $productCollection,
            'sortOrderUrl' => Request::fullUrl(),
        ]);
    }

    /**
     * Prepare inventory update data.
     *
     * @param  string  $productId
     * @param  string  $comboKey
     * @param  string  $locationUid
     * @param  string  $supplierId
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function prepareInventoryUpdateData($productId, $combinationId, $locationUid, $supplierId)
    {
        $combinations = [];
        $showInactiveMessage = false;

        if ($productId != 'null') {
            $product = $this->productRepository->fetchWithLabelsAndValue($productId);

            // Check if product exist
            if (__isEmpty($product)) {
                return $this->engineReaction(18, null, __tr('Product does not exist.'));
            }

            $category = $this->categoryRepository->fetch($product->categories__id);

            // Check if product exist
            if (__isEmpty($category)) {
                return $this->engineReaction(18, null, __tr('Category does not exist.'));
            }

            if ($category->status == 2 or $product->status == 2) {
                $showInactiveMessage = true;
            }

            foreach ($product['combinations'] as $combinationKey => $productCombination) {
                $combinationOption = '';
                foreach ($productCombination['combinationOptions'] as $key => $options) {
                    $combinationOption .= '('.$options->optionValue->productOptionLabel->name.' : '.$options->optionValue->name.') ';
                }

                $combinations[] = [
                    'id' => $productCombination->_id,
                    'name' => $productCombination->title.' ( '.$productCombination->product_id.' )',
                    'combinationOption' => $combinationOption,
                ];
            }
        }

        $locationId = null;
        $isLocationExist = false;
        // Check if location id exist
        if ($locationUid != 'null') {
            $locationData = $this->locationRepository->fetch($locationUid);

            if (__isEmpty($locationData)) {
                return $this->engineReaction(18, null, __tr('Location does not exist.'));
            }

            $locationId = $locationData->_id;
            $isLocationExist = true;
        }

        $supplierNumericId = null;
        if ($supplierId != 'null') {
            $supplier = $this->suppliersRepository->fetch($supplierId);

            if (__isEmpty($supplier)) {
                return $this->engineReaction(18, null, __tr('Supplier does not exist.'));
            }

            $supplierNumericId = $supplier->_id;
        }

        $availableQuantity = 0;
        $combinationExist = false;
        if ($combinationId != 'null') {
            $combination = $this->productRepository->fetchCombinationWithOptions($combinationId, $locationId, $supplierNumericId);

            // Check if combination exist
            if (__isEmpty($combination)) {
                return $this->engineReaction(18, null, __tr('Combination does not exist.'));
            }
            $availableQuantity = $this->calculateQuantityByType($combination->stockTrasactions);
            $combinationExist = true;
        }

        $allProducts = $this->productRepository->fetchAllProductWithStockTransactions();
        $productData = [];
        if (! __isEmpty($allProducts)) {
            foreach ($allProducts as $productKey => $productValue) {
                $isStockTransactionExist = [];
                foreach ($productValue['combinations'] as $productCombo) {
                    $isStockTransactionExist[] = ($productCombo->status == 1)
                        ? true : false;
                }

                // Check if quantity exist
                if (in_array(true, $isStockTransactionExist)) {
                    if (($productValue->status == 1) and ($productValue->category_status == 1)) {
                        $productData[] = [
                            'id' => $productValue->_id,
                            'name' => $productValue->name,
                        ];
                    }
                }
            }
        }

        $locations = [];
        if (canAccess('admin')) {
            $locationCollection = $this->locationRepository->fetchLocations();
        } else {
            $locationCollection = $this->locationRepository->fetchMyLocations(getUserAuthorityId());
        }

        // Check if locations are exist
        if (! __isEmpty($locationCollection)) {
            foreach ($locationCollection as $key => $location) {
                if ($location->status == 1) {
                    $locations[] = [
                        'id' => $location->_id,
                        'name' => $location->name,
                    ];
                }
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

        $customerCollection = $this->customerRepository->fetchAll();
        $customers = [];

        // Check if customer exist
        if (! __isEmpty($customerCollection)) {
            foreach ($customerCollection as $key => $customer) {
                $customers[] = [
                    'id' => $customer->_id,
                    'name' => $customer->name,
                ];
            }
        }

        $subTypes = configItem('stock_transactions.sub_types');
        unset($subTypes[2]);

        return $this->engineReaction(1, [
            'combinations' => $combinations,
            'combinationExist' => $combinationExist,
            'locationExist' => $isLocationExist,
            'locationId' => $locationId,
            'availableQuantity' => $availableQuantity,
            'products' => $productData,
            'locations' => $locations,
            'suppliers' => $suppliers,
            'customers' => $customers,
            'showInactiveMessage' => $showInactiveMessage,
            'subTypes' => $subTypes,
            'currency' => getCurrency(),
            'currencySymbol' => getCurrencySymbol(),
        ]);
    }

    /**
     * Prepare Product Combinations
     *
     * @param  string  $productId
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function prepareProductCombination($productId)
    {
        $product = $this->productRepository->fetchWithLabelsAndValue($productId);

        // Check if product exist
        if (__isEmpty($product)) {
            return $this->engineReaction(18, null, __tr('Product does not exist.'));
        }

        $combinations = [];
        foreach ($product['combinations'] as $combinationKey => $productCombination) {
            if ($productCombination->status == 1) {
                $combinationOption = '';
                foreach ($productCombination['combinationOptions'] as $key => $options) {
                    $combinationOption .= '('.$options->optionValue->productOptionLabel->name.' : '.$options->optionValue->name.') ';
                }
                $combinations[] = [
                    'id' => $productCombination->_id,
                    'name' => $productCombination->title.' ( '.$productCombination->product_id.' )',
                    'combinationOption' => $combinationOption,
                ];
            }
        }

        return $this->engineReaction(1, [
            'combinations' => $combinations,
        ]);
    }

    /**
     * Calculate process quantity
     *
     * @param  string  $productId
     * @param  string  $combinationId
     * @param  string  $locationUid
     * @param  string  $supplierId
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function processCalcuateQuantity($productId, $combinationId, $locationUid, $supplierId, $type)
    {
        if ($productId != 'null') {
            $product = $this->productRepository->fetchWithLabelsAndValue($productId);

            // Check if product exist
            if (__isEmpty($product)) {
                return $this->engineReaction(18, null, __tr('Product does not exist.'));
            }
        }

        $availableQuantity = 0;
        $locationId = null;
        if ($locationUid != 'null') {
            $locationData = $this->locationRepository->fetch($locationUid);
            $locationId = $locationData->_id;
        }

        $supplierNumericId = null;
        if ($supplierId != 'null') {
            $supplier = $this->suppliersRepository->fetch($supplierId);

            if (__isEmpty($supplier)) {
                return $this->engineReaction(18, null, __tr('Supplier does not exist.'));
            }

            $supplierNumericId = $supplier->_id;
        }

        if ($combinationId != 'null') {
            $combinations = $this->productRepository->fetchCombinationWithOptions($combinationId, $locationId, null, $type);

            // Check if combination exist
            if (__isEmpty($combinations)) {
                return $this->engineReaction(18, null, __tr('Combination does not exist.'));
            }

            $availableQuantity = $this->calculateQuantityByType($combinations->stockTrasactions);

            if ($type != 'null') {
                if ($type == 3) { // Purchase Return
                    // Check if supplier id exist
                    if (! __isEmpty($supplierNumericId)) {
                        $supplierStockTransaction = $combinations->stockTrasactions->where('suppliers__id', $supplierNumericId);
                        $supplierReturnQty = $supplierStockTransaction->where('type', 1)->sum('quantity');
                        $supplierPurchaseQty = $supplierStockTransaction->where('type', 2)->sum('quantity');
                        $supplierAvailableQty = $supplierPurchaseQty - $supplierReturnQty;
                        $availableQuantity = ($supplierAvailableQty < $availableQuantity)
                            ? $supplierAvailableQty
                            : $availableQuantity;
                    } elseif (__isEmpty($supplierNumericId)) {
                        $supplierStockTransaction = $combinations->stockTrasactions->where('suppliers__id', null);
                        $availableQuantity = $supplierStockTransaction->where('type', 2)->sum('quantity');
                    }
                }
                if ($type == 7) { // Sale Return
                    $saleReturnQty = $combinations->stockTrasactions->where('sub_type', 7)->sum('quantity');
                    $availableQuantity = $combinations->stockTrasactions->where('sub_type', 2)->sum('quantity');
                    $availableQuantity = $availableQuantity - $saleReturnQty;
                }
                if ($type == 1) {
                    if (! __isEmpty($supplierNumericId)) {
                        $purchaseStockTransaction = $combinations->stockTrasactions->where('suppliers__id', $supplierNumericId);
                        $saleQty = $combinations->stockTrasactions->where('type', 1)->sum('quantity');
                        $purchaseQty = $purchaseStockTransaction->where('type', 2)->sum('quantity');
                        $availableQuantity = $purchaseQty - $saleQty;

                        if ($availableQuantity < 0) {
                            $availableQuantity = 0;
                        }
                    }
                }
            }
        }

        return $this->engineReaction(1, [
            'availableQuantity' => $availableQuantity,
        ]);
    }

    /**
     * Process Update Inventory
     *
     * @param  array  $inputData
     * @param  string  $productId
     * @return eloquent collection object processUpdateInventory
     *---------------------------------------------------------------- */
    public function processUpdateInventory($inputData, $productId)
    {
        $reactionCode = $this->inventoryRepository
            ->processTransaction(function () use ($inputData, $productId) {
                $product = $this->productRepository->fetch($productId);

                // Check if product exist
                if (__isEmpty($product)) {
                    return $this->inventoryRepository->transactionResponse(18, null, __tr('Product does not exist.'));
                }

                // Check if product exist
                if ($product->status == 2) {
                    return $this->inventoryRepository->transactionResponse(2, null, __tr('Inventory cannot be added as product is inactive.'));
                }

                $category = $this->categoryRepository->fetch($product->categories__id);

                // Check if category exist
                if (__isEmpty($category)) {
                    return $this->inventoryRepository->transactionResponse(18, null, __tr('Product Category does not exist.'));
                }

                // Check if category exist
                if ($category->status == 2) {
                    return $this->inventoryRepository->transactionResponse(2, null, __tr('Inventory cannot be added as product category is inactive.'));
                }

                $inputData['product_id'] = $product->_id;
                $totalAddonPrice = 0;
                $productValues = null;

                $locationData = $this->locationRepository->fetch($inputData['location']);

                // check if location exist
                if (__isEmpty($locationData)) {
                    return $this->inventoryRepository->transactionResponse(18, null, __tr('Location does not exist.'));
                }

                if ($locationData->status == 2) {
                    return $this->inventoryRepository->transactionResponse(2, null, __tr('Inventory cannot be added as location is inactive.'));
                }

                $supplierNumericId = null;
                // check if supplier exist
                if (isset($inputData['supplier']) and ! __isEmpty($inputData['supplier'])) {
                    $supplierData = $this->suppliersRepository->fetch($inputData['supplier']);

                    // check if location exist
                    if (__isEmpty($supplierData)) {
                        return $this->inventoryRepository->transactionResponse(18, null, __tr('Supplier does not exist.'));
                    }

                    $supplierNumericId = $supplierData->_id;
                }

                $inputData['location_name'] = $locationData->name;
                $inputData['product_name'] = $product->name;

                $combinationId = $inputData['combination'];
                $locationId = $inputData['location'];

                $combinations = $this->productRepository->fetchCombinationWithOptions($combinationId, $locationId, null, $inputData['sub_type']);

                // Check if combination exist
                if (__isEmpty($combinations)) {
                    return $this->engineReaction(18, null, __tr('Combination does not exist.'));
                }

                if ($combinations->status != 2) {
                    $combinationUpdateData = [
                        'status' => 2,
                    ];
                    $this->productRepository->updateCombination($combinations, $combinationUpdateData);
                }

                $inputData['product_combinations_id'] = $combinationId;
                $availableQuantity = $this->calculateQuantityByType($combinations->stockTrasactions);

                if ($inputData['sub_type'] == 3) { // Purchase Return
                    // Check if supplier id exist
                    if (! __isEmpty($supplierNumericId)) {
                        $supplierStockTransaction = $combinations->stockTrasactions->where('suppliers__id', $supplierNumericId);
                        $supplierReturnQty = $supplierStockTransaction->where('type', 1)->sum('quantity');
                        $supplierPurchaseQty = $supplierStockTransaction->where('type', 2)->sum('quantity');
                        $supplierAvailableQty = $supplierPurchaseQty - $supplierReturnQty;
                        $availableQuantity = ($supplierAvailableQty < $availableQuantity)
                            ? $supplierAvailableQty
                            : $availableQuantity;
                    } elseif (__isEmpty($supplierNumericId)) {
                        $supplierStockTransaction = $combinations->stockTrasactions->where('suppliers__id', null);
                        $availableQuantity = $supplierStockTransaction->where('type', 2)->sum('quantity');
                    }
                }

                $combinationPrice = $combinations->price;

                if ($inputData['sub_type'] == 2 or $inputData['sub_type'] == 7) {
                    $combinationPrice = $combinations->sale_price;
                }

                if ($inputData['sub_type'] == 7) { // Sale Return
                    $saleReturnQty = $combinations->stockTrasactions->where('sub_type', 7)->sum('quantity');
                    $availableQuantityInDB = $combinations->stockTrasactions->where('sub_type', 2)->sum('quantity');
                    $availableQuantityInDB = $availableQuantityInDB - $saleReturnQty;

                    $availableQuantity = $this->calculateQuantityByType($combinations->stockTrasactions);
                }

                // combinations price
                $inputData['total_price'] = $combinationPrice;

                // combinations price + addon price
                $inputData['total_amount'] = $combinationPrice * $inputData['quantity'];

                $inputData['type'] = ($inputData['sub_type'] == 1 // Purchased
                    or $inputData['sub_type'] == 5 // Moved In
                    or $inputData['sub_type'] == 7) // Sale Return
                    ? 2 // Credit // Deposite
                    : 1; // Debit

                // Check if out quantity is greater than available quantity
                if ($inputData['type'] == 1) { // Debit
                    if ($inputData['quantity'] > $availableQuantity) {
                        return $this->inventoryRepository->transactionResponse(2, null, __tr('Not enough products in inventory.'));
                    }
                } elseif ($inputData['type'] == 2) { // Credit
                    if ($inputData['sub_type'] == 7) { // Sale Return
                        if ($inputData['quantity'] > $availableQuantityInDB) {
                            return $this->inventoryRepository->transactionResponse(2, null, __tr('Not enough products in inventory.'));
                        }
                    }
                }

                // Check if stock transaction stored
                if ($stockTransaction = $this->inventoryRepository->storeStockTransaction($inputData)) {
                    $stockDetails = [
                        'quantity' => $stockTransaction->quantity,
                        'location_id' => $stockTransaction->locations__id,
                        'location_name' => $locationData->name,
                    ];

                    // Check if transaction product options are stored
                    return $this->inventoryRepository->transactionResponse(1, [
                        'stockDetails' => $stockDetails,
                    ], __tr('Inventory added successfully.'));
                }

                return $this->inventoryRepository->transactionResponse(2, null, __tr('Inventory not added.'));
            });

        return $this->engineReaction($reactionCode);
    }

    /**
     * Prepare product inventory details
     *
     * @param  string  $productId
     * @return eloquent collection object processUpdateInventory
     *---------------------------------------------------------------- */
    public function prepareProductInvetoryDetails($productId)
    {
        $myLocationIds = [];
        $myLocations = $this->locationRepository->fetchMyLocations(getUserAuthorityId());

        if (! __isEmpty($myLocations)) {
            $myLocationIds = $myLocations->pluck('_id')->toArray();
        }

        $products = $this->productRepository->fetchProductForSearchData($productId);

        // Check if product exist
        if (__isEmpty($products)) {
            return $this->inventoryRepository->transactionResponse(18, null, __tr('Product does not exist.'));
        }

        $inventoryData = [];

        foreach ($products['combinations'] as $combinationKey => $combination) {
            $options = [];
            foreach ($combination['combinationOptions'] as $optionKey => $option) {
                $options[] = [
                    'label_name' => $option->optionValue->productOptionLabel->name,
                    'value_name' => $option->optionValue->name,
                ];
            }
            $inventoryData[] = [
                'options' => $options,
            ];
        }

        return $this->engineReaction(1, [
            'invetoryData' => $inventoryData,
        ]);
    }

    /**
     * Prepare inventory transaction details
     *
     * @param  string  $productId
     * @param  string  $combinationId
     * @param  string  $locationId
     * @return eloquent collection object processUpdateInventory
     *---------------------------------------------------------------- */
    public function prepareInventoryTrasactionData($productId, $combinationId, $locationId)
    {
        $product = $this->productRepository->fetch($productId);

        // Check if product exist
        if (__isEmpty($product)) {
            return $this->inventoryRepository->transactionResponse(18, null, __tr('Product does not exist.'));
        }

        $transactionData = $combinations = $balanceStockData = [];

        $productTransactions = $this->productRepository->fetchTransactions($combinationId, $locationId);
        $balanceStock = 0;
        if (! __isEmpty($productTransactions['stockTrasactions'])) {
            foreach ($productTransactions['stockTrasactions'] as $key => $stockTransaction) {
                $quantity = round($stockTransaction->quantity);
                $stockLocationId = $stockTransaction->locations__id;

                if (! array_key_exists($stockLocationId, $balanceStockData)) {
                    $balanceStockData[$stockLocationId] = $quantity;
                } elseif (array_key_exists($stockLocationId, $balanceStockData)) {
                    if ($stockTransaction->type == 2) {
                        $balanceStockData[$stockLocationId] = $balanceStockData[$stockLocationId] + $quantity;
                    } elseif ($stockTransaction->type == 1) {
                        $balanceStockData[$stockLocationId] = $balanceStockData[$stockLocationId] - $quantity;
                    }
                }

                $transactionData[] = [
                    'quantity' => round($stockTransaction->quantity),
                    'forammted_created_at' => formatDateTime($stockTransaction->updated_at),
                    'formatted_type' => configItem('stock_transactions.sub_types', $stockTransaction->sub_type),
                    'supplier' => (! __isEmpty($stockTransaction->supplier))
                        ? $stockTransaction->supplier->name
                        : '',
                    'location' => $stockTransaction->location->name,
                    'type' => $stockTransaction->type,
                    'balance_stock' => $balanceStockData[$stockLocationId],
                ];
            }
        }

        if (! __isEmpty($productTransactions->combinationOptions)) {
            foreach ($productTransactions->combinationOptions as $key => $option) {
                $combinations[] = [
                    'label_name' => $option->optionValue->productOptionLabel->name,
                    'value_name' => $option->optionValue->name,
                ];
            }
        }

        return $this->engineReaction(1, [
            'transactionData' => $transactionData,
            'combinations' => $combinations,
        ]);
    }

    /**
     * Prepare product transaction details
     *
     * @param  string  $productId
     * @return eloquent collection object processUpdateInventory
     *---------------------------------------------------------------- */
    public function prepareProductTrasactionData($productId)
    {
        $myLocationIds = [];
        $myLocations = $this->locationRepository->fetchMyLocations(getUserAuthorityId());

        if (! __isEmpty($myLocations)) {
            $myLocationIds = $myLocations->pluck('_id')->toArray();
        }

        $products = $this->productRepository->fetchProductForTransaction($productId, $myLocationIds);

        // Check if product exist
        if (__isEmpty($products)) {
            return $this->inventoryRepository->transactionResponse(18, null, __tr('Product does not exist.'));
        }

        $transactionData = [];

        foreach ($products['combinations'] as $combinationKey => $combination) {
            $options = [];
            foreach ($combination['combinationOptions'] as $optionKey => $option) {
                $options[] = [
                    'label_name' => $option->optionValue->productOptionLabel->name,
                    'value_name' => $option->optionValue->name,
                ];
            }
            $stockTransactions = $balanceStockData = [];
            $balanceStock = 0;
            foreach ($combination['stockTrasactions'] as $trasactionKey => $trasaction) {
                $quantity = round($trasaction->quantity);
                $stockLocationId = $trasaction->locations__id;

                if (! array_key_exists($stockLocationId, $balanceStockData)) {
                    $balanceStockData[$stockLocationId] = $quantity;
                } elseif (array_key_exists($stockLocationId, $balanceStockData)) {
                    if ($trasaction->type == 2) {
                        $balanceStockData[$stockLocationId] = $balanceStockData[$stockLocationId] + $quantity;
                    } elseif ($trasaction->type == 1) {
                        $balanceStockData[$stockLocationId] = $balanceStockData[$stockLocationId] - $quantity;
                    }
                }

                $stockTransactions[] = [
                    'quantity' => round($trasaction->quantity),
                    'forammted_created_at' => formatDateTime($trasaction->created_at),
                    'formatted_type' => configItem('stock_transactions.sub_types', $trasaction->sub_type),
                    'supplier' => (! __isEmpty($trasaction->supplier))
                        ? $trasaction->supplier->name
                        : '',
                    'location' => $trasaction->location->name,
                    'type' => $trasaction->type,
                    'balance_stock' => $balanceStockData[$stockLocationId],
                ];
            }
            $transactionData[] = [
                'combinations' => $options,
                'stockTransactions' => $stockTransactions,
            ];
        }

        return $this->engineReaction(1, [
            'transactionData' => $transactionData,
        ]);
    }
}

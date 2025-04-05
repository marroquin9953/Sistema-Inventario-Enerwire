<?php
/*
* BillingEngine.php - Main component file
*
* This file is part of the Billing component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Billing;

use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\Billing\Interfaces\BillingEngineInterface;
use App\Yantrana\Components\Billing\Repositories\BillingRepository;
use App\Yantrana\Components\Customer\Repositories\CustomerRepository;
use App\Yantrana\Components\Inventory\InventoryEngine;
use App\Yantrana\Components\Inventory\Repositories\InventoryRepository;
use App\Yantrana\Components\Location\Repositories\LocationRepository;
use App\Yantrana\Components\Product\Repositories\ProductRepository;
use App\Yantrana\Components\Suppliers\Repositories\SuppliersRepository;
use App\Yantrana\Components\User\Repositories\UserRepository;
use PDF;

class BillingEngine extends BaseEngine implements BillingEngineInterface
{
    /**
     * @var  BillingRepository - Billing Repository
     */
    protected $billingRepository;

    /**
     * @var UserRepository - User Repository
     */
    protected $userRepository;

    /**
     * @var  CustomerRepository - Customer Repository
     */
    protected $customerRepository;

    /**
     * @var  LocationRepository - Location Repository
     */
    protected $locationRepository;

    /**
     * @var  ProductRepository - Product Repository
     */
    protected $productRepository;

    /**
     * @var  InventoryEngine - Inventory Engine
     */
    protected $inventoryEngine;

    /**
     * @var  InventoryRepository - Inventory Repository
     */
    protected $inventoryRepository;

    /**
     * @var  SuppliersRepository - Suppliers Repository
     */
    protected $suppliersRepository;

    /**
     * Constructor
     *
     * @param  BillingRepository  $billingRepository - Billing Repository
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(
        BillingRepository $billingRepository,
        UserRepository $userRepository,
        CustomerRepository $customerRepository,
        LocationRepository $locationRepository,
        ProductRepository $productRepository,
        InventoryEngine $inventoryEngine,
        InventoryRepository $inventoryRepository,
        SuppliersRepository $suppliersRepository
    ) {
        $this->billingRepository = $billingRepository;
        $this->userRepository = $userRepository;
        $this->customerRepository = $customerRepository;
        $this->locationRepository = $locationRepository;
        $this->productRepository = $productRepository;
        $this->inventoryEngine = $inventoryEngine;
        $this->inventoryRepository = $inventoryRepository;
        $this->suppliersRepository = $suppliersRepository;
    }

    /**
     * Billing datatable source
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareBillingDataTableSource()
    {
        $billingCollection = $this->billingRepository
            ->fetchBillingDataTableSource();
        $requireColumns = [
            '_id',
            '_uid',
            'txn_id',
            'bill_number',
            'total_amount' => function ($key) {
                return moneyFormat($key['total_amount'], true, false);
            },
            'status',
            'formatted_status' => function ($key) {
                return configItem('bill_statuses', $key['status']);
            },
            'bill_date' => function ($key) {
                return formatDate($key['bill_date']);
            },
            'customer_name',
            'view_details' => function ($key) {
                return canAccess('print_or_download_bill');
            },
            'edit_bill' => function ($key) {
                return canAccess('manage.billing.write.update_bill');
            },
            'can_delete' => function ($key) {
                return canAccess('manage.billing.write.delete');
            },
        ];

        return $this->dataTableResponse($billingCollection, $requireColumns);
    }

    /**
     * Prepare Add Support Data
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareAddSupportData()
    {
        $userDetails = $this->userRepository->fetchUserWithProfile(getUserID());

        $userData = [];

        // Check if user details exist
        if (! __isEmpty($userDetails)) {
            $userData = [
                'fullName' => $userDetails->first_name.' '.$userDetails->last_name,
                'address1' => (! __isEmpty($userDetails->profile))
                    ? $userDetails->profile->address_line_1 : '',
                'address2' => (! __isEmpty($userDetails->profile))
                    ? $userDetails->profile->address_line_2 : '',
                'country' => (! __isEmpty($userDetails->profile))
                    ? $userDetails->profile->country->name
                    : '',
            ];
        }

        $customerCollection = $this->customerRepository->fetchAll();
        $customerData = [];

        if (! __isEmpty($customerCollection)) {
            foreach ($customerCollection as $key => $customer) {
                $customerData[] = [
                    'id' => $customer->_id,
                    'name' => $customer->name,
                    'short_description' => $customer->short_description,
                    'country' => $customer->country_name,
                ];
            }
        }

        return $this->engineReaction(1, [
            'userData' => $userData,
            'customerData' => $customerData,
            'currencyCode' => getCurrency(),
            'currencySymbol' => getCurrencySymbol(),
        ]);
    }

    /**
     * Prepare Add Support Data
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareEditSupportData($billUid)
    {
        $myLocationIds = [];
        $myLocations = $this->locationRepository->fetchMyLocations(getUserAuthorityId());

        if (! __isEmpty($myLocations)) {
            $myLocationIds = $myLocations->pluck('_id')->toArray();
        }

        $billingData = $this->billingRepository->fetchBillingDataWithStockTransaction($billUid, $myLocationIds);

        // Check if billing data exist
        if (__isEmpty($billingData)) {
            return $this->engineReaction(18, null, __tr('Bill does not exist.'));
        }

        $userDetails = $this->userRepository->fetchUserWithProfile(getUserID());

        $userData = [];

        // Check if user details exist
        if (! __isEmpty($userDetails)) {
            $userData = [
                'fullName' => $userDetails->first_name.' '.$userDetails->last_name,
                'address1' => (! __isEmpty($userDetails->profile))
                    ? $userDetails->profile->address_line_1 : '',
                'address2' => (! __isEmpty($userDetails->profile))
                    ? $userDetails->profile->address_line_2 : '',
                'country' => (! __isEmpty($userDetails->profile))
                    ? $userDetails->profile->country->name
                    : '',
            ];
        }

        $customerCollection = $this->customerRepository->fetchAll();

        $customerData = [];

        if (! __isEmpty($customerCollection)) {
            foreach ($customerCollection as $key => $customer) {
                $customerData[] = [
                    'id' => $customer->_id,
                    'name' => $customer->name,
                    'short_description' => $customer->short_description,
                    'country' => $customer->country_name,
                ];
            }
        }

        $productIds = $lockedQuantities = [];
        if (! __isEmpty($billingData)) {
            foreach ($billingData->stockTransactions as $lockStockProductCombo) {
                if (! __isEmpty($lockStockProductCombo->productCombination)) {
                    $productIds[] = $lockStockProductCombo->productCombination->products__id;
                }
            }
        }

        if (! __isEmpty($productIds)) {
            $LockedQuantityCollection = $this->productRepository->fetchLockQuantityForProductCombination($productIds, $myLocationIds);

            if (! __isEmpty($LockedQuantityCollection)) {
                foreach ($LockedQuantityCollection as $LockedQuantity) {
                    $locationWiseStockTransactions = $LockedQuantity->stockTrasactions->groupBy('locations__id');
                    foreach ($locationWiseStockTransactions as $locationStockTransactions) {
                        $totalLockedQty = 0;
                        foreach ($locationStockTransactions as $locationStockTransaction) {
                            $totalLockedQty = $totalLockedQty + $locationStockTransaction->quantity;
                            $locationId = $locationStockTransaction->locations__id;
                            $combinationId = $locationStockTransaction->product_combinations__id;
                        }
                        $lockedQuantities[$locationId.'_'.$combinationId] = $totalLockedQty;
                    }
                }
            }
        }

        $productCombinations = [];
        $combination = $subTotalArray = [];
        $totalProductTax = $totalUnitPrice = [];
        if (! __isEmpty($billingData)) {
            foreach ($billingData->stockTransactions as $productCombo) {
                $combinations = [];

                if (! __isEmpty($productCombo->productCombination->combinationOptions)) {
                    foreach ($productCombo->productCombination->combinationOptions as $comboOption) {
                        $combinations[] = [
                            'labelName' => $comboOption->optionValue->productOptionLabel->name,
                            'valueName' => $comboOption->optionValue->name,
                        ];
                    }
                }

                $taxes = [];
                // check if product exists
                if (! __isEmpty($productCombo->productCombination->product)) {
                    $product = $productCombo->productCombination->product;

                    // check if tax preset  exists
                    if (! __isEmpty($product->taxPreset)) {
                        // check if taxes exists
                        if (! __isEmpty($product->taxPreset->taxes)) {
                            foreach ($product->taxPreset->taxes as $tax) {
                                if ($tax->status == 1) {
                                    $taxes[] = [
                                        'id' => $tax->_id,
                                        'title' => $tax->title,
                                        'tax_amount' => $tax->tax_amount,
                                        'type' => $tax->type,
                                        'tax_preset_id' => $tax->tax_presets__id,
                                    ];
                                }
                            }
                        }
                    }
                }

                $locationWiseTransactions = $productCombo->productCombination->stockTrasactions->groupBy('locations__id');

                $availableQuantity = 0;
                // for transactions according to locations and their quantity counts
                $locTransactions = [];
                if (! __isEmpty($locationWiseTransactions)) {
                    foreach ($locationWiseTransactions as $key => $transaction) {
                        if ($productCombo->locations__id == $key) {
                            $availableQuantity = $this->inventoryEngine->calculateQuantityByType($transaction);
                        }
                    }
                }

                $isQuantityExist = ($availableQuantity == 0) ? false : true;
                $showUpdateInventoryButton = false;
                if (! $isQuantityExist) {
                    if (canAccess('manage_assigned_location_inventory')) {
                        $showUpdateInventoryButton = true;
                    }
                }

                $lockQuantity = array_get($lockedQuantities, $productCombo->locations__id.'_'.$productCombo->product_combinations__id);
                $availableQty = (int) $availableQuantity - $lockQuantity;
                $productComboQty = (int) $productCombo->quantity;

                $combination = [
                    'id' => $productCombo->product_combinations__id,
                    'stock_transaction_id' => $productCombo->_id,
                    'productId' => (string) $productCombo->productCombination->products__id,
                    'name' => $productCombo->productCombination->product->name,
                    'product_status' => $productCombo->productCombination->product->status,
                    'combinationTitle' => $productCombo->productCombination->title,
                    'combinationStatus' => $productCombo->productCombination->status,
                    'salePrice' => $productCombo->productCombination->sale_price,
                    'formattedSalePrice' => moneyFormat($productCombo->productCombination->sale_price, false, true),
                    'combinations' => $combinations,
                    'comboSKU' => $productCombo->productCombination->product_id,
                    'quantity' => (int) $availableQuantity,
                    'lockQuantity' => $lockQuantity - $productComboQty,
                    'isQuantityExist' => $isQuantityExist,
                    'showUpdateInventoryButton' => $showUpdateInventoryButton,
                    'tax_presets__id' => $productCombo->productCombination->product->tax_presets__id,
                    'availableQty' => $availableQty + $productComboQty,
                ];

                $jsonData = $productCombo->__data;

                $productCombinations[] = [
                    'combination' => $combination,
                    'taxes' => $taxes,
                    'stock_transaction_uid' => $productCombo->_uid,
                    'unit_price' => $productCombo->total_price,
                    'formattedUnitPrice' => moneyFormat($productCombo->total_price, $productCombo->currency_code),
                    'quantity' => (int) $productCombo->quantity,
                    'actualQuantity' => (int) $productCombo->quantity,
                    'price' => $productCombo->total_amount,
                    'formattedPrice' => moneyFormat($productCombo->total_amount, $productCombo->currency_code),
                    'showDetails' => true,
                    'isForUpdate' => true,
                    'location_id' => isset($productCombo->location->_id) ? $productCombo->location->_id : null,
                    'location_name' => isset($productCombo->location->name) ? $productCombo->location->name : null,
                    'tax_presets__id' => $combination['tax_presets__id'],
                    'prev_added' => true,
                ];
            }
        }

        $customerId = $billingData->customers__id;
        $customerInfo = [];
        $customerDetails = $this->customerRepository->fetch($customerId);

        if (! __isEmpty($customerDetails)) {
            $customerInfo = [
                'name' => $customerDetails->name,
                'short_description' => $customerDetails->short_description,
                'country_name' => $customerDetails->country_name,
            ];
        }

        $jsonData = $billingData->__data;
        $taxDetails = isset($jsonData['tax_details']) ? $jsonData['tax_details'] : [];
        $discountDetails = isset($jsonData['discount_details']) ? $jsonData['discount_details'] : [];

        $updateData = [
            'customer' => $customerId,
            'bill_date' => $billingData->bill_date,
            'due_date' => $billingData->due_date,
            'bill_number' => $billingData->bill_number,
            'txn_id' => $billingData->txn_id,
            'productCombinations' => $productCombinations,
            'is_add_tax' => isset($taxDetails['is_add_tax']) ? $taxDetails['is_add_tax'] : false,
            'tax' => isset($taxDetails['tax']) ? $taxDetails['tax'] : 0,
            'tax_type' => isset($taxDetails['type']) ? $taxDetails['type'] : null,
            'tax_description' => isset($taxDetails['description']) ? $taxDetails['description'] : null,
            'is_add_discount' => isset($discountDetails['is_add_discount']) ? $discountDetails['is_add_discount'] : false,
            'discount' => isset($discountDetails['discount']) ? $discountDetails['discount'] : 0,
            'discount_type' => isset($discountDetails['type']) ? $discountDetails['type'] : null,
            'discount_description' => isset($discountDetails['description']) ? $discountDetails['description'] : null,
            'formatted_status' => configItem('bill_statuses', $billingData->status),
            'customerInfo' => $customerInfo,
        ];

        return $this->engineReaction(1, [
            'userData' => $userData,
            'customerData' => $customerData,
            'updateData' => $updateData,
            'currencyCode' => getCurrency(),
            'currencySymbol' => getCurrencySymbol(),
        ]);
    }

    /**
     * Prepare Add Support Data
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareBillDetails($billUid)
    {
        $myLocationIds = [];
        $myLocations = $this->locationRepository->fetchMyLocations(getUserAuthorityId());

        if (! __isEmpty($myLocations)) {
            $myLocationIds = $myLocations->pluck('_id')->toArray();
        }

        $billingData = $this->billingRepository->fetchWithStockTransaction($billUid, $myLocationIds);

        // Check if billing data exist
        if (__isEmpty($billingData)) {
            return $this->engineReaction(18, null, __tr('Bill does not exist.'));
        }

        $userDetails = $this->userRepository->fetchUserWithProfile(getUserID());

        $userData = [];

        // Check if user details exist
        if (! __isEmpty($userDetails)) {
            $userData = [
                'fullName' => $userDetails->first_name.' '.$userDetails->last_name,
                'address1' => (! __isEmpty($userDetails->profile))
                    ? $userDetails->profile->address_line_1 : '',
                'address2' => (! __isEmpty($userDetails->profile))
                    ? $userDetails->profile->address_line_2 : '',
                'country' => (! __isEmpty($userDetails->profile))
                    ? $userDetails->profile->country->name
                    : '',
            ];
        }

        $customerCollection = $this->customerRepository->fetchAll();

        $customerData = [];

        if (! __isEmpty($customerCollection)) {
            foreach ($customerCollection as $key => $customer) {
                $customerData[] = [
                    'id' => $customer->_id,
                    'name' => $customer->name,
                    'short_description' => $customer->short_description,
                    'country' => $customer->country_name,
                ];
            }
        }
        $productCombinations = [];
        $combination = $subTotalArray = [];
        $totalProductTax = $totalUnitPrice = $totalProductPrice = [];
        if (! __isEmpty($billingData)) {
            foreach ($billingData->stockTransactions as $productCombo) {
                $combinations = [];

                if (! __isEmpty($productCombo->productCombination->combinationOptions)) {
                    foreach ($productCombo->productCombination->combinationOptions as $comboOption) {
                        $combinations[] = [
                            'labelName' => $comboOption->optionValue->productOptionLabel->name,
                            'valueName' => $comboOption->optionValue->name,
                        ];
                    }
                }

                $taxes = [];
                // check if product exists
                if (! __isEmpty($productCombo->productCombination->product)) {
                    $product = $productCombo->productCombination->product;

                    // check if tax preset  exists
                    if (! __isEmpty($product->taxPreset)) {
                        // check if taxes exists
                        if (! __isEmpty($product->taxPreset->taxes)) {
                            foreach ($product->taxPreset->taxes as $tax) {
                                if ($tax->status == 1) {
                                    $taxes[] = [
                                        'id' => $tax->_id,
                                        'title' => $tax->title,
                                        'tax_amount' => $tax->tax_amount,
                                        'type' => $tax->type,
                                        'tax_preset_id' => $tax->tax_presets__id,
                                    ];
                                }
                            }
                        }
                    }
                }

                $locationWiseTransactions = $productCombo->productCombination->stockTrasactions->groupBy('locations__id');

                $availableQuantity = 0;
                // for transactions according to locations and their quantity counts
                $locTransactions = [];
                if (! __isEmpty($locationWiseTransactions)) {
                    foreach ($locationWiseTransactions as $key => $transaction) {
                        if ($productCombo->locations__id == $key) {
                            $availableQuantity = $this->inventoryEngine->calculateQuantityByType($transaction);
                        }
                    }
                }

                $isQuantityExist = ($availableQuantity == 0) ? false : true;
                $showUpdateInventoryButton = false;
                if (! $isQuantityExist) {
                    if (canAccess('manage_assigned_location_inventory')) {
                        $showUpdateInventoryButton = true;
                    }
                }

                $combination = [
                    'id' => $productCombo->product_combinations__id,
                    'productId' => (string) $productCombo->productCombination->products__id,
                    'name' => $productCombo->productCombination->product->name,
                    'combinationTitle' => $productCombo->productCombination->title,
                    'salePrice' => $productCombo->productCombination->sale_price,
                    'formattedSalePrice' => moneyFormat($productCombo->productCombination->sale_price, false, true),
                    'combinations' => $combinations,
                    'comboSKU' => $productCombo->productCombination->product_id,
                    'quantity' => $availableQuantity,
                    'isQuantityExist' => $isQuantityExist,
                    'showUpdateInventoryButton' => $showUpdateInventoryButton,
                    'tax_presets__id' => $productCombo->productCombination->product->tax_presets__id,
                ];

                $subTotalArray[] = $productCombo->total_amount;
                $jsonData = $productCombo->__data;
                $taxDetails = [];
                $taxApplied = null;
                $taxSubTotal = [];
                $taxAmount = 0;
                $productUnitPrice = (float) $productCombo->total_price;
                $productPrice = ((float) $productCombo->total_price) * (int) $productCombo->quantity;

                $formattedTaxDetails = [];

                if ($billingData->status == 2) {
                    $addedTaxes = $jsonData['tax_details'];
                } else {
                    $addedTaxes = $taxes;
                }

                if (! __isEmpty($addedTaxes)) {
                    foreach ($addedTaxes as $key => $tax_d) {
                        if ($tax_d['type'] == 1) {
                            $taxAmount = $tax_d['tax_amount'];
                            $formattedTax = moneyFormat($taxAmount, $productCombo->currency_code);
                            $formattedTaxDetails[] = [
                                'amount' => $formattedTax,
                                'title' => $tax_d['title'],
                            ];
                        } elseif ($tax_d['type'] == 2) {
                            $taxAmount = $tax_d['tax_amount'];
                            $taxAmountStr = '('.$taxAmount.'%)';
                            $taxAmount = ($productPrice * ($taxAmount / 100));
                            $formattedTax = moneyFormat($taxAmount, $productCombo->currency_code);
                            $formattedTaxDetails[] = [
                                'amount' => $formattedTax,
                                'title' => $tax_d['title'].$taxAmountStr,
                            ];
                        }

                        $taxSubTotal[] = $taxAmount;
                    }
                }

                $productTaxSubTotal = array_sum($taxSubTotal);
                $formattedTaxSubTotal = moneyFormat(array_sum($taxSubTotal), $productCombo->currency_code);

                $totalProductTax[] = $productTaxSubTotal;
                $totalProductPrice[] = $productPrice;

                $productCombinations[] = [
                    'combination' => $combination,
                    'taxes' => $addedTaxes,
                    'unit_price' => $productCombo->total_price,
                    'formattedUnitPrice' => moneyFormat($productCombo->total_price, $productCombo->currency_code),
                    'quantity' => (int) $productCombo->quantity,
                    'price' => $productCombo->total_amount,
                    'formattedPrice' => moneyFormat($productCombo->total_amount, $productCombo->currency_code),
                    'showDetails' => true,
                    'isForUpdate' => true,
                    'location_id' => isset($productCombo->location->_id) ? $productCombo->location->_id : null,
                    'location_name' => isset($productCombo->location->name) ? $productCombo->location->name : null,
                    'tax_presets__id' => $combination['tax_presets__id'],
                    'tax_details' => $taxDetails,
                    'formattedTaxDetails' => $formattedTaxDetails,
                    'prev_added' => true,
                ];
            }
        }

        $customerId = $billingData->customers__id;
        $customerInfo = [];
        $customerDetails = $this->customerRepository->fetch($customerId);

        if (! __isEmpty($customerDetails)) {
            $customerInfo = [
                'name' => $customerDetails->name,
                'short_description' => $customerDetails->short_description,
                'country_name' => $customerDetails->country_name,
            ];
        }

        $jsonData = $billingData->__data;
        $subTotal = array_sum($totalProductPrice);
        // $totalAmount = $billingData->total_amount;
        $formattedSubTotal = isset($productCombo->currency_code)
            ? moneyFormat($subTotal, $productCombo->currency_code) : 0;

        $taxDetails = isset($jsonData['tax_details']) ? $jsonData['tax_details'] : [];
        $discountDetails = isset($jsonData['discount_details']) ? $jsonData['discount_details'] : [];

        $customTax = 0;
        if (! __isEmpty($taxDetails)) {
            if ($taxDetails['type'] == 2) {
                $customTax = $taxDetails['tax'];
                $formattedCustomTax = moneyFormat($customTax, $productCombo->currency_code);
            } elseif ($taxDetails['type'] == 1) {
                $customTax = $taxDetails['tax'];
                $taxAmountStr = '('.$customTax.'%)';
                $customTax = ($subTotal * ($customTax / 100));
                $formattedCustomTax = moneyFormat($customTax, $productCombo->currency_code);
            }
        }

        $discount = 0;
        if (! __isEmpty($discountDetails)) {
            if ($discountDetails['type'] == 2) {
                $discount = $discountDetails['discount'];
                $formattedDiscount = moneyFormat($discount, $productCombo->currency_code);
            } elseif ($discountDetails['type'] == 1) {
                $discount = $discountDetails['discount'];
                $taxAmountStr = '('.$discount.'%)';
                $discount = ($subTotal * ($discount / 100));
                $formattedDiscount = moneyFormat($discount, $productCombo->currency_code);
            }
        }

        $totalAmount = ((array_sum($totalProductTax) + array_sum($totalProductPrice) + $customTax) - $discount);

        $formattedTotalAmount = isset($productCombo->currency_code)
            ? moneyFormat($totalAmount, $productCombo->currency_code) : 0;

        $updateData = [
            'customer' => $customerId,
            'bill_date' => $billingData->bill_date,
            'bill_status' => $billingData->status,
            'due_date' => $billingData->due_date,
            'bill_number' => $billingData->bill_number,
            'txn_id' => $billingData->txn_id,
            'productCombinations' => $productCombinations,
            'is_add_tax' => isset($taxDetails['is_add_tax']) ? $taxDetails['is_add_tax'] : false,
            'tax' => isset($taxDetails['tax']) ? $taxDetails['tax'] : 0,
            'tax_type' => isset($taxDetails['type']) ? $taxDetails['type'] : null,
            'tax_description' => isset($taxDetails['description']) ? $taxDetails['description'] : null,
            'is_add_discount' => isset($discountDetails['is_add_discount']) ? $discountDetails['is_add_discount'] : false,
            'discount' => isset($discountDetails['discount']) ? $discountDetails['discount'] : 0,
            'discount_type' => isset($discountDetails['type']) ? $discountDetails['type'] : null,
            'discount_description' => isset($discountDetails['description']) ? $discountDetails['description'] : null,
            'formatted_status' => configItem('bill_statuses', $billingData->status),
            'customerInfo' => $customerInfo,
            'subTotal' => $subTotal,
            'formattedSubTotal' => $formattedSubTotal,
            'totalAmount' => $totalAmount,
            'formattedTotalAmount' => $formattedTotalAmount,
            'calculatedTotalProductTax' => moneyFormat(array_sum($totalProductTax), $productCombo->currency_code),
            'calculatedTotalUnitPrice' => moneyFormat(array_sum($totalProductPrice), $productCombo->currency_code),
            'formattedCustomTax' => $formattedCustomTax,
            'formattedDiscount' => $formattedDiscount,
        ];

        return $this->engineReaction(1, [
            'userData' => $userData,
            'customerData' => $customerData,
            'updateData' => $updateData,
            'currencyCode' => getCurrency(),
            'currencySymbol' => getCurrencySymbol(),
        ]);
    }

    /**
     * Prepare Search Combination Data
     *
     * @param  string  $searchTerm
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareSearchCombinationData($searchTerm)
    {
        $productIds = $this->productRepository->searchProductsForBilling($searchTerm);

        $productData = [];

        $myLocationIds = [];
        $myLocations = $this->locationRepository->fetchMyLocations(getUserAuthorityId());

        if (! __isEmpty($myLocations)) {
            $myLocationIds = $myLocations->pluck('_id')->toArray();
        }

        if (! __isEmpty($productIds)) {
            $productCombinations = $this->productRepository->fetchCombinationsByProductsIds($productIds, $myLocationIds);

            $LockedQuantityCollection = $this->productRepository->fetchLockQuantityForProductCombination($productIds, $myLocationIds);

            $lockedQuantities = [];
            if (! __isEmpty($LockedQuantityCollection)) {
                foreach ($LockedQuantityCollection as $key => $LockedQuantity) {
                    $totalLockedQty = 0;
                    foreach ($LockedQuantity->stockTrasactions as $LockQtyStockTrans) {
                        $totalLockedQty = $totalLockedQty + $LockQtyStockTrans->quantity;
                    }
                    $lockedQuantities[$LockedQuantity->_id] = $totalLockedQty;
                }
            }

            if (! __isEmpty($productCombinations)) {
                foreach ($productCombinations as $key => $productCombo) {
                    $taxes = [];
                    if (! __isEmpty($productCombo->taxPreset)) {
                        if (! __isEmpty($productCombo->taxPreset->taxes)) {
                            foreach ($productCombo->taxPreset->taxes as $tax) {
                                if ($tax->status == 1) {
                                    $taxes[] = [
                                        'id' => $tax->_id,
                                        'title' => $tax->title,
                                        'tax_amount' => $tax->tax_amount,
                                        'type' => $tax->type,
                                        'tax_preset_id' => $tax->tax_presets__id,
                                    ];
                                }
                            }
                        }
                    }

                    $combinations = [];
                    if (! __isEmpty($productCombo->combinationOptions)) {
                        foreach ($productCombo->combinationOptions as $comboOption) {
                            $combinations[] = [
                                'labelName' => $comboOption->optionValue->productOptionLabel->name,
                                'valueName' => $comboOption->optionValue->name,
                            ];
                        }
                    }

                    $message = '';
                    $quantity = $this->inventoryEngine->calculateQuantityByType($productCombo->stockTrasactions);
                    $locationWiseTransactions = $productCombo->stockTrasactions->groupBy('locations__id');

                    // for  transactions according to locations and their quantity counts
                    $locTransactions = [];
                    $locationName = '';
                    if (! __isEmpty($locationWiseTransactions)) {
                        foreach ($locationWiseTransactions as $key => $transaction) {
                            $quan = $this->inventoryEngine->calculateQuantityByType($transaction);
                            if ($quan >= 0) {
                                $locTransactions[] = $quan;
                            }
                        }
                    }

                    $locationCount = $locationWiseTransactions->count();
                    $locationID = null;

                    if ($locationCount == 1) {
                        $transact = $locationWiseTransactions->first();
                        $firstTransaction = $transact->first();

                        if (isset($firstTransaction->location->name)) {
                            $locationName = $firstTransaction->location->name;
                        }

                        $locationID = $firstTransaction->locations__id;
                    }

                    $chooseInventryByLocation = false;
                    if (count($locTransactions) > 1) {
                        $chooseInventryByLocation = true;
                    }

                    $isQuantityExist = ($quantity == 0) ? false : true;
                    $showUpdateInventoryButton = false;
                    if (! $isQuantityExist) {
                        if (canAccess('manage_assigned_location_inventory')) {
                            $showUpdateInventoryButton = true;
                            $message = __tr('Not enough quantity in inventory, please update inventory.');
                        } else {
                            $message = __tr('Not enough quantity in inventory, please contact administrator.');
                        }
                    }

                    $lockedQty = array_get($lockedQuantities, $productCombo->_id);
                    $productData[] = [
                        'id' => $productCombo->_id,
                        'productId' => (string) $productCombo->products__id,
                        'name' => $productCombo->product_name,
                        'combinationTitle' => $productCombo->title,
                        'salePrice' => $productCombo->sale_price,
                        'formattedSalePrice' => moneyFormat($productCombo->sale_price, false, true),
                        'combinations' => $combinations,
                        'taxes' => $taxes,
                        'comboSKU' => $productCombo->product_id,
                        'tax_presets__id' => $productCombo->tax_presets__id,
                        'barcode' => $productCombo->barcode,
                        'quantity' => $quantity,
                        'location_id' => $locationID,
                        'location_name' => $locationName,
                        'isQuantityExist' => $isQuantityExist,
                        'message' => $message,
                        'showUpdateInventoryButton' => $showUpdateInventoryButton,
                        'chooseInventryByLocation' => $chooseInventryByLocation,
                        'lockQuantity' => $lockedQty,
                        'availableQty' => $quantity - $lockedQty,
                    ];
                }
            }
        }

        return $this->engineReaction(1, [
            'productData' => $productData,
        ]);
    }

    /**
     * Prepare Search Combination Data
     *
     * @param  string  $searchTerm
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareLocationWiseCombination($combinationId, $productId)
    {
        $myLocationIds = [];
        $myLocations = $this->locationRepository->fetchMyLocations(getUserAuthorityId());

        if (! __isEmpty($myLocations)) {
            $myLocationIds = $myLocations->pluck('_id')->toArray();
        }

        $productId = (int) $productId;
        $productCombinations = $this->productRepository->fetchCombinationsByProductsIds([$productId], $myLocationIds);

        $LockedQuantityCollection = $this->productRepository->fetchLockQuantityForProductCombination([$productId], $myLocationIds);
        $lockedQuantities = [];

        if (! __isEmpty($LockedQuantityCollection)) {
            foreach ($LockedQuantityCollection as $LockedQuantity) {
                $lockStockTransactions = $LockedQuantity->stockTrasactions->groupBy('locations__id');
                foreach ($lockStockTransactions as $lockStockTrans) {
                    $totalLockedQty = 0;
                    $locationId = null;
                    foreach ($lockStockTrans as $lockStockTran) {
                        $totalLockedQty = $totalLockedQty + $lockStockTran->quantity;
                        $locationId = $lockStockTran->locations__id;
                    }
                    $lockedQuantities[$locationId] = $totalLockedQty;
                }
            }
        }

        $productData = [];
        if (! __isEmpty($productCombinations)) {
            foreach ($productCombinations as $key => $productCombo) {
                if ($combinationId == $productCombo->_id) {
                    $combinations = [];

                    if (! __isEmpty($productCombo->combinationOptions)) {
                        foreach ($productCombo->combinationOptions as $comboOption) {
                            $combinations[] = [
                                'labelName' => $comboOption->optionValue->productOptionLabel->name,
                                'valueName' => $comboOption->optionValue->name,
                            ];
                        }
                    }

                    $stockTrasactions = $productCombo->stockTrasactions->groupBy('locations__id');
                    $stockByLocations = [];

                    if (! __isEmpty($stockTrasactions)) {
                        foreach ($stockTrasactions as $key => $stock) {
                            $location = [];
                            foreach ($stock as $key2 => $tran) {
                                if (($key2 == 0) and ! __isEmpty($tran->location)) {
                                    if (
                                        $tran->location->status == 1
                                        or $tran->location->status == 3
                                    ) {
                                        $location = [
                                            'id' => $tran->location->_id,
                                            'name' => $tran->location->name,
                                        ];
                                    }
                                }
                            }

                            $quantity = $this->inventoryEngine->calculateQuantityByType($stock);

                            $lockQuantity = array_get($lockedQuantities, $location['id'], 0);
                            if (! __isEmpty($location)) {
                                $stockByLocations[] = [
                                    'quantity' => $quantity,
                                    'location_id' => $location['id'],
                                    'location' => $location['name'],
                                    'lockQuantity' => $lockQuantity,
                                    'availableQty' => $quantity - $lockQuantity,
                                ];
                            }
                        }
                    }

                    $productData = [
                        'id' => $productCombo->_id,
                        'productId' => $productCombo->products__id,
                        'name' => $productCombo->product_name,
                        'combinationTitle' => $productCombo->title,
                        'salePrice' => $productCombo->sale_price,
                        'formattedSalePrice' => moneyFormat($productCombo->sale_price, false, true),
                        'combinations' => $combinations,
                        'comboSKU' => $productCombo->product_id,
                        'barcode' => $productCombo->barcode,
                        'quantity' => $quantity,
                        'stockByLocations' => $stockByLocations,
                    ];
                }
            }
        }

        return $this->engineReaction(1, [
            'productData' => $productData,
        ]);
    }

    /**
     * Process Store Product Billing
     *
     * @param  array  $inputData
     * @return  array
     *---------------------------------------------------------------- */
    public function processStoreProductBill($inputData)
    {
        $reactionCode = $this->billingRepository
            ->processTransaction(function () use ($inputData) {
                if (bcdiv($inputData['totalAmount'], 1, 2) < 1) {
                    return $this->billingRepository->transactionResponse(3, null, __tr('Please ensure that the total amount is greater than or equal to zero.'));
                }

                $storeData = [
                    'status' => $inputData['type'],
                    'customers__id' => $inputData['customer'],
                    'user_authorities__id' => getUserAuthorityId(),
                    'total_amount' => $inputData['totalAmount'],
                    'txn_id' => array_get($inputData, 'txn_id'),
                    'short_description' => '',
                    'bill_number' => array_get($inputData, 'bill_number'),
                    'bill_date' => array_get($inputData, 'bill_date'),
                    'due_date' => array_get($inputData, 'due_date'),
                    '__data' => [
                        'tax_details' => [
                            'amount' => array_get($inputData, 'tax_amount'),
                            'type' => array_get($inputData, 'tax_type'),
                            'description' => array_get($inputData, 'tax_description'),
                            'tax' => array_get($inputData, 'tax'),
                            'is_add_tax' => array_get($inputData, 'is_add_tax'),
                        ],
                        'discount_details' => [
                            'amount' => array_get($inputData, 'discount_amount'),
                            'type' => array_get($inputData, 'discount_type'),
                            'description' => array_get($inputData, 'discount_description'),
                            'discount' => array_get($inputData, 'discount'),
                            'is_add_discount' => array_get($inputData, 'is_add_discount'),
                        ],
                        'customer_details' => $inputData['customerDetails'],
                    ],
                ];

                $stockTrasactionData = [];
                $selectedProductIds = [];

                if ($billId = $this->billingRepository->storeBill($storeData)) {
                    if (! __isEmpty($inputData['productCombinations'])) {
                        foreach ($inputData['productCombinations'] as $key => $combination) {
                            $selectedProductIds[] = $combination['combination']['productId'];

                            $stockTrasactionData[] = [
                                'status' => 1,
                                'quantity' => $combination['quantity'],
                                'type' => 1,
                                'sub_type' => 2,
                                'user_authorities__id' => getUserAuthorityId(),
                                'total_price' => $combination['unit_price'],
                                'total_amount' => $combination['price'],
                                'product_combinations__id' => $combination['combination']['id'],
                                'currency_code' => getCurrency(),
                                'bills__id' => $billId,
                                'customers__id' => $inputData['customer'],
                                'locations__id' => isset($combination['location_id']) ? $combination['location_id'] : null,
                                'tax_presets__id' => isset($combination['tax_presets__id']) ? $combination['tax_presets__id'] : null,
                                '__data' => [
                                    'tax_details' => ! __isEmpty($combination['tax_details']) ? $combination['tax_details'] : [],
                                ],
                            ];
                        }

                        // check if selected products are inactive
                        $inactiveProducts = $this->productRepository->fetchInActiveProducts($selectedProductIds);

                        if (! __isEmpty($inactiveProducts)) {
                            return $this->billingRepository->transactionResponse(2, [
                                'inactiveProducts' => $inactiveProducts,
                            ], __tr('Bill cannot be updated as some of products are inactive. Please remove inactive products'));
                        }

                        if ($this->inventoryRepository->storeStockTransactions($stockTrasactionData)) {
                            return $this->billingRepository->transactionResponse(1, null, __tr('Bill Added Successfully.'));
                        }
                    }
                }

                return $this->billingRepository->transactionResponse(2, null, __tr('Bill not added.'));
            });

        return $this->engineReaction($reactionCode);
    }

    /**
     * Process Update Product Billing
     *
     * @param  mixed  $billId
     * @param  array  $inputData
     * @return  array
     *---------------------------------------------------------------- */
    public function processUpdateProductBill($billId, $inputData)
    {
        $reactionCode = $this->billingRepository
            ->processTransaction(function () use ($billId, $inputData) {
                $billDetails = $this->billingRepository->fetch($billId);

                if (__isEmpty($billDetails)) {
                    return $this->engineReaction(18, null, __tr('Bill does not exist'));
                }

                $billTransactions = $this->billingRepository->fetchWithStockTransaction($billId, []);

                $billTransactionsIds = $billTransactions->stockTransactions->pluck('_id')->toArray();

                if (bcdiv($inputData['totalAmount'], 1, 2) < 1) {
                    return $this->billingRepository->transactionResponse(3, null, __tr('Please ensure that the total amount is greater than or equal to zero.'));
                }

                $storeData = [
                    'status' => $inputData['type'],
                    'total_amount' => $inputData['totalAmount'],
                    'txn_id' => array_get($inputData, 'txn_id'),
                    'short_description' => '',
                    'bill_number' => array_get($inputData, 'bill_number'),
                    'bill_date' => array_get($inputData, 'bill_date'),
                    'due_date' => array_get($inputData, 'due_date'),
                    '__data' => [
                        'tax_details' => [
                            'amount' => array_get($inputData, 'tax_amount'),
                            'type' => array_get($inputData, 'tax_type'),
                            'description' => array_get($inputData, 'tax_description'),
                            'tax' => array_get($inputData, 'tax'),
                            'is_add_tax' => array_get($inputData, 'is_add_tax'),
                        ],
                        'discount_details' => [
                            'amount' => array_get($inputData, 'discount_amount'),
                            'type' => array_get($inputData, 'discount_type'),
                            'description' => array_get($inputData, 'discount_description'),
                            'discount' => array_get($inputData, 'discount'),
                            'is_add_discount' => array_get($inputData, 'is_add_discount'),
                        ],
                        'customer_details' => $inputData['customerDetails'],
                    ],
                ];

                $storckTrasactionStoreData = $storckTrasactionUpdateData = $stockTransactionsDeleteData = [];
                $isUpdated = $stockAdded = $stockUpdated = $stockDeleted = false;
                if ($this->billingRepository->updateBill($billDetails, $storeData)) {
                    $isUpdated = true;
                }

                $stockTrasactionsUpdateIds = [];
                $selectedProductIds = [];
                $productCombinationIds = [];

                if (! __isEmpty($inputData['productCombinations'])) {
                    foreach ($inputData['productCombinations'] as $key => $combination) {
                        $selectedProductIds[] = $combination['combination']['productId'];
                        $productCombinationIds[] = $combination['combination']['id'];

                        if ($combination['isForUpdate']) {
                            $taxDetails = [];

                            if (
                                $combination['combination']['product_status'] == 3
                                or $combination['combination']['combinationStatus'] == 3
                            ) {
                                return $this->billingRepository->transactionResponse(3, null, __tr('highlighted product / combination may be deleted, please remove that product to continue.'));
                            }

                            if (isset($combination['tax_details']) and ! __isEmpty($combination['tax_details'])) {
                                $taxDetails = $combination['tax_details'];
                            }

                            $stockTrasactionsUpdateIds[] = $combination['combination']['stock_transaction_id'];

                            $storckTrasactionUpdateData[] = [
                                '_id' => $combination['combination']['stock_transaction_id'],
                                'status' => 1,
                                'quantity' => $combination['quantity'],
                                'total_price' => $combination['unit_price'],
                                'total_amount' => $combination['price'],
                                'locations__id' => isset($combination['location_id']) ? $combination['location_id'] : null,
                                'tax_presets__id' => isset($combination['tax_presets__id']) ? $combination['tax_presets__id'] : null,
                                '__data' => [
                                    'tax_details' => $taxDetails,
                                ],
                            ];
                        } else {
                            $taxDetails = [];

                            if (isset($combination['tax_details']) and ! __isEmpty($combination['tax_details'])) {
                                $taxDetails = $combination['tax_details'];
                            }

                            $balanceQuantity = $combination['combination']['quantity'] - $combination['combination']['lockQuantity'];
                            $balanceQuantity = $balanceQuantity - $combination['quantity'];

                            if ($balanceQuantity < 0) {
                                return $this->billingRepository->transactionResponse(3, null, __tr('Not enough products in inventory.'));
                            }

                            $storckTrasactionStoreData[] = [
                                'status' => 1,
                                'quantity' => $combination['quantity'],
                                'type' => 1,
                                'sub_type' => 2,
                                'user_authorities__id' => getUserAuthorityId(),
                                'total_price' => $combination['unit_price'],
                                'total_amount' => $combination['price'],
                                'product_combinations__id' => $combination['combination']['id'],
                                'currency_code' => getCurrency(),
                                'bills__id' => $billDetails->_id,
                                'customers__id' => $inputData['customer'],
                                'locations__id' => isset($combination['location_id']) ? $combination['location_id'] : null,
                                'tax_presets__id' => isset($combination['tax_presets__id']) ? $combination['tax_presets__id'] : null,
                                '__data' => [
                                    'tax_details' => $taxDetails,
                                ],
                            ];
                        }
                    }

                    $deletedCombinations = $this->productRepository->fetchDeletedProductCombination($productCombinationIds);

                    if (! __isEmpty($deletedCombinations)) {
                        return $this->billingRepository->transactionResponse(2, null, __tr('Bill cannot be updated as some of the products combinations are deleted. Please remove those products.'));
                    }

                    // check if selected products are inactive
                    $inactiveProducts = $this->productRepository->fetchInActiveProducts($selectedProductIds);

                    if (! __isEmpty($inactiveProducts)) {
                        return $this->billingRepository->transactionResponse(2, [
                            'inactiveProducts' => $inactiveProducts,
                        ], __tr('Bill cannot be updated as some of products are inactive. Please remove inactive products'));
                    }

                    $stockTransactionsDeleteData = array_diff($billTransactionsIds, $stockTrasactionsUpdateIds);

                    if (! __isEmpty($storckTrasactionStoreData)) {
                        if ($this->inventoryRepository->storeStockTransactions($storckTrasactionStoreData)) {
                            $stockAdded = true;
                        } else {
                            $stockAdded = false;
                        }
                    }

                    if (! __isEmpty($storckTrasactionUpdateData)) {
                        if ($this->inventoryRepository->updateStockTransactions($storckTrasactionUpdateData)) {
                            $stockUpdated = true;
                        } else {
                            $stockUpdated = false;
                        }
                    }

                    if (! __isEmpty($stockTransactionsDeleteData)) {
                        if ($this->inventoryRepository->deleteStockTransactions($stockTransactionsDeleteData)) {
                            $stockDeleted = true;
                        } else {
                            $stockDeleted = false;
                        }
                    }
                }

                if ($isUpdated || $stockAdded || $stockUpdated || $stockDeleted) {
                    return $this->billingRepository->transactionResponse(1, null, __tr('Bill Updated Successfully.'));
                }

                return $this->billingRepository->transactionResponse(2, null, __tr('Bill not updated.'));
            });

        return $this->engineReaction($reactionCode);
    }

    /**
     * Process Print Bill
     *
     * @param  string  $billId
     * @param  string  $inputData
     * @return  array
     *---------------------------------------------------------------- */
    public function processPrintBill($billId)
    {
        $billData = $this->engineData($this->prepareBillDetails($billId));

        if (__isEmpty($billData)) {
            return $this->engineReaction(18, null, __tr('Bill does not exist.'));
        }

        return $this->engineReaction(1, [
            'billData' => $billData,
        ]);
    }

    /**
     * Process Download Pdf
     *
     * @param  string  $billId
     * @param  string  $inputData
     * @return  array
     *---------------------------------------------------------------- */
    public function processDownloadPdf($billId)
    {
        $billData = $this->engineData($this->prepareBillDetails($billId));

        if (__isEmpty($billData)) {
            App:
            abort(404);
        }

        // download pdf
        $billPdf = PDF::loadView('billing.download-pdf', ['billData' => $billData]);

        //return $billPdf->stream();
        return $billPdf->download($billId.'_bill.pdf');
    }

    /**
     * Delete Category Process
     *
     * @input number $billId
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function billDeleteProcess($billId)
    {
        $bill = $this->billingRepository->fetch($billId);

        // Check if bill exist
        if (__isEmpty($bill)) {
            return $this->engineReaction(18, null, __tr('Bill does not exist'));
        }

        // Check if bill is paed
        if ($bill->status == 2) {
            return $this->engineReaction(2, null, __tr('Bill cannot be deleted as it is Paid.'));
        }

        if ($this->billingRepository->deleteBill($bill)) {
            return $this->engineReaction(1, null, __tr('Bill deleted successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Bill not deleted.'));
    }

    /**
     * Delete Bill Transactions Process
     *
     * @input number $billId
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function deleteTransactionProcess($billId, $transactionId)
    {
        $bill = $this->billingRepository->fetch($billId);

        // Check if bill exist
        if (__isEmpty($bill)) {
            return $this->engineReaction(18, null, __tr('Bill does not exist'));
        }

        $transaction = $this->billingRepository->fetchBillStockTransaction($transactionId, $bill->_id);

        // Check if bill exist
        if (__isEmpty($transaction)) {
            return $this->engineReaction(18, null, __tr('Product combination for this bill does not exist'));
        }

        if ($this->billingRepository->deleteBillTransaction($transaction)) {
            return $this->engineReaction(1, null, __tr('Product combination deleted from bill successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Bill not deleted.'));
    }
}

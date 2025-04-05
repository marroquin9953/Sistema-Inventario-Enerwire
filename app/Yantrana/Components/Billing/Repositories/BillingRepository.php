<?php
/*
* BillingRepository.php - Repository file
*
* This file is part of the Billing component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Billing\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\Billing\Interfaces\BillingRepositoryInterface;
use App\Yantrana\Components\Billing\Models\BillingModel;
use App\Yantrana\Components\Inventory\Models\StockTransactionModel;

class BillingRepository extends BaseRepository implements BillingRepositoryInterface
{
    /**
     * Fetch the record of Billing
     *
     * @param    int || string $idOrUid
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetch($idOrUid)
    {
        if (is_numeric($idOrUid)) {
            return BillingModel::where('_id', $idOrUid)->first();
        }

        return BillingModel::where('_uid', $idOrUid)->first();
    }

    /**
     * Fetch billing datatable source
     *
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchBillingDataTableSource()
    {
        $dataTableConfig = [
            'searchable' => [
                'total_amount',
                'txn_id',
                'customer_name' => 'customers.name',
            ],
            'fieldAlias' => [
                'formatted_status' => 'status',
                'customer_name' => 'customers.name',
            ],
        ];

        return BillingModel::leftjoin('customers', 'bills.customers__id', '=', 'customers._id')
            ->select(__nestedKeyValues([
                'bills.*',
                'customers' => 'name as customer_name',
            ]))
            ->dataTables($dataTableConfig)
            ->toArray();
    }

    /**
     * Store New Bill
     *
     * @param  array  $storeData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function storeBill($storeData)
    {
        $keyValues = [
            'status',
            'customers__id',
            'user_authorities__id',
            'total_amount',
            'txn_id',
            'short_description',
            'bill_number',
            'bill_date',
            'due_date',
            '__data',
        ];

        $billModel = new BillingModel();

        if ($billModel->assignInputsAndSave($storeData, $keyValues)) {
            activityLog(17, $billModel->_id, 1);

            return $billModel->_id;
        }

        return false;
    }

    /**
     * Fetch the record of Billing
     *
     * @param    int || string $idOrUid
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchWithStockTransaction($idOrUid, $myLocationIds)
    {
        $billQuery = BillingModel::with([
            'stockTransactions' => function ($query) use ($myLocationIds) {
                $query->with([
                    'productCombination' => function ($comboQury) use ($myLocationIds) {
                        $comboQury->with([
                            'product',
                            'stockTrasactions' => function ($transQuery) use ($myLocationIds) {
                                $transQuery->with([
                                    'location' => function ($locQuery) use ($myLocationIds) {
                                        if (! __isEmpty($myLocationIds) and ! canAccess('admin')) {
                                            $locQuery->whereIn('_id', $myLocationIds);
                                        }
                                    },
                                ]);
                            },
                            'combinationOptions' => function ($comboQuery) {
                                $comboQuery->with([
                                    'optionValue' => function ($valueQuery) {
                                        $valueQuery->with('productOptionLabel');
                                    },
                                ]);
                            },
                        ]);
                    },
                ]);
            },
        ]);

        if (is_numeric($idOrUid)) {
            return $billQuery->where('_id', $idOrUid)->first();
        }

        return $billQuery->where('_uid', $idOrUid)->first();
    }

    /**
     * Fetch the record of Billing
     *
     * @param    int || string $idOrUid
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchBillingDataWithStockTransaction($idOrUid, $myLocationIds)
    {
        $billQuery = BillingModel::with([
            'stockTransactions' => function ($query) use ($myLocationIds) {
                $query->with([
                    'productCombination' => function ($comboQury) use ($myLocationIds) {
                        $comboQury->with([
                            'product',
                            'stockTrasactions' => function ($transQuery) use ($myLocationIds) {
                                $transQuery->with([
                                    'location' => function ($locQuery) use ($myLocationIds) {
                                        if (! __isEmpty($myLocationIds) and ! canAccess('admin')) {
                                            $locQuery->whereIn('_id', $myLocationIds);
                                        }
                                    },
                                ])->leftJoin('bills', 'stock_transactions.bills__id', '=', 'bills._id')
                                    ->where('bills.status', '!=', 1)
                                    ->orWhere('bills.status', '=', null);
                            },
                            'combinationOptions' => function ($comboQuery) {
                                $comboQuery->with([
                                    'optionValue' => function ($valueQuery) {
                                        $valueQuery->with('productOptionLabel');
                                    },
                                ]);
                            },
                        ]);
                    },
                ]);
            },
        ]);

        if (is_numeric($idOrUid)) {
            return $billQuery->where('_id', $idOrUid)->first();
        }

        return $billQuery->where('_uid', $idOrUid)->first();
    }

    /**
     * Fetch the record of Billing
     *
     * @param    int || string $idOrUid
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function updateBill($bill, $updateData)
    {
        // Check if bill updated then return positive response
        if ($bill->modelUpdate($updateData)) {
            activityLog(17, $bill->_id, 2);

            return true;
        }

        return false;
    }

    /**
     * Delete Categories Process
     *
     * @param object bill
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function deleteBill($bill)
    {
        if ($bill->delete()) {
            activityLog(17, $bill->_id, 3);

            return true;
        }

        return false;
    }

    /**
     * Fetch the record of Billing
     *
     * @param    int || string $idOrUid
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchBillStockTransaction($idOrUid, $billId)
    {
        if (is_numeric($idOrUid)) {
            return StockTransactionModel::where([
                '_id' => $idOrUid,
                'bills__id' => $billId,
            ])->first();
        }

        return StockTransactionModel::where([
            '_uid' => $idOrUid,
            'bills__id' => $billId,
        ])->first();
    }

    /**
     * Delete Transaction
     *
     * @param object transaction
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function deleteBillTransaction($transaction)
    {
        if ($transaction->delete()) {
            return true;
        }

        return false;
    }
}

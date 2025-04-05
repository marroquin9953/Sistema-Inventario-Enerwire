<?php
/*
* InventoryRepository.php - Repository file
*
* This file is part of the Inventory component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Inventory\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\Inventory\Interfaces\InventoryRepositoryInterface;
use App\Yantrana\Components\Inventory\Models\StockTransactionModel;

class InventoryRepository extends BaseRepository implements InventoryRepositoryInterface
{
    /**
     * Store Stock Transaction
     *
     * @param  array  $inputData
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function storeStockTransaction($inputData)
    {
        $newStoreTransactionModel = new StockTransactionModel();

        $keyValues = [
            'status' => 1,
            'quantity' => $inputData['quantity'],
            'type' => $inputData['type'],
            'sub_type' => $inputData['sub_type'],
            'user_authorities__id' => getUserAuthorityId(),
            'locations__id' => $inputData['location'],
            'suppliers__id' => array_get($inputData, 'supplier'),
            'product_combinations__id' => $inputData['product_combinations_id'],
            'currency_code' => getCurrency(),
            'total_price',
            'total_amount',
        ];

        // Check if stored transaction
        if ($newStoreTransactionModel->assignInputsAndSave($inputData, $keyValues)) {
            activityLog(7, $newStoreTransactionModel->_id, 2, $inputData['product_name'], $inputData['quantity'].' quantity added in '.$inputData['location_name'].' location.');

            return $newStoreTransactionModel;
        }

        return false;
    }

    /**
     * Fetch Stock Transaction by combo key
     *
     * @param  string  $comboId
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchStockTransaction($comboId)
    {
        return StockTransactionModel::where('combo_key', $comboId)
            ->with([
                'stockTransactionProductOptions' => function ($q) {
                    $q->with('productOptionValue');
                },
            ])
            ->first();
    }

    /**
     * Fetch total quantity by combo id
     *
     * @param  string  $comboId
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchStockTransactionByComboId($comboId)
    {
        return StockTransactionModel::where('combo_key', $comboId)
            ->get();
    }

    /**
     * Store Stock Transactions
     *
     * @param  array  $storeData
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function storeStockTransactions($storeData)
    {
        $stockTransaction = new StockTransactionModel();

        if ($stockTransaction->prepareAndInsert($storeData)) {
            activityLog(21, $stockTransaction->_id, 1);

            return true;
        }

        return false;
    }

    /**
     * Update Stock Transactions
     *
     * @param  array  $updateData
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function updateStockTransactions($updateData)
    {
        return StockTransactionModel::bunchUpdate($updateData, '_id');
    }

    /**
     * Update Stock Transactions
     *
     * @param  array  $updateData
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function deleteStockTransactions($stockIds)
    {
        return StockTransactionModel::whereIn('_id', $stockIds)->delete();
    }
}

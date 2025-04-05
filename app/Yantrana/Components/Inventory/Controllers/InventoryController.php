<?php
/*
* InventoryController.php - Controller file
*
* This file is part of the Inventory component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Inventory\Controllers;

use App\Yantrana\Base\BaseController;
use App\Yantrana\Components\Inventory\InventoryEngine;
use App\Yantrana\Components\Inventory\Requests\UpdateInventoryRequest;

class InventoryController extends BaseController
{
    /**
     * @var  InventoryEngine - Inventory Engine
     */
    protected $inventoryEngine;

    /**
     * Constructor
     *
     * @param  InventoryEngine  $inventoryEngine - Inventory Engine
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(InventoryEngine $inventoryEngine)
    {
        $this->inventoryEngine = $inventoryEngine;
    }

    /**
     * Get Datatable list of inventory
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function prepareInventoryList()
    {
        $processReaction = $this->inventoryEngine->prepareList();

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Get Inventory Update Data
     *
     * @param  string  $productId
     * @param  string  $combinationId
     * @param  string  $locationId
     * @param  string  $supplierId
     * @return json object
     *---------------------------------------------------------------- */
    public function getInventoryUpdateData($productId, $combinationId, $locationId, $supplierId)
    {
        $processReaction = $this->inventoryEngine->prepareInventoryUpdateData($productId, $combinationId, $locationId, $supplierId);
        // __dd($processReaction );
        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Get Inventory Update Data
     *
     * @param  string  $productId
     * @param  string  $combinationId
     * @param  string  $locationId
     * @return json object
     *---------------------------------------------------------------- */
    public function getProductCombination($productId)
    {
        $processReaction = $this->inventoryEngine->prepareProductCombination($productId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Calculate Options Quantity
     *
     * @param  string  $productId
     * @param  string  $combinationId
     * @param  string  $locationId
     * @param  string  $supplierId
     * @return json object
     *---------------------------------------------------------------- */
    public function calculateOptionsQuantity($productId, $combinationId, $locationId, $supplierId, $type)
    {
        $processReaction = $this->inventoryEngine->processCalcuateQuantity($productId, $combinationId, $locationId, $supplierId, $type);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Update Inventory Quantity
     *
     * @param  string  $productId
     * @return json object
     *---------------------------------------------------------------- */
    public function updateInventory(UpdateInventoryRequest $request, $productId)
    {
        $processReaction = $this->inventoryEngine->processUpdateInventory($request->all(), $productId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Get Inventory Transaction Data
     *
     * @param  string  $productId
     * @param  string  $combinationId
     * @param  string  $tranType
     * @param  string  $locationId
     * @return json object
     *---------------------------------------------------------------- */
    public function getInventoryTransactionData($productId, $combinationId, $tranType, $locationId)
    {
        if ($tranType == 1) {
            $processReaction = $this->inventoryEngine
                ->prepareInventoryTrasactionData($productId, $combinationId, $locationId);
        } elseif ($tranType == 2) {
            $processReaction = $this->inventoryEngine
                ->prepareProductTrasactionData($productId, $combinationId);
        }

        return __processResponse($processReaction, [], [], true);
    }
}

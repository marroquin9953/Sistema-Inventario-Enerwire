<?php

/*
* DashboardController.php - Controller file
*
* This file is part of the Dashboard component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Dashboard\Controllers;

use App\Yantrana\Base\BaseController;
use App\Yantrana\Components\Dashboard\DashboardEngine;
use App\Yantrana\Components\Inventory\InventoryEngine;

class DashboardController extends BaseController
{
    /**
     * @var DashboardEngine - Dashboard Engine
     */
    protected $dashboardEngine;

    /**
     * @var  InventoryEngine - Inventory Engine
     */
    protected $inventoryEngine;

    /**
     * Constructor
     *
     * @param  DashboardEngine  $dashboardEngine - Dashboard Engine
     * @return void
     *-----------------------------------------------------------------------*/
    public function __construct(
        DashboardEngine $dashboardEngine,
        InventoryEngine $inventoryEngine
    ) {
        $this->dashboardEngine = $dashboardEngine;
        $this->inventoryEngine = $inventoryEngine;
    }

    /**
     * get dashboard list.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function dashboardSupportData()
    {
        $processReaction = $this->dashboardEngine
            ->prepareDashboardSupportData();

        return __processResponse($processReaction, [], [
            'dashboard' => $processReaction['data'],
        ]);
    }

    /**
     * Search for product
     *
     * @param  string  $searchTerm
     * @return array
     *---------------------------------------------------------------- */
    public function searchProducts($searchTerm)
    {
        $processReaction = $this->dashboardEngine->prepareProductSearchData($searchTerm);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Product Inventory Details
     *
     * @param  string  $productId
     * @return array
     *---------------------------------------------------------------- */
    public function getProductInvetoryDetails($productId)
    {
        $processReaction = $this->inventoryEngine->prepareProductInvetoryDetails($productId);

        return __processResponse($processReaction, [], [], true);
    }
}

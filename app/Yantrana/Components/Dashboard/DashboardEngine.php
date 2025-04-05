<?php

/*
* DashboardEngine.php - Main component file
*
* This file is part of the Dashboard component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Dashboard;

use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\Dashboard\Blueprints\DashboardEngineBlueprint;
use App\Yantrana\Components\Dashboard\Repositories\DashboardRepository;
use App\Yantrana\Components\Location\Repositories\LocationRepository;
use App\Yantrana\Components\Product\Repositories\ProductRepository;

class DashboardEngine extends BaseEngine implements DashboardEngineBlueprint
{
    /**
     * @var DashboardRepository - Dashboard Repository
     */
    protected $dashboardRepository;

    /**
     * @var  LocationRepository - Location Repository
     */
    protected $locationRepository;

    /**
     * @var  ProductRepository - Product Repository
     */
    protected $productRepository;

    /**
     * Constructor
     *
     * @param  DashboardRepository  $dashboardRepository - Dashboard Repository
     * @return void
     *-----------------------------------------------------------------------*/
    public function __construct(
        DashboardRepository $dashboardRepository,
        LocationRepository $locationRepository,
        ProductRepository $productRepository
    ) {
        $this->dashboardRepository = $dashboardRepository;
        $this->locationRepository = $locationRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Get active data.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareDashboardSupportData()
    {
        return $this->engineReaction(1, [
            'dashboardData' => [
                'isAdmin' => isAdmin(),
                'myLocations' => $this->prepareMyLocations(),
            ],
        ]);
    }

    /**
     * Prepare My Locations
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareMyLocations()
    {
        if (! isAdmin()) {
            $locationsCollection = $this->locationRepository->fetchMyLocations(getUserAuthorityId());
        } else {
            $locationsCollection = $this->locationRepository->fetchLocations();
        }

        $locations = [];
        // Check if locations exist
        if (! __isEmpty($locationsCollection)) {
            foreach ($locationsCollection as $key => $location) {
                $locations[] = [
                    'name' => $location->name,
                ];
            }
        }

        return $locations;
    }

    /**
     * Prepare search product list
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareProductSearchData($searchTerm)
    {
        $myLocationIds = [];
        $myLocations = $this->locationRepository->fetchMyLocations(getUserAuthorityId());

        if (! __isEmpty($myLocations)) {
            $myLocationIds = $myLocations->pluck('_id')->toArray();
        }

        $productCollection = $this->productRepository->searchProducts($searchTerm, $myLocationIds);

        $productList = [];
        $keyList = [];

        // Check if product collection exist
        if (! __isEmpty($productCollection)) {
            foreach ($productCollection as $key => $product) {
                if (! in_array($product->product_combination_id, $keyList)) {
                    //check if location, category and product are active
                    if (($product->status == 1) and ($product->category_status == 1) and ($product->location_status == 1)) {
                        $productList[] = [
                            'id' => $product->product_combination_id,
                            'name' => $product->title.' ( '.$product->product_id.')',
                            'product_id' => $product->_id,
                            'product_name' => $product->name,
                            'supplier_id' => $product->suppliers__id,
                            'barcode' => $product->barcode,
                            'location_name' => $product->location_name,
                        ];
                    }
                }

                $keyList[] = $product->product_combination_id;
            }
        }

        return $this->engineReaction(1, [
            'productList' => array_values($productList),
        ]);
    }
}

<?php
/*
* ProductController.php - Controller file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Controllers;

use App\Yantrana\Base\BaseController;
use App\Yantrana\Components\Product\ProductEngine;
use App\Yantrana\Components\Product\Requests\ProductAddRequest;
use App\Yantrana\Components\Product\Requests\ProductEditRequest;
use App\Yantrana\Support\CommonPostRequest as Request;

class ProductController extends BaseController
{
    /**
     * @var  ProductEngine - Product Engine
     */
    protected $productEngine;

    /**
     * Constructor
     *
     * @param  ProductEngine  $productEngine - Product Engine
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(ProductEngine $productEngine)
    {
        $this->productEngine = $productEngine;
    }

    /**
     * list of Product
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function prepareProductList()
    {
        return $this->productEngine
          ->prepareProductDataTableSource();
    }

    /**
     * Product process delete
     *
     * @param  mix  $productIdOrUid
     * @return  json object
     *---------------------------------------------------------------- */
    public function processProductDelete(Request $request, $productIdOrUid)
    {
        $processReaction = $this->productEngine
          ->processProductDelete($productIdOrUid);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Product Add Support Data
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function prepareProductSupportData()
    {
        $processReaction = $this->productEngine
          ->prepareProductSupportData();

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Product Process Create
     *
     * @param  object ProductListRequest $request
     * @return  json object
     *---------------------------------------------------------------- */
    public function processProductCreate(ProductAddRequest $request)
    {
        $processReaction = $this->productEngine
          ->processProductCreate($request->all());

        return __processResponse($processReaction);
    }

    /**
     * Product get update data
     *
     * @param  mix  $productIdOrUid
     * @return  json object
     *---------------------------------------------------------------- */
    public function updateProductData($productIdOrUid)
    {
        $processReaction = $this->productEngine
          ->prepareProductUpdateData($productIdOrUid);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Product get update data
     *
     * @param  mix  $productIdOrUid
     * @return  json object
     *---------------------------------------------------------------- */
    public function getDetails($productIdOrUid)
    {
        $processReaction = $this->productEngine
          ->prepareDetails($productIdOrUid);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Product process update
     *
     * @param  mix @param  mix  $productIdOrUid
     * @param  object ProductListRequest $request
     * @return  json object
     *---------------------------------------------------------------- */
    public function processProductUpdate($productIdOrUid, ProductEditRequest $request)
    {
        $processReaction = $this->productEngine
          ->processProductUpdate($productIdOrUid, $request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Process Product Option Combination Delete
     *
     * @param  mix  $productId
     * @param  mix combinationId
     * @return  json object
     *---------------------------------------------------------------- */
    public function processProductCombinationDelete(Request $request, $productId, $combinationId)
    {
        $processReaction = $this->productEngine
          ->processProductCombinationDelete($productId, $combinationId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Process Product Option Value Delete
     *
     * @param  mix  $productId
     * @param  mix valueId
     * @return  json object
     *---------------------------------------------------------------- */
    public function processProductOptionValueDelete(Request $request, $productId, $comboId, $valueId)
    {
        $processReaction = $this->productEngine
          ->processProductOptionValueDelete($productId, $comboId, $valueId);

        return __processResponse($processReaction, [], [], true);
    }
}

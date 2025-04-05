<?php
/*
* CategoryController.php - Controller file
*
* This file is part of the Category component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Category\Controllers;

use App\Yantrana\Base\BaseController;
use App\Yantrana\Components\Category\CategoryEngine;
use App\Yantrana\Components\Category\Requests\AddCategoryRequest;
use App\Yantrana\Components\Category\Requests\EditCategoryRequest;
use App\Yantrana\Support\CommonPostRequest as Request;

class CategoryController extends BaseController
{
    /**
     * @var  CategoryEngine - Category Engine
     */
    protected $categoryEngine;

    /**
     * Constructor
     *
     * @param  CategoryEngine  $categoryEngine - Category Engine
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(CategoryEngine $categoryEngine)
    {
        $this->categoryEngine = $categoryEngine;
    }

    /**
     * Get Datatable list of category
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function index()
    {
        return $this->categoryEngine->prepareList();
    }

    /**
     * Category Add Process
     *
     * @param  object  $request
     * @return json object
     *---------------------------------------------------------------- */
    public function addProcess(AddCategoryRequest $request)
    {
        $processReaction = $this->categoryEngine->addCategoryProcess($request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Category Add Process
     *
     * @param  object  $request
     * @return json object
     *---------------------------------------------------------------- */
    public function addFromProductProcess(Request $request)
    {
        $processReaction = $this->categoryEngine->addCategoryProcess($request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Category get update data
     *
     * @param  int  $categoryId
     * @return json object
     *---------------------------------------------------------------- */
    public function getUpdateData($categoryId)
    {
        $processReaction = $this->categoryEngine->prepareUpdateData($categoryId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Category Update Process
     *
     * @param  object  $request
     * @return json object
     *---------------------------------------------------------------- */
    public function updateProcess(EditCategoryRequest $request, $categoryId)
    {
        $processReaction = $this->categoryEngine->updateCategoryProcess($request->all(), $categoryId);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Category Delete Process
     *
     * @param  object  $request
     * @param  string  $categoryId
     * @return json object
     *---------------------------------------------------------------- */
    public function deleteProcess(Request $request, $categoryId)
    {
        $processReaction = $this->categoryEngine->categoryDeleteProcess($categoryId);

        return __processResponse($processReaction, [], [], true);
    }
}

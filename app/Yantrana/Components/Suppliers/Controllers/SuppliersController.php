<?php
/*
* SuppliersController.php - Controller file
*
* This file is part of the Suppliers component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Suppliers\Controllers;

use App\Yantrana\Base\BaseController;
use App\Yantrana\Components\Suppliers\Requests\SuppliersAddRequest;
use App\Yantrana\Components\Suppliers\Requests\SuppliersEditRequest;
use App\Yantrana\Components\Suppliers\SuppliersEngine;
use App\Yantrana\Support\CommonPostRequest as Request;

class SuppliersController extends BaseController
{
    /**
     * @var  SuppliersEngine - Suppliers Engine
     */
    protected $suppliersEngine;

    /**
     * Constructor
     *
     * @param  SuppliersEngine  $suppliersEngine - Suppliers Engine
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(SuppliersEngine $suppliersEngine)
    {
        $this->suppliersEngine = $suppliersEngine;
    }

    /**
     * list of Suppliers
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function prepareSuppliersList()
    {
        return $this->suppliersEngine
            ->prepareSuppliersDataTableSource();
    }

    /**
     * Suppliers process delete
     *
     * @param  mix  $suppliersIdOrUid
     * @return  json object
     *---------------------------------------------------------------- */
    public function processSuppliersDelete(Request $request, $suppliersIdOrUid)
    {
        $processReaction = $this->suppliersEngine
            ->processSuppliersDelete($suppliersIdOrUid);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Suppliers create process
     *
     * @param  object SuppliersListRequest $request
     * @return  json object
     *---------------------------------------------------------------- */
    public function processSuppliersCreate(SuppliersAddRequest $request)
    {
        $processReaction = $this->suppliersEngine
            ->processSuppliersCreate($request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Suppliers get update data
     *
     * @param  mix  $suppliersIdOrUid
     * @return  json object
     *---------------------------------------------------------------- */
    public function updateSuppliersData($suppliersIdOrUid)
    {
        $processReaction = $this->suppliersEngine
            ->prepareSuppliersUpdateData($suppliersIdOrUid);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Suppliers process update
     *
     * @param  mix @param  mix  $suppliersIdOrUid
     * @param  object SuppliersListRequest $request
     * @return  json object
     *---------------------------------------------------------------- */
    public function processSuppliersUpdate($suppliersIdOrUid, SuppliersEditRequest $request)
    {
        $processReaction = $this->suppliersEngine
            ->processSuppliersUpdate($suppliersIdOrUid, $request->all());

        return __processResponse($processReaction, [], [], true);
    }
}

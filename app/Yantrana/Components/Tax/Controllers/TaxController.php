<?php
/*
* TaxController.php - Controller file
*
* This file is part of the Tax component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Tax\Controllers;

use App\Yantrana\Base\BaseController;
use App\Yantrana\Components\Tax\Requests\TaxAddRequest;
use App\Yantrana\Components\Tax\Requests\TaxEditRequest;
use App\Yantrana\Components\Tax\TaxEngine;

class TaxController extends BaseController
{
    /**
     * @var  TaxEngine - Tax Engine
     */
    protected $taxEngine;

    /**
     * Constructor
     *
     * @param  TaxEngine  $taxEngine - Tax Engine
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(TaxEngine $taxEngine)
    {
        $this->taxEngine = $taxEngine;
    }

    /**
     * list of Tax
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function prepareTaxList($taxPresetIdOrUid)
    {
        return $this->taxEngine->prepareTaxDataTableSource($taxPresetIdOrUid);
    }

    /**
     * Tax process delete
     *
     * @param  mix  $taxIdOrUid
     * @return  json object
     *---------------------------------------------------------------- */
    public function processTaxDelete($taxPresetIdOrUid, $taxIdOrUid)
    {
        $processReaction = $this->taxEngine
            ->processTaxDelete($taxPresetIdOrUid, $taxIdOrUid);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Tax Add Support Data
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function prepareTaxSupportData($taxPresetIdOrUid)
    {
        $processReaction = $this->taxEngine->prepareTaxSupportData($taxPresetIdOrUid);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Tax create process
     *
     * @param  object TaxListRequest $request
     * @return  json object
     *---------------------------------------------------------------- */
    public function processTaxCreate(TaxAddRequest $request, $taxPresetIdOrUid)
    {
        $processReaction = $this->taxEngine
            ->processTaxCreate($request->all(), $taxPresetIdOrUid);

        return __processResponse($processReaction);
    }

    /**
     * Tax get update data
     *
     * @param  mix  $taxIdOrUid
     * @return  json object
     *---------------------------------------------------------------- */
    public function updateTaxData($taxPresetIdOrUid, $taxIdOrUid)
    {
        $processReaction = $this->taxEngine
            ->prepareTaxUpdateData($taxPresetIdOrUid, $taxIdOrUid);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Tax process update
     *
     * @param  mix @param  mix  $taxIdOrUid
     * @param  object TaxListRequest $request
     * @return  json object
     *---------------------------------------------------------------- */
    public function processTaxUpdate($taxPresetIdOrUid, $taxIdOrUid, TaxEditRequest $request)
    {
        $processReaction = $this->taxEngine
            ->processTaxUpdate($taxPresetIdOrUid, $taxIdOrUid, $request->all());

        return __processResponse($processReaction, [], [], true);
    }
}

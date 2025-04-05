<?php
/*
* TaxPresetController.php - Controller file
*
* This file is part of the TaxPreset component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\TaxPreset\Controllers;

use App\Yantrana\Base\BaseController;
use App\Yantrana\Components\TaxPreset\Requests\TaxpresetAddRequest;
use App\Yantrana\Components\TaxPreset\Requests\TaxpresetEditRequest;
use App\Yantrana\Components\TaxPreset\TaxPresetEngine;

class TaxPresetController extends BaseController
{
    /**
     * @var  TaxPresetEngine - TaxPreset Engine
     */
    protected $taxPresetEngine;

    /**
     * Constructor
     *
     * @param  TaxPresetEngine  $taxPresetEngine - TaxPreset Engine
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(TaxPresetEngine $taxPresetEngine)
    {
        $this->taxPresetEngine = $taxPresetEngine;
    }

    /**
     * list of Taxpreset
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function prepareTaxpresetList()
    {
        return $this->taxPresetEngine
            ->prepareTaxpresetDataTableSource();
    }

    /**
     * Taxpreset process delete
     *
     * @param  mix  $taxPresetIdOrUid
     * @return  json object
     *---------------------------------------------------------------- */
    public function processTaxpresetDelete($taxPresetIdOrUid)
    {
        $processReaction = $this->taxPresetEngine
            ->processTaxpresetDelete($taxPresetIdOrUid);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Taxpreset Add Support Data
     *
     * @return  json object
     *---------------------------------------------------------------- */
    public function prepareTaxpresetSupportData()
    {
        $processReaction = $this->taxPresetEngine
            ->prepareTaxpresetSupportData();

        return __processResponse($processReaction);
    }

    /**
     * Taxpreset create process
     *
     * @param  object TaxpresetListRequest $request
     * @return  json object
     *---------------------------------------------------------------- */
    public function processTaxpresetCreate(TaxpresetAddRequest $request)
    {
        $processReaction = $this->taxPresetEngine
            ->processTaxpresetCreate($request->all());

        return __processResponse($processReaction);
    }

    /**
     * Taxpreset get update data
     *
     * @param  mix  $taxPresetIdOrUid
     * @return  json object
     *---------------------------------------------------------------- */
    public function updateTaxpresetData($taxPresetIdOrUid)
    {
        $processReaction = $this->taxPresetEngine
            ->prepareTaxpresetUpdateData($taxPresetIdOrUid);

        return __processResponse($processReaction, [], [], true);
    }

    /**
     * Taxpreset process update
     *
     * @param  mix @param  mix  $taxPresetIdOrUid
     * @param  object TaxpresetListRequest $request
     * @return  json object
     *---------------------------------------------------------------- */
    public function processTaxpresetUpdate($taxPresetIdOrUid, TaxpresetEditRequest $request)
    {
        $processReaction = $this->taxPresetEngine
            ->processTaxpresetUpdate($taxPresetIdOrUid, $request->all());

        return __processResponse($processReaction, [], [], true);
    }
}

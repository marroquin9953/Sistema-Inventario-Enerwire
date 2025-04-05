<?php
/*
* TaxPresetEngine.php - Main component file
*
* This file is part of the TaxPreset component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\TaxPreset;

use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\TaxPreset\Interfaces\TaxPresetEngineInterface;
use App\Yantrana\Components\TaxPreset\Repositories\TaxPresetRepository;

class TaxPresetEngine extends BaseEngine implements TaxPresetEngineInterface
{
    /**
     * @var  TaxPresetRepository - TaxPreset Repository
     */
    protected $taxPresetRepository;

    /**
     * Constructor
     *
     * @param  TaxPresetRepository  $taxPresetRepository - TaxPreset Repository
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(TaxPresetRepository $taxPresetRepository)
    {
        $this->taxPresetRepository = $taxPresetRepository;
    }

    /**
     * Taxpreset datatable source
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareTaxpresetDataTableSource()
    {
        $taxpresetCollection = $this->taxPresetRepository->fetchTaxpresetDataTableSource();

        $requireColumns = [
            '_id',
            '_uid',
            'title',
            'status',
            'formatted_status' => function ($key) {
                return configItem('tax_status', $key['status']);
            },
            'short_description',
            'can_edit' => function ($key) {
                return canAccess('manage.tax_preset.write.update');
            },
            'can_delete' => function ($key) {
                return canAccess('manage.tax_preset.write.delete');
            },
            'can_view_tax_list' => function ($key) {
                return canAccess('manage.tax.read.list');
            },
        ];

        return $this->dataTableResponse($taxpresetCollection, $requireColumns);
    }

    /**
     * Taxpreset delete process
     *
     * @param  mix  $taxPresetIdOrUid
     * @return  array
     *---------------------------------------------------------------- */
    public function processTaxpresetDelete($taxPresetIdOrUid)
    {
        $taxpreset = $this->taxPresetRepository
            ->fetch($taxPresetIdOrUid);

        if (__isEmpty($taxpreset)) {
            return $this->engineReaction(18, null, __tr('Tax preset not found.'));
        }

        if ($this->taxPresetRepository
            ->deleteTaxpreset($taxpreset)
        ) {
            return $this->engineReaction(1, null, __tr('Tax preset deleted successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Tax preset not deleted.'));
    }

    /**
     * Taxpreset Add Support Data
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareTaxpresetSupportData()
    {
        return $this->engineReaction(1, []);
    }

    /**
     * Taxpreset create
     *
     * @param  array  $inputData
     * @return  array
     *---------------------------------------------------------------- */
    public function processTaxpresetCreate($inputData)
    {
        if ($this->taxPresetRepository->storeTaxpreset($inputData)) {
            return $this->engineReaction(1, null, __tr('Tax preset added successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Tax preset not added.'));
    }

    /**
     * Taxpreset prepare update data
     *
     * @param  mix  $taxPresetIdOrUid
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareTaxpresetUpdateData($taxPresetIdOrUid)
    {
        $taxpreset = $this->taxPresetRepository->fetch($taxPresetIdOrUid);

        // Check if $taxpreset not exist then throw not found
        // exception
        if (__isEmpty($taxpreset)) {
            return $this->engineReaction(18, null, __tr('Tax preset not found.'));
        }

        $updateData = [
            'title' => $taxpreset->title,
            'description' => $taxpreset->short_description,
            'status' => ($taxpreset->status == 1) ? true : false,
        ];

        return $this->engineReaction(1, $updateData);
    }

    /**
     * Taxpreset process update
     *
     * @param  mix  $taxPresetIdOrUid
     * @param  array  $inputData
     * @return  array
     *---------------------------------------------------------------- */
    public function processTaxpresetUpdate($taxPresetIdOrUid, $inputData)
    {
        $taxpreset = $this->taxPresetRepository->fetch($taxPresetIdOrUid);

        // Check if $taxpreset not exist then throw not found
        // exception
        if (__isEmpty($taxpreset)) {
            return $this->engineReaction(18, null, __tr('Tax preset not found.'));
        }

        $updateData = [
            'title' => $inputData['title'],
            'short_description' => isset($inputData['description']) ? $inputData['description'] : null,
            'status' => ($inputData['status'] == true) ? 1 : 2,
        ];

        // Check if Taxpreset updated
        if ($this->taxPresetRepository->updateTaxpreset($taxpreset, $updateData)) {
            return $this->engineReaction(1, null, __tr('Tax preset updated successfully.'));
        }

        return $this->engineReaction(14, null, __tr('Tax preset not updated.'));
    }
}

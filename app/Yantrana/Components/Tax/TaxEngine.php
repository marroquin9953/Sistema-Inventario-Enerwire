<?php
/*
* TaxEngine.php - Main component file
*
* This file is part of the Tax component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Tax;

use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\Tax\Interfaces\TaxEngineInterface;
use App\Yantrana\Components\Tax\Repositories\TaxRepository;
use App\Yantrana\Components\TaxPreset\Repositories\TaxPresetRepository;

class TaxEngine extends BaseEngine implements TaxEngineInterface
{
    /**
     * @var  TaxRepository - Tax Repository
     */
    protected $taxRepository;

    /**
     * @var  TaxPresetRepository - TaxPreset Repository
     */
    protected $taxPresetRepository;

    /**
     * Constructor
     *
     * @param  TaxRepository  $taxRepository - Tax Repository
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(TaxRepository $taxRepository, TaxPresetRepository $taxPresetRepository)
    {
        $this->taxRepository = $taxRepository;
        $this->taxPresetRepository = $taxPresetRepository;
    }

    /**
     * Tax datatable source
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareTaxDataTableSource($taxPresetIdOrUid)
    {
        $taxpreset = $this->taxPresetRepository->fetch($taxPresetIdOrUid);

        if (__isEmpty($taxpreset)) {
            return $this->engineReaction(18, null, __tr('Tax preset not found.'));
        }

        $taxCollection = $this->taxRepository->fetchTaxDataTableSource($taxpreset->_id);

        $taxTypes = configItem('tax.type');

        $requireColumns = [
            '_id',
            '_uid',
            'title',
            'status',
            'formatted_status' => function ($key) {
                return configItem('tax_status', $key['status']);
            },
            'tax_amount' => function ($key) {
                if (isset($key['type']) and $key['type'] == 1) {
                    return moneyFormat($key['tax_amount'], true, true);
                } else {
                    return floatval($key['tax_amount']).'%';
                }
            },
            'type',
            'formatted_type' => function ($key) use ($taxTypes) {
                if (! __isEmpty($key['type'])) {
                    return $taxTypes[$key['type']];
                }
            },

            'can_edit' => function () {
                return canAccess('manage.tax.write.update');
            },
            'can_delete' => function () {
                return canAccess('manage.tax.write.delete');
            },

        ];

        return $this->dataTableResponse($taxCollection, $requireColumns);
    }

    /**
     * Tax delete process
     *
     * @param  mix  $taxIdOrUid
     * @return  array
     *---------------------------------------------------------------- */
    public function processTaxDelete($taxPresetIdOrUid, $taxIdOrUid)
    {
        $taxpreset = $this->taxPresetRepository->fetch($taxPresetIdOrUid);

        if (__isEmpty($taxpreset)) {
            return $this->engineReaction(18, null, __tr('Tax preset not found.'));
        }

        $tax = $this->taxRepository->fetch($taxIdOrUid);

        if (__isEmpty($tax)) {
            return $this->engineReaction(18, null, __tr('Tax not found.'));
        }

        if ($this->taxRepository->deleteTax($tax)) {
            return $this->engineReaction(1, null, __tr('Tax deleted successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Tax not deleted.'));
    }

    /**
     * Tax Add Support Data
     *
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareTaxSupportData($taxPresetIdOrUid)
    {
        $taxpreset = $this->taxPresetRepository->fetch($taxPresetIdOrUid);

        if (__isEmpty($taxpreset)) {
            return $this->engineReaction(18, null, __tr('Tax preset not found.'));
        }

        return $this->engineReaction(1, [
            'tax_types' => configItem('tax.type'),
        ]);
    }

    /**
     * Tax create
     *
     * @param  array  $inputData
     * @return  array
     *---------------------------------------------------------------- */
    public function processTaxCreate($inputData, $taxPresetIdOrUid)
    {
        $taxpreset = $this->taxPresetRepository->fetch($taxPresetIdOrUid);

        if (__isEmpty($taxpreset)) {
            return $this->engineReaction(18, null, __tr('Tax preset not found.'));
        }

        $storeData = [
            'title' => $inputData['title'],
            'tax_amount' => $inputData['amount'],
            'type' => $inputData['type'],
            'status' => ($inputData['status'] == true) ? 1 : 2,
            'tax_presets__id' => $taxpreset->_id,
        ];

        if ($this->taxRepository->storeTax($storeData)) {
            return $this->engineReaction(1, null, __tr('Tax added successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Tax not added.'));
    }

    /**
     * Tax prepare update data
     *
     * @param  mix  $taxIdOrUid
     * @return  array
     *---------------------------------------------------------------- */
    public function prepareTaxUpdateData($taxPresetIdOrUid, $taxIdOrUid)
    {
        $taxpreset = $this->taxPresetRepository->fetch($taxPresetIdOrUid);

        if (__isEmpty($taxpreset)) {
            return $this->engineReaction(18, null, __tr('Tax preset not found.'));
        }

        $tax = $this->taxRepository->fetch($taxIdOrUid);

        // Check if $tax not exist then throw not found
        // exception
        if (__isEmpty($tax)) {
            return $this->engineReaction(18, null, __tr('Tax not found.'));
        }

        $updateData = [
            'title' => $tax->title,
            'amount' => (float) moneyFormat($tax->tax_amount),
            'type' => $tax->type,
            'status' => ($tax->status == 1) ? true : false,
        ];

        return $this->engineReaction(1, [
            'updateData' => $updateData,
            'tax_types' => configItem('tax.type'),
        ]);
    }

    /**
     * Tax process update
     *
     * @param  mix  $taxIdOrUid
     * @param  array  $inputData
     * @return  array
     *---------------------------------------------------------------- */
    public function processTaxUpdate($taxPresetIdOrUid, $taxIdOrUid, $inputData)
    {
        $taxpreset = $this->taxPresetRepository->fetch($taxPresetIdOrUid);

        if (__isEmpty($taxpreset)) {
            return $this->engineReaction(18, null, __tr('Tax preset not found.'));
        }

        $tax = $this->taxRepository->fetch($taxIdOrUid);

        // Check if $tax not exist then throw not found
        // exception
        if (__isEmpty($tax)) {
            return $this->engineReaction(18, null, __tr('Tax not found.'));
        }

        $updateData = [
            'title' => $inputData['title'],
            'tax_amount' => $inputData['amount'],
            'type' => $inputData['type'],
            'tax_presets__id' => $taxpreset->_id,
            'status' => ($inputData['status'] == true) ? 1 : 2,
        ];

        // Check if Tax updated
        if ($this->taxRepository->updateTax($tax, $updateData)) {
            return $this->engineReaction(1, null, __tr('Tax updated successfully.'));
        }

        return $this->engineReaction(14, null, __tr('Tax not updated.'));
    }
}

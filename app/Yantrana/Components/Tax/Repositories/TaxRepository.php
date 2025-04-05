<?php
/*
* TaxRepository.php - Repository file
*
* This file is part of the Tax component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Tax\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\Tax\Interfaces\TaxRepositoryInterface;
use App\Yantrana\Components\Tax\Models\TaxModel;

class TaxRepository extends BaseRepository implements TaxRepositoryInterface
{
    /**
     * Fetch the record of Tax
     *
     * @param    int || string $idOrUid
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetch($idOrUid)
    {
        if (is_numeric($idOrUid)) {
            return TaxModel::where('_id', $idOrUid)->first();
        }

        return TaxModel::where('_uid', $idOrUid)->first();
    }

    /**
     * Fetch tax datatable source
     *
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchTaxDataTableSource($taxPresetId)
    {
        $dataTableConfig = [
            'searchable' => [
                'title',
                'tax_amount',
                'type',
            ],
        ];

        return TaxModel::where('tax_presets__id', '=', $taxPresetId)
            ->dataTables($dataTableConfig)
            ->toArray();
    }

    /**
     * Delete $tax record and return response
     *
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function deleteTax($tax)
    {
        // Check if $tax deleted
        if ($tax->delete()) {
            activityLog(19, $tax->_id, 3);

            return true;
        }

        return false;
    }

    /**
     * Store new tax record and return response
     *
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function storeTax($inputData)
    {
        $keyValues = [
            'title',
            'tax_amount',
            'type',
            'tax_presets__id',
            'status',
        ];

        $newTax = new TaxModel();

        // Check if task testing record added then return positive response
        if ($newTax->assignInputsAndSave($inputData, $keyValues)) {
            activityLog(19, $newTax->_id, 3);

            return $newTax;
        }

        return false;
    }

    /**
     * Update tax record and return response
     *
     * @param  object  $tax
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function updateTax($tax, $inputData)
    {
        // Check if tax updated then return positive response
        if ($tax->modelUpdate($inputData)) {
            activityLog(19, $tax->_id, 2);

            return true;
        }

        return false;
    }
}

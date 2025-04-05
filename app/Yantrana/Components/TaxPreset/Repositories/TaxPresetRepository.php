<?php
/*
* TaxPresetRepository.php - Repository file
*
* This file is part of the TaxPreset component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\TaxPreset\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\TaxPreset\Interfaces\TaxPresetRepositoryInterface;
use App\Yantrana\Components\TaxPreset\Models\TaxPresetModel;

class TaxPresetRepository extends BaseRepository implements TaxPresetRepositoryInterface
{
    /**
     * Fetch the record of TaxPreset
     *
     * @param    int || string $idOrUid
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetch($idOrUid)
    {
        if (is_numeric($idOrUid)) {
            return TaxPresetModel::where('_id', $idOrUid)->first();
        }

        return TaxPresetModel::where('_uid', $idOrUid)->first();
    }

    /**
     * Fetch taxpreset datatable source
     *
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchTaxpresetDataTableSource()
    {
        $dataTableConfig = [
            'searchable' => [
                'title',
                'short_description',
            ],
        ];

        return TaxPresetModel::dataTables($dataTableConfig)
            ->toArray();
    }

    /**
     * Delete $taxpreset record and return response
     *
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function deleteTaxpreset($taxpreset)
    {
        // Check if $taxpreset deleted
        if ($taxpreset->delete()) {
            activityLog(18, $taxpreset->_id, 3);

            return true;
        }

        return false;
    }

    /**
     * Store new taxpreset record and return response
     *
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function storeTaxpreset($inputData)
    {
        $keyValues = [
            'title' => $inputData['title'],
            'status' => ($inputData['status'] == true) ? 1 : 2,
            'short_description' => isset($inputData['description'])
                ? $inputData['description'] : null,
        ];

        $newTaxPreset = new TaxPresetModel();

        // Check if task testing record added then return positive response
        if ($newTaxPreset->assignInputsAndSave($inputData, $keyValues)) {
            activityLog(18, $newTaxPreset->_id, 1);

            return $newTaxPreset;
        }

        return false;
    }

    /**
     * Update taxpreset record and return response
     *
     * @param  object  $taxpreset
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function updateTaxpreset($taxpreset, $inputData)
    {
        // Check if taxpreset updated then return positive response
        if ($taxpreset->modelUpdate($inputData)) {
            activityLog(18, $taxpreset->_id, 2);

            return true;
        }

        return false;
    }

    /**
     * Fetch all Tax Presets
     *
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAll($status = [1])
    {
        return TaxPresetModel::whereIn('status', $status)->get();
    }
}

<?php
/*
* SuppliersRepository.php - Repository file
*
* This file is part of the Suppliers component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Suppliers\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\Suppliers\Interfaces\SuppliersRepositoryInterface;
use App\Yantrana\Components\Suppliers\Models\SuppliersModel;

class SuppliersRepository extends BaseRepository implements SuppliersRepositoryInterface
{
    /**
     * Fetch the record of Suppliers
     *
     * @param    int || string $idOrUid
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetch($idOrUid)
    {
        if (is_numeric($idOrUid)) {
            return SuppliersModel::where('_id', $idOrUid)->first();
        }

        return SuppliersModel::where('_uid', $idOrUid)->first();
    }

    /**
     * Fetch suppliers datatable source
     *
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchSuppliersDataTableSource()
    {
        $dataTableConfig = [
            'searchable' => [
                'name',
                'short_description',
            ],
        ];

        return SuppliersModel::where('status', 1)->dataTables($dataTableConfig)
            ->toArray();
    }

    /**
     * Delete $suppliers record and return response
     *
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function deleteSuppliers($suppliers, $updateData)
    {
        // Check if $suppliers deleted
        if ($suppliers->modelUpdate($updateData)) {
            activityLog(13, $suppliers->_id, 3, $suppliers->name);

            return true;
        }

        return false;
    }

    /**
     * Store new suppliers record and return response
     *
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function storeSuppliers($inputData)
    {
        $keyValues = [
            'name',
            'short_description',
            'status' => 1,
            'user_authorities__id' => getUserAuthorityId(),
        ];

        $newSuppliers = new SuppliersModel();

        // Check if task testing record added then return positive response
        if ($newSuppliers->assignInputsAndSave($inputData, $keyValues)) {
            activityLog(13, $newSuppliers->_id, 1, $newSuppliers->name);

            return $newSuppliers;
        }

        return false;
    }

    /**
     * Update suppliers record and return response
     *
     * @param  object  $suppliers
     * @param  array  $inputData
     * @return  mixed
     *---------------------------------------------------------------- */
    public function updateSuppliers($suppliers, $inputData)
    {
        // Check if suppliers updated then return positive response
        if ($suppliers->modelUpdate($inputData)) {
            activityLog(13, $suppliers->_id, 2, $suppliers->name);

            return true;
        }

        return false;
    }

    /**
     * Fetch All Suppliers
     *
     * @return  mixed
     *---------------------------------------------------------------- */
    public function fetchAllSuppliers()
    {
        return SuppliersModel::where('status', 1)->get();
    }
}

<?php
/*
* BarcodesRepository.php - Repository file
*
* This file is part of the Barcodes component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Barcodes\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\Barcodes\Interfaces\BarcodesRepositoryInterface;
use App\Yantrana\Components\Barcodes\Models\BarcodesModel;

class BarcodesRepository extends BaseRepository implements BarcodesRepositoryInterface
{
    /**
     * Fetch the record of Barcodes
     *
     * @param    int || string $idOrUid
     * @return    eloquent collection object
     *---------------------------------------------------------------- */
    public function fetch($idOrUid)
    {
        if (is_numeric($idOrUid)) {
            return BarcodesModel::where('_id', $idOrUid)->first();
        }

        return BarcodesModel::where('_uid', $idOrUid)->first();
    }

    /**
     * Fetch the record of Barcodes
     *
     * @param  int || string $idOrUid
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchBarcode($code)
    {
        return BarcodesModel::where('barcode', $code)->first();
    }

    /**
     * Insert Barcodes
     */
    public function insert($inputData)
    {
        $barcodesModel = new BarcodesModel();

        if ($barcodesModel->prepareAndInsert($inputData)) {
            return true;
        }

        return false;
    }

    /**
     * Delete Barcode
     */
    public function delete($modelInstance)
    {
        if ($modelInstance->delete()) {
            return true;
        }

        return false;
    }
}

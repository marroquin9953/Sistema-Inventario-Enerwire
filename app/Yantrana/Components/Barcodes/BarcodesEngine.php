<?php
/*
* BarcodesEngine.php - Main component file
*
* This file is part of the Barcodes component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Barcodes;

use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\Barcodes\Interfaces\BarcodesEngineInterface;
use App\Yantrana\Components\Barcodes\Repositories\BarcodesRepository;

class BarcodesEngine extends BaseEngine implements BarcodesEngineInterface
{
    /**
     * @var  BarcodesRepository - Barcodes Repository
     */
    protected $barcodeRepository;

    /**
     * Constructor
     *
     * @param  BarcodesRepository  $barcodesRepository - Barcodes Repository
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(BarcodesRepository $barcodeRepository)
    {
        $this->barcodeRepository = $barcodeRepository;
    }

    /**
     * Process delete barcode
     *
     * @param  mix  $productId
     * @param  mix combinationId
     * @return  json object
     *---------------------------------------------------------------- */
    public function processDelete($code)
    {
        $barCodeRecord = $this->barcodeRepository->fetchBarcode($code);

        if (__isEmpty($barCodeRecord)) {
            return $this->engineReaction(1);
        }

        // Delete Barcode
        if ($this->barcodeRepository->delete($barCodeRecord)) {
            activityLog(16, null, 3, $code);

            return $this->engineReaction(1, null, 'Barcode deleted successfully.');
        }

        return $this->engineReaction(2, null, 'Barcode not deleted.');
    }
}

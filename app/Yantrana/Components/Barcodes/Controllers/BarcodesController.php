<?php
/*
* BarcodesController.php - Controller file
*
* This file is part of the Barcodes component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Barcodes\Controllers;

use App\Yantrana\Base\BaseController;
use App\Yantrana\Components\Barcodes\BarcodesEngine;
use Illuminate\Http\Request;

class BarcodesController extends BaseController
{
    /**
     * @var  BarcodesEngine - Barcodes Engine
     */
    protected $barcodesEngine;

    /**
     * Constructor
     *
     * @param  BarcodesEngine  $barcodesEngine - Barcodes Engine
     * @return  void
     *-----------------------------------------------------------------------*/
    public function __construct(BarcodesEngine $barcodesEngine)
    {
        $this->barcodesEngine = $barcodesEngine;
    }

    /**
     * Process delete barcode delete
     *
     * @param  mix  $productId
     * @param  mix combinationId
     * @return  json object
     *---------------------------------------------------------------- */
    public function delete(Request $request)
    {
        $processReaction = $this->barcodesEngine
            ->processDelete(
                $request->input('barcode')
            );

        return __processResponse($processReaction, [], [], true);
    }
}

<?php
/*
* Barcodes.php - Model file
*
* This file is part of the Barcodes component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Barcodes\Models;

use App\Yantrana\Base\BaseModel;

class BarcodesModel extends BaseModel
{
    /**
     * @var  string - The database table used by the model.
     */
    protected $table = 'barcodes';

    /**
     * @var  array - The attributes that should be casted to native types.
     */
    protected $casts = [
        'product_combinations__id' => 'integer',
    ];
}

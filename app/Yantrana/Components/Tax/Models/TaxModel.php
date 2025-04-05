<?php
/*
* Tax.php - Model file
*
* This file is part of the Tax component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Tax\Models;

use App\Yantrana\Base\BaseModel;

class TaxModel extends BaseModel
{
    /**
     * @var  string - The database table used by the model.
     */
    protected $table = 'taxes';

    /**
     * @var  array - The attributes that should be casted to native types.
     */
    protected $casts = [
        'tax_amount' => 'double',
    ];

    /**
     * @var  array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}

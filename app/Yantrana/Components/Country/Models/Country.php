<?php

/*
* Country.php - Model file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Country\Models;

use App\Yantrana\Base\BaseModel;

class Country extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'countries';

    /**
     * @var array - The attributes that should be casted to native types.
     */
    protected $casts = [
        '_id' => 'integer',
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}

<?php
/*
* UserLocationModel.php - Model file
*
* This file is part of the Location component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Location\Models;

use App\Yantrana\Base\BaseModel;

class UserLocationModel extends BaseModel
{
    /**
     * @var  string - The database table used by the model.
     */
    protected $table = 'user_locations';

    /**
     * @var  array - The attributes that should be casted to native types.
     */
    protected $casts = [];

    /**
     * @var  array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}

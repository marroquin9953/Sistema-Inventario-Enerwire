<?php
/*
* Category.php - Model file
*
* This file is part of the Category component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Category\Models;

use App\Yantrana\Base\BaseModel;

class CategoryModel extends BaseModel
{
    /**
     * @var  string - The database table used by the model.
     */
    protected $table = 'categories';

    /**
     * Does it has has Entity Ownership ID
     *
     * @var bool
     *----------------------------------------------------------------------- */
    protected $hasEoId = true;

    /**
     * @var  array - The attributes that should be casted to native types.
     */
    protected $casts = [
        '_id' => 'integer',
        'status' => 'integer',
        'user_authorities__id' => 'integer',
    ];

    /**
     * @var  array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}

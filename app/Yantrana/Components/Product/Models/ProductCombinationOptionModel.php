<?php
/*
* ProductCombinationOptionModel.php - Model file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Models;

use App\Yantrana\Base\BaseModel;

class ProductCombinationOptionModel extends BaseModel
{
    /**
     * @var  string - The database table used by the model.
     */
    protected $table = 'product_combinations_options';

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
        'product_option_values__id' => 'integer',
        'product_combinations__id' => 'integer',
    ];

    /**
     * @var  array - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * Establish relationship with option label & values data
     *
     * @return information
     *-----------------------------------------------------------------------*/
    public function optionValue()
    {
        return $this->belongsTo(ProductOptionValueModel::class, 'product_option_values__id');
    }
}

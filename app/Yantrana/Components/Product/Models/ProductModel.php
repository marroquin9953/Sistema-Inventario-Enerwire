<?php
/*
* Product.php - Model file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Models;

use App\Yantrana\Base\BaseModel;
use App\Yantrana\Components\TaxPreset\Models\TaxPresetModel;

class ProductModel extends BaseModel
{
    /**
     * @var  string - The database table used by the model.
     */
    protected $table = 'products';

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
        'measure_type' => 'integer',
        'user_authorities__id' => 'integer',
        'categories__id' => 'integer',
        'suppliers__id' => 'integer',
        'price' => 'float',
    ];

    /**
     * @var  array - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * Establish relationship with option label & product data
     *
     * @return information
     *-----------------------------------------------------------------------*/
    public function optionLabels()
    {
        return $this->hasMany(ProductOptionLabelModel::class, 'products__id');
    }

    /**
     * Establish relationship with option combination & product data
     *
     * @return information
     *-----------------------------------------------------------------------*/
    public function combinations()
    {
        return $this->hasMany(ProductCombinationModel::class, 'products__id');
    }

    /**
     * Establish relationship with option tax preset & product data
     *
     * @return information
     *-----------------------------------------------------------------------*/
    public function taxPreset()
    {
        return $this->hasOne(TaxPresetModel::class, '_id', 'tax_presets__id')->with('taxes');
    }
}

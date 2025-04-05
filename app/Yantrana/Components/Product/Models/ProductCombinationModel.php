<?php
/*
* ProductCombinationModel.php - Model file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Models;

use App\Yantrana\Base\BaseModel;
use App\Yantrana\Components\Barcodes\Models\BarcodesModel;
use App\Yantrana\Components\Inventory\Models\StockTransactionModel;
use App\Yantrana\Components\TaxPreset\Models\TaxPresetModel;

class ProductCombinationModel extends BaseModel
{
    /**
     * @var  string - The database table used by the model.
     */
    protected $table = 'product_combinations';

    /**
     * @var  array - The attributes that should be casted to native types.
     */
    protected $casts = [
        '_id' => 'integer',
        'status' => 'integer',
        'products__id' => 'integer',
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
    public function combinationOptions()
    {
        return $this->hasMany(ProductCombinationOptionModel::class, 'product_combinations__id');
    }

    /**
     * Establish relationship with option label & values data
     *
     * @return information
     *-----------------------------------------------------------------------*/
    public function combinationBarcodes()
    {
        return $this->hasMany(BarcodesModel::class, 'product_combinations__id');
    }

    /**
     * Establish relationship with option stock transaction and combination
     *
     * @return information
     *-----------------------------------------------------------------------*/
    public function stockTrasactions()
    {
        return $this->hasMany(StockTransactionModel::class, 'product_combinations__id')->with(['location', 'bill']);
    }

    /**
     * Establish relationship with location
     *
     * @return information
     *-----------------------------------------------------------------------*/
    public function product()
    {
        return $this->hasOne(ProductModel::class, '_id', 'products__id')->with('taxPreset');
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

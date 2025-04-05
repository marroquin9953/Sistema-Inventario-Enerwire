<?php
/*
* StockTransactionModel.php - Model file
*
* This file is part of the Inventory component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Inventory\Models;

use App\Yantrana\Base\BaseModel;
use App\Yantrana\Components\Billing\Models\BillingModel;
use App\Yantrana\Components\Location\Models\LocationModel;
use App\Yantrana\Components\Product\Models\ProductCombinationModel;
use App\Yantrana\Components\Suppliers\Models\SuppliersModel;

class StockTransactionModel extends BaseModel
{
    /**
     * @var  string - The database table used by the model.
     */
    protected $table = 'stock_transactions';

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
        '__data' => 'array',
    ];

    /**
     * Let the system knows Text columns treated as JSON
     *
     * @var array
     *----------------------------------------------------------------------- */
    protected $jsonColumns = [
        '__data' => [
            'tax_details' => 'array',
            'discount_details' => 'array',
            'customer_details' => 'array',
        ],
    ];

    /**
     * @var  array - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * Establish relationship with option stock trancation & product options
     *
     * @return information
     *-----------------------------------------------------------------------*/
    public function stockTransactionProductOptions()
    {
        return $this->hasMany(StockTransactionProductOptionModel::class, 'stock_transactions__id');
    }

    /**
     * Establish relationship with location
     *
     * @return information
     *-----------------------------------------------------------------------*/
    public function productCombination()
    {
        return $this->hasOne(ProductCombinationModel::class, '_id', 'product_combinations__id');
    }

    /**
     * Establish relationship with location
     *
     * @return information
     *-----------------------------------------------------------------------*/
    public function location()
    {
        return $this->hasOne(LocationModel::class, '_id', 'locations__id');
    }

    /**
     * Establish relationship with location
     *
     * @return information
     *-----------------------------------------------------------------------*/
    public function locations()
    {
        return $this->hasMany(LocationModel::class, '_id', 'locations__id');
    }

    /**
     * Establish relationship with supplier
     *
     * @return information
     *-----------------------------------------------------------------------*/
    public function supplier()
    {
        return $this->hasOne(SuppliersModel::class, '_id', 'suppliers__id');
    }

    /**
     * Establish relationship with supplier
     *
     * @return information
     *-----------------------------------------------------------------------*/
    public function bill()
    {
        return $this->hasOne(BillingModel::class, '_id', 'bills__id');
    }
}

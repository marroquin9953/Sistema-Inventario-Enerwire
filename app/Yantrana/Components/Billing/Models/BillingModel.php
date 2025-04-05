<?php
/*
* Billing.php - Model file
*
* This file is part of the Billing component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Billing\Models;

use App\Yantrana\Base\BaseModel;
use App\Yantrana\Components\Inventory\Models\StockTransactionModel;

class BillingModel extends BaseModel
{
    /**
     * @var  string - The database table used by the model.
     */
    protected $table = 'bills';

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
     * Establish relationship with option label & values data
     *
     * @return information
     *-----------------------------------------------------------------------*/
    public function stockTransactions()
    {
        return $this->hasMany(StockTransactionModel::class, 'bills__id')->with('location');
    }
}

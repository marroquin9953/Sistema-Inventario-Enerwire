<?php
/*
* ProductOptionValueModel.php - Model file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Models;

use App\Yantrana\Base\BaseModel;

class ProductOptionValueModel extends BaseModel
{
    /**
     * @var  string - The database table used by the model.
     */
    protected $table = 'product_option_values';

    /**
     * @var  array - The attributes that should be casted to native types.
     */
    protected $casts = [
        '_id' => 'integer',
        'status' => 'integer',
        'product_option_labels__id' => 'integer',
        'user_authorities__id' => 'integer',
        'addon_price' => 'float',
    ];

    /**
     * @var  array - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * Establish relationship with product Option Label
     *
     * @return information
     *-----------------------------------------------------------------------*/
    public function productOptionLabel()
    {
        return $this->hasOne(ProductOptionLabelModel::class, '_id', 'product_option_labels__id');
    }
}

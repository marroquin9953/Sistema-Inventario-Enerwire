<?php
/*
* SuppliersListRequest.php - Request file
*
* This file is part of the Suppliers component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Suppliers\Requests;

use App\Yantrana\Base\BaseRequest;

class SuppliersAddRequest extends BaseRequest
{
    /**
     * Loosely sanitize fields.
     *------------------------------------------------------------------------ */
    protected $looseSanitizationFields = [
        'name' => '',
        'short_description' => '',
    ];

    /**
     * Authorization for request.
     *
     * @return  bool
     *-----------------------------------------------------------------------*/
    public function authorize()
    {
        return true;
    }

    /**
     * Validation rules.
     *
     * @return  bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        return [
            'name' => 'required|max:150',
            'short_description' => 'max:255',
        ];
    }
}

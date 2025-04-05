<?php
/*
* TaxpresetListRequest.php - Request file
*
* This file is part of the TaxPreset component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\TaxPreset\Requests;

use App\Yantrana\Base\BaseRequest;

class TaxpresetAddRequest extends BaseRequest
{
    /**
     * Set if you need form request secured.
     *------------------------------------------------------------------------ */
    protected $securedForm = true;

    /**
     * Unsecured/Unencrypted form fields.
     *------------------------------------------------------------------------ */
    protected $unsecuredFields = [];

    /**
     * Loosely sanitize fields.
     *------------------------------------------------------------------------ */
    protected $looseSanitizationFields = [
        'title' => '',
        'description' => '',
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
            'title' => 'required|max:150|min:3',
            'description' => 'max:250',
        ];
    }
}

<?php
/*
* CustomerListRequest.php - Request file
*
* This file is part of the Customer component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Customer\Requests;

use App\Yantrana\Base\BaseRequest;

class CustomerEditRequest extends BaseRequest
{
    /**
     * Loosely sanitize fields.
     *------------------------------------------------------------------------ */
    protected $looseSanitizationFields = ['description' => ''];

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
        ];
    }
}

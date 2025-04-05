<?php
/*
* LocationListRequest.php - Request file
*
* This file is part of the Location component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Location\Requests;

use App\Yantrana\Base\BaseRequest;

class LocationAddRequest extends BaseRequest
{
    /**
     * Loosely sanitize fields.
     *------------------------------------------------------------------------ */
    protected $looseSanitizationFields = ['short_description' => ''];

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
            'name' => 'required|max:85',
            'location_id' => 'required|max:45|unique:locations,location_id',
            'short_description' => 'max:252',
        ];
    }
}

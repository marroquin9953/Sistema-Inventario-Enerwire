<?php
/*
* LocationListRequest.php - Request file
*
* This file is part of the Location component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Location\Requests;

use App\Yantrana\Base\BaseRequest;
use Request;

class LocationEditRequest extends BaseRequest
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
        $locationId = Request::route()->parameter('locationIdOrUid');

        return [
            'name' => 'required|max:85',
            'location_id' => 'required|max:45|unique:locations,location_id,'.$locationId.',_uid',
            'short_description' => 'max:252',
        ];
    }
}

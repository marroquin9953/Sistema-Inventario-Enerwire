<?php
/*
* AssignLocationRequest.php - Request file
*
* This file is part of the Location component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Location\Requests;

use App\Yantrana\Base\BaseRequest;

class AssignLocationRequest extends BaseRequest
{
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
            'locations' => 'required',
        ];
    }
}

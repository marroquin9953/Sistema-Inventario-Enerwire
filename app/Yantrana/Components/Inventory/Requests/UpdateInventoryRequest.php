<?php
/*
* UpdateInventoryRequest.php - Request file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Inventory\Requests;

use App\Yantrana\Base\BaseRequest;

class UpdateInventoryRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the user register request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        return [
            'quantity' => 'required|numeric|min:1',
            'sub_type' => 'required',
            'location' => 'required',
        ];
    }
}

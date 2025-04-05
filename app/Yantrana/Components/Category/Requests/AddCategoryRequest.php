<?php
/*
* AddCategoryRequest.php - Request file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Category\Requests;

use App\Yantrana\Base\BaseRequest;

class AddCategoryRequest extends BaseRequest
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
            'name' => 'required|max:45',
        ];
    }

    /**
     * Get the validation messages
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function messages()
    {
        return [
            'name.required' => 'The category name field is required',
        ];
    }
}

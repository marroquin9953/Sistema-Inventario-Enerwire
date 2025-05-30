<?php
/*
* AddUserRequest.php - Request file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User\Requests;

use App\Yantrana\Base\BaseRequest;

class AddUserRequest extends BaseRequest
{
    /**
     * Secure form.
     *------------------------------------------------------------------------ */
    protected $securedForm = true;

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
            'first_name' => 'required|min:2|max:45',
            'last_name' => 'required|min:2|max:45',
            'role' => 'sometimes|required',
            'username' => 'required|min:2|max:45|unique:users,username',
            'email' => 'sometimes|email|unique_email|max:45',
            'password' => 'required|min:6|max:255',
            'password_confirmation' => 'required|min:6|max:255|same:password',
        ];
    }
}

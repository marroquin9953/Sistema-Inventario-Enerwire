<?php

/*
* ConfigurationRequest.php - Request file
*
* This file is part of the Configuration component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Configuration\Requests;

use App\Yantrana\Base\BaseRequest;
use Illuminate\Http\Request;

class ConfigurationRequest extends BaseRequest
{
    /**
     * Set if you need form request secured.
     *------------------------------------------------------------------------ */
    protected $securedForm = true;

    /**
     * Unsecured/Unencrypted form fields.
     *------------------------------------------------------------------------ */
    protected $unsecuredFields = [
        'logoURL',
        'faviconURL',
        'addtional_page_end_content',
        'currency_symbol',
        'currency_format',
    ];

    /**
     * Loosely sanitize fields.
     *------------------------------------------------------------------------ */
    protected $looseSanitizationFields = [];

    /**
     * Authorization for request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function authorize()
    {
        return true;
    }

    /**
     * Validation rules.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $rules = [
            'name' => 'sometimes|required|min:2|max:30',
            'favicon_image' => 'sometimes|required',
            'timezone' => 'sometimes|required',
            'business_email' => 'sometimes|required|email',
            'test_case_id_format' => 'sometimes|required|verify_id_format',
            'requirement_id_format' => 'sometimes|required|verify_id_format',
            'issue_id_format' => 'sometimes|required|verify_id_format',
            'test_plan_id_format' => 'sometimes|required|verify_id_format',
            'id_format_middle_digits' => 'sometimes|required|verify_number_format',
            // For currency
            'currency' => 'sometimes|required',
            'currency_symbol' => 'sometimes|required',
            'currency_format' => 'sometimes|required|verify_format',
            'currency_value' => 'sometimes|required|alpha',
            'currency_markup' => 'sometimes|min:0',
        ];

        return $rules;
    }

    /**
     * Set custom msg for field
     *
     * @return array
     *-----------------------------------------------------------------------*/
    public function messages()
    {
        return [
            'currency_format.verify_format' => 'Invalid currency format.',
        ];
    }
}

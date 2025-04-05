<?php
/*
* ProductListRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use App\Yantrana\Base\BaseRequest;

class ProductAddRequest extends BaseRequest
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
        $inputData = $this->all();
        $rules = [
            'name' => 'required|max:255',
            'category_id' => 'required',
            'short_description' => 'max:255',
        ];

        if (! __isEmpty($inputData['optionLabels'])) {
            foreach ($inputData['optionLabels'] as $optionLabelKey => $optionLabel) {
                $rules['optionLabels.'.$optionLabelKey.'.title'] = 'required';
                $rules['optionLabels.'.$optionLabelKey.'.product_id'] = 'required|alpha_dash|unique:product_combinations,product_id,NULL,products__id|different_array';

                $rules['optionLabels.'.$optionLabelKey.'.price'] = 'required';
                $rules['optionLabels.'.$optionLabelKey.'.selling_price'] = 'required';

                /* $rules['optionLabels.'.$optionLabelKey.'.barcodes'] = "required|unique:barcodes,barcode|check_unique_in_input"; */

                foreach ($optionLabel['barcodes'] as $barcodeValueKey => $code) {
                    if (! __isEmpty($code)) {
                        $rules['optionLabels.'.$optionLabelKey.'.barcodes'] = 'required|unique:barcodes,barcode|unique_barcode';
                    }
                }

                foreach ($optionLabel['values'] as $optionValueKey => $optionValue) {
                    if (! __isEmpty($optionValue['value_name'])) {
                        $rules['optionLabels.'.$optionLabelKey.'.values.'.$optionValueKey.'.label_name'] = 'required';
                    }

                    if (! __isEmpty($optionValue['label_name'])) {
                        $rules['optionLabels.'.$optionLabelKey.'.values.'.$optionValueKey.'.value_name'] = 'required';
                    }
                }
            }
        }

        return $rules;
    }

    /**
     * Set custom msg for field
     *
     * @return array
     *-----------------------------------------------------------------------*/
    public function messages()
    {
        $messages = [];

        $inputData = $this->all();

        if (! __isEmpty($inputData['optionLabels'])) {
            foreach ($inputData['optionLabels'] as $optionLabelKey => $optionLabel) {
                $dynamicKey = 'optionLabels.'.$optionLabelKey;

                $messages[$dynamicKey.'.title.required'] = 'The title field is required.';
                $messages[$dynamicKey.'.price.required'] = 'The purchase price field is required.';
                $messages[$dynamicKey.'.selling_price.required'] = 'The sell price field is required.';

                $messages[$dynamicKey.'.product_id.required'] = 'The product id / sku field is required.';
                $messages[$dynamicKey.'.product_id.alpha_dash'] = 'The product id / sku may only contain letters, numbers, dashes and underscores.';
                $messages[$dynamicKey.'.product_id.unique'] = 'The product id / sku has already been taken.';

                $messages[$dynamicKey.'.product_id.different_array'] = __tr('The __attribute__ must be different.', [
                    '__attribute__' => $optionLabel['product_id'],
                ]);

                foreach ($optionLabel['barcodes'] as $barcodeValueKey => $code) {
                    // __dd($optionLabel['barcodes']);
                    if (! __isEmpty($code)) {
                        $messages['optionLabels.'.$barcodeValueKey.'.barcodes.unique'] = __tr('The __attribute__ has already been taken.', [
                            '__attribute__' => implode(',', $optionLabel['barcodes']),
                        ]);
                    }
                }

                foreach ($optionLabel['values'] as $optionValueKey => $optionValue) {
                    if (! __isEmpty($optionValue['value_name'])) {
                        $messages['optionLabels.'.$optionLabelKey.'.values.'.$optionValueKey.'.label_name.required'] = __tr('The label field is required.');
                    }

                    if (! __isEmpty($optionValue['label_name'])) {
                        $messages['optionLabels.'.$optionLabelKey.'.values.'.$optionValueKey.'.value_name.required'] = __tr('The value field is required.');
                    }
                }
            }
        }

        return $messages;
    }
}

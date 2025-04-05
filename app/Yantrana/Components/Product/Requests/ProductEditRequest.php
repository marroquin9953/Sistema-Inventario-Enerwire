<?php
/*
* ProductListRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use App\Yantrana\Base\BaseRequest;
use Request;

class ProductEditRequest extends BaseRequest
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
        $productIdOrUid = $inputData['_id']; //$this->route('productIdOrUid');
        $rules = [
            'name' => 'required|max:255',
            'category_id' => 'required',
            'short_description' => 'max:255',
        ];

        // check if options labels in available
        if (! __isEmpty($inputData['optionLabels'])) {
            foreach ($inputData['optionLabels'] as $optionLabelKey => $optionLabel) {
                $optionLabelId = isset($optionLabel['_id']) ? $optionLabel['_id'] : '';

                $rules['optionLabels.'.$optionLabelKey.'.title'] = 'required';
                $rules['optionLabels.'.$optionLabelKey.'.product_id'] = 'required|alpha_dash|unique:product_combinations,product_id,'.$optionLabelId.',_id';

                /*  $rules['optionLabels.'.$optionLabelKey.'.barcode'] = "required|unique:product_combinations,barcode,".$optionLabelId.",_id"; */

                $rules['optionLabels.'.$optionLabelKey.'.price'] = 'required';
                $rules['optionLabels.'.$optionLabelKey.'.selling_price'] = 'required';
                $rules['optionLabels.'.$optionLabelKey.'.barcodes'] = 'required';

                if (__isEmpty($optionLabel['barcodes'])) {
                    $rules['optionLabels.'.$optionLabelKey.'.barcodes'] = 'required';
                }

                /*$barcodeRules = [];
                foreach ($optionLabel['barcodes'] as $barcodeValueKey => $code) {
                    $barcodeRules = "unique:barcodes,barcode,".$optionLabelId.",product_combinations__id|unique_barcode";
                }

                $rules['optionLabels.'.$optionLabelKey.'.barcodes'] = $barcodeRules;*/

                $rules['optionLabels.'.$optionLabelKey.'.barcodes'] = 'check_unique_barcode:'.$optionLabelId;

                foreach ($optionLabel['values'] as $optionValueKey => $optionValue) {
                    if (isset($optionValue['value_name']) and ! __isEmpty($optionValue['value_name'])) {
                        $rules['optionLabels.'.$optionLabelKey.'.values.'.$optionValueKey.'.label_name'] = 'required';
                    }

                    if (isset($optionValue['label_name']) and ! __isEmpty($optionValue['label_name'])) {
                        $rules['optionLabels.'.$optionLabelKey.'.values.'.$optionValueKey.'.value_name'] = 'required';
                    }
                    // $rules['optionLabels.'.$optionLabelKey.'.values.'.$optionValueKey.'.value_name'] = "required";
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

                $barcodesValues = [];
                // foreach ($optionLabel['barcodes'] as $barcodeValueKey => $code)
                // {
                //     if (!__isEmpty($code)) {
                //         $messages['optionLabels.'.$barcodeValueKey.'.barcodes.unique'] = __tr('The __attribute__ has already been taken.', [
                //             '__attribute__' => implode($optionLabel['barcodes'], ',')
                //         ]);
                //     }
                // }

                $messages[$dynamicKey.'.barcodes.check_unique_barcode'] = __tr('The barcode has already been taken.');

                foreach ($optionLabel['values'] as $optionValueKey => $optionValue) {
                    // $messages['optionLabels.'.$optionLabelKey.'.values.'.$optionValueKey.'.value_name.required'] = __tr('The value field is required.');
                    if (isset($optionValue['value_name']) and ! __isEmpty($optionValue['value_name'])) {
                        $messages['optionLabels.'.$optionLabelKey.'.values.'.$optionValueKey.'.label_name.required'] = __tr('The label field is required.');
                    }

                    if (isset($optionValue['label_name']) and ! __isEmpty($optionValue['label_name'])) {
                        $messages['optionLabels.'.$optionLabelKey.'.values.'.$optionValueKey.'.value_name.required'] = __tr('The value field is required.');
                    }
                }
            }
        }

        return $messages;
    }
}

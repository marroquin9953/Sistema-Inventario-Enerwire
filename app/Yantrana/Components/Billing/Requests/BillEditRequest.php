<?php
/*
* BillEditRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Billing\Requests;

use App\Yantrana\Base\BaseRequest;
use Illuminate\Validation\Rule;

class BillEditRequest extends BaseRequest
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
        $inputData = $this->all();
        $billId = $this->route('billId');

        $rules = [
            'customer' => 'required',
            'bill_number' => [
                'required',
                Rule::unique('bills')->where(function ($query) {
                    return $query;
                })->ignore($billId, '_uid'),
            ],
        ];

        /*if (!__isEmpty($inputData['optionLabels'])) {
            foreach ($inputData['optionLabels'] as $optionLabelKey => $optionLabel) {
                $rules['optionLabels.'.$optionLabelKey.'.title'] = "required";
                $rules['optionLabels.'.$optionLabelKey.'.product_id'] = "required|alpha_dash|unique:product_combinations,product_id,NULL,products__id|different_array";

                $rules['optionLabels.'.$optionLabelKey.'.price'] = "required";
                $rules['optionLabels.'.$optionLabelKey.'.selling_price'] = "required";

                 $rules['optionLabels.'.$optionLabelKey.'.barcodes'] = "required|unique:barcodes,barcode|check_unique_in_input";

                foreach ($optionLabel['barcodes'] as $barcodeValueKey => $code)
                {
                    if (!__isEmpty($code)) {
                        $rules['optionLabels.'.$optionLabelKey.'.barcodes'] = "required|unique:barcodes,barcode|unique_barcode";
                   }
                }

                foreach ($optionLabel['values'] as $optionValueKey => $optionValue) {
                    if (!__isEmpty($optionValue['value_name'])) {
                        $rules['optionLabels.'.$optionLabelKey.'.values.'.$optionValueKey.'.label_name'] = "required";
                   }
                }

            }
        } */

        return $rules;
    }
}

<?php

/**
 * Custom validation rules for check unique email address -
 * for user.
 *
 * @return bool
 *---------------------------------------------------------------- */
Validator::extend('unique_email', function ($attribute, $value, $parameters) {
    $email = strtolower($value);
    $userCount = App\Yantrana\Components\User\Models\User::where('email', $email)
        ->get()
        ->count();

    // Check for user exist with given email
    if ($userCount > 0) {
        return false;
    }

    $newEmailRequestCount = App\Yantrana\Components\User\Models\EmailChangeRequest::where('new_email', $email)
        ->count();
    // Check for new email request exist with given email
    if ($newEmailRequestCount > 0) {
        return false;
    }

    return true;
});

/**
 * Custom validation rules for check unique email address -
 * for user.
 *
 * @return bool
 *---------------------------------------------------------------- */
Validator::extend('unique_in_email_change_request', function ($attribute, $value, $parameters) {
    $email = strtolower($value);

    $newEmailRequestCount = App\Yantrana\Components\User\Models\EmailChangeRequest::where('new_email', $email)->count();
    // Check for new email request exist with given email
    if ($newEmailRequestCount > 0) {
        return false;
    }

    return true;
});

/**
 * Custom validation rules for check verify currency format -
 *
 * {__currencySymbol__}{__amount__} {__currencyCode__} this is format contains
 *
 * take reference from - config('__settings.items.currency_format.default')
 *
 * @return bool
 *---------------------------------------------------------------- */
Validator::extend('verify_format', function ($attribute, $value, $parameters) {
    $condition = false;

    if (str_contains($value, '{__amount__}')) {
        $condition = true;
    }

    // Check if currency symbol exist only one time in string
    if (
        substr_count($value, '{__currencySymbol__}') > 1
        or substr_count($value, '{__amount__}') > 1
        or substr_count($value, '{__currencyCode__}') > 1
    ) {
        $condition = false;
    }

    return $condition;
});

/**
 * verify number format -
 * for user.
 *
 * @return bool
 *---------------------------------------------------------------- */
Validator::extend('verify_number_format', function ($attribute, $value, $parameters) {
    $condition = false;

    if ($value >= 1 && $value <= 10) {
        $condition = true;
    }

    return $condition;
});

/**
 * verify number format -
 * for user.
 *
 * @return bool
 *---------------------------------------------------------------- */
Validator::extend('different_array', function ($attribute, $value, $parameters) {
    $inputData = request()->all();

    $productSkuIds = [];
    foreach ($inputData['optionLabels'] as $optionLabelKey => $optionLabel) {
        if (! in_array($optionLabel['product_id'], $productSkuIds)) {
            $productSkuIds[] = $optionLabel['product_id'];
        } else {
            return false;
        }
    }

    return true;
});

/**
 * verify number format -
 * for user.
 *
 * @return bool
 *---------------------------------------------------------------- */
Validator::extend('unique_barcode', function ($attribute, $value, $parameters) {
    $inputData = request()->all();

    $barcodesData = [];

    foreach ($inputData['optionLabels'] as $optionLabelKey => $optionLabel) {
        foreach ($optionLabel['barcodes'] as $barcodeValueKey => $code) {
            if (! in_array($code, $barcodesData)) {
                $barcodes[] = $code;
            } else {
                return false;
            }
        }
    }

    return true;
});

/**
 * Custom validation rules for check unique email address -
 * for user.
 *
 * @return bool
 *---------------------------------------------------------------- */
Validator::extend('check_unique_barcode', function ($attribute, $addedBarcodes, $parameters, $validator) {
    // __dd($attribute, $value, $parameters, $validator->getData());

    $productCombinationId = $parameters[0];

    $barcodes = App\Yantrana\Components\Barcodes\Models\BarcodesModel::whereIn('barcode', $addedBarcodes)
        ->get();
    // $barcodeExistInDB = $barcodes->pluck('barcode')->toArray();
    $barcodeExists = [];

    if (! __isEmpty($addedBarcodes)) {
        foreach ($addedBarcodes as $key => $addedBarcode) {
            foreach ($barcodes as $key2 => $barcode) {
                if ($barcode->barcode == $addedBarcode and $productCombinationId != $barcode->product_combinations__id) {
                    $barcodeExists[] = $addedBarcode;
                }
            }
        }
    }

    if (! __isEmpty($barcodeExists)) {
        return false;
    }

    return true;
});

/**
 * verify recaptcha -
 * for user.
 *
 * @return bool
 *---------------------------------------------------------------- */
Validator::extend('recaptcha', 'App\\Yantrana\\Services\\ReCaptcha\\ReCaptcha@validate');

<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// verify installation
Route::get('/app-configuration', [
    'as' => 'installation.verify',
    'uses' => 'Installation\InstallationVerification@verify',
]);

Route::get('/error-not-found', [
    'as' => 'error.public-not-found',
    'uses' => '__Igniter@errorNotFound',
]);

// get all application template using this route
Route::get('/get-template/{viewName}', [
    'as' => 'template.get',
    'uses' => '__Igniter@getTemplate',
]);

// captcha generate url
Route::get('/generate-captcha', [
    'as' => 'security.captcha',
    'uses' => '__Igniter@captcha',
]);

// get all application template using this route
Route::get('/email-view/{viewName}', [
    'as' => 'template.email',
    'uses' => '__Igniter@emailTemplate',
]);

// Change Theme Color
Route::get('/{colorName}/change-theme-color', [
    'as' => 'theme_color',
    'uses' => '__Igniter@changeThemeColor',
]);

/*
    User Components Public Section Related Routes
    ----------------------------------------------------------------------- */
Route::group([
    'namespace' => 'User\Controllers',
    'prefix' => 'user',
], function () {
    // contact form
    Route::get('/contact', [
        'as' => 'get.user.contact',
        'uses' => 'UserController@contact',
    ]);

    // process contact form
    Route::post('/post-contact', [
        'as' => 'user.contact.process',
        'uses' => 'UserController@contactProcess',
    ]);
});

Route::get('', [
    'as' => 'manage.app',
    'uses' => '__Igniter@manageIndex',
]);

Route::group(['middleware' => 'auth'], function () {
    Route::group([
        'namespace' => 'User\Controllers',
        'prefix' => 'user',
    ], function () {
        // profile
        Route::get('/profile', [
            'as' => 'user.profile',
            'uses' => 'UserController@profile',
        ]);

        // change password
        Route::get('/change-password', [
            'as' => 'user.change_password',
            'uses' => 'UserController@changePassword',
        ]);

        // new email activation
        Route::get('/{userID}/{activationKey}/new-email-activation', [
            'as' => 'user.new_email.activation',
            'uses' => 'UserController@newEmailActivation',
        ]);

        // profile edit support data
        Route::get('/get-country-list', [
            'as' => 'user.get.country_list',
            'uses' => 'UserController@getCountries',
        ]);

        // user country add
        Route::post('/add/country', [
            'as' => 'user.add.country.process',
            'uses' => 'UserController@addCountry',
        ]);
    });
});

/*
      Start After Authentication Accessible Routes
      ---------------------------------------------------------------------- */

Route::group(['middleware' => 'authority.checkpost'], function () {
    /*
          Start Inventory Components Manage Section Related Routes
        ------------------------------------------------------------------- */
    Route::group([
        'namespace' => 'Inventory\Controllers',
        'prefix' => '/inventory',
    ], function () {
        // Inventory list
        Route::get('/list', [
            'as' => 'manage.inventory.read.list',
            'uses' => 'InventoryController@prepareInventoryList',
        ]);

        // Inventory update data
        Route::get('/{productId}/{combinationId}/{locationId}/{supplierId}/get-inventory-update-data', [
            'as' => 'manage.inventory.read.update_data',
            'uses' => 'InventoryController@getInventoryUpdateData',
        ]);

        // Get Product Combinations
        Route::get('/{productId}/get-product-combinations', [
            'as' => 'manage.inventory.read.product_combination',
            'uses' => 'InventoryController@getProductCombination',
        ]);

        // Calculate options price
        Route::get('/{productId}/{combinationId}/{locationId}/{supplierId}/{type}/calculate-options-quantity', [
            'as' => 'manage.inventory.write.calculate_options_quantity',
            'uses' => 'InventoryController@calculateOptionsQuantity',
        ]);

        // Update Inventory Process
        Route::post('/{productId}/inventory-update-process', [
            'as' => 'manage.inventory.write.update',
            'uses' => 'InventoryController@updateInventory',
        ]);

        // Inventory transaction data
        Route::get('/{productId}/{combinationId}/{tranType}/{locationId}/get-inventory-transaction-data', [
            'as' => 'manage.inventory.read.transaction_data',
            'uses' => 'InventoryController@getInventoryTransactionData',
        ]);
    });

    /*
          Start Location Components Manage Section Related Routes
        ------------------------------------------------------------------- */
    Route::group([
        'namespace' => 'Location\Controllers',
        'prefix' => '/location',
    ], function () {
        // Location list
        Route::get('/list', [
            'as' => 'manage.location.read.list',
            'uses' => 'LocationController@prepareLocationList',
        ]);

        // Location delete process
        Route::post('/{locationIdOrUid}/delete-process', [
            'as' => 'manage.location.write.delete',
            'uses' => 'LocationController@processLocationDelete',
        ]);

        // Location create support data
        Route::get('/add-support-data', [
            'as' => 'manage.location.read.support_data',
            'uses' => 'LocationController@prepareLocationSupportData',
        ]);

        // Location create process
        Route::post('/add-process', [
            'as' => 'manage.location.write.create',
            'uses' => 'LocationController@processLocationCreate',
        ]);

        // Location get the data
        Route::get('/{locationIdOrUid}/get-update-data', [
            'as' => 'manage.location.read.update.data',
            'uses' => 'LocationController@updateLocationData',
        ]);

        // Location update process
        Route::post('/{locationIdOrUid}/update-process', [
            'as' => 'manage.location.write.update',
            'uses' => 'LocationController@processLocationUpdate',
        ]);

        // get assign Location to user
        Route::get('/{userAuthorityId}/get-assign-location-data', [
            'as' => 'manage.location.read.assign_location',
            'uses' => 'LocationController@getAssignLocationData',
        ]);

        // Location Assign process
        Route::post('/{userAuthorityId}/location-assign-process', [
            'as' => 'manage.location.write.location_assign_process',
            'uses' => 'LocationController@processAssignLocation',
        ]);

        // get assign user
        Route::get('/{locationId}/get-assign-user-data', [
            'as' => 'manage.location.read.assign_user',
            'uses' => 'LocationController@getAssignUserData',
        ]);

        // User Assign process
        Route::post('/{locationId}/user-assign-process', [
            'as' => 'manage.location.write.user_assign_process',
            'uses' => 'LocationController@processAssignUser',
        ]);
    });

    /*
          Start Billing Components Manage Section Related Routes
        ------------------------------------------------------------------- */
    Route::group([
        'namespace' => 'Billing\Controllers',
        'prefix' => '/billing',
    ], function () {
        // Billing list
        Route::get('/list', [
            'as' => 'manage.billing.read.list',
            'uses' => 'BillingController@prepareBillingList',
        ]);

        // Billing Data
        Route::get('/add-support-data', [
            'as' => 'manage.billing.read.add_support_data',
            'uses' => 'BillingController@getAddSupportData',
        ]);

        // Billing edit support data
        Route::get('/{billUid}/edit-support-data', [
            'as' => 'manage.billing.read.edit_support_data',
            'uses' => 'BillingController@getEditSupportData',
        ]);

        // Billing details support data
        Route::get('/{billUid}/details-support-data', [
            'as' => 'manage.billing.read.details_support_data',
            'uses' => 'BillingController@getDetailSupportData',
        ]);

        // Get Search Term Product Combination
        Route::get('/{searchTerm}/search-combination-for-bill', [
            'as' => 'manage.billing.read.search_combination_for_bill',
            'uses' => 'BillingController@getSearchCombinationData',
        ]);

        // Get   Combination location wise
        Route::get('/{combinationId}/{productId}/fetch-combinations-location', [
            'as' => 'manage.billing.read.combinations_locationwise',
            'uses' => 'BillingController@getCombinationLocationWise',
        ]);

        // Store Billing
        Route::post('/process-store-bill', [
            'as' => 'manage.billing.write.store_bill',
            'uses' => 'BillingController@storeProductBill',
        ]);

        // UpdateBill
        Route::post('/{billId}/process-update-bill', [
            'as' => 'manage.billing.write.update_bill',
            'uses' => 'BillingController@updateProductBill',
        ]);

        // Print Bill
        Route::get('/{billId}/print-bill', [
            'as' => 'manage.billing.read.print_bill',
            'uses' => 'BillingController@printBill',
        ]);

        // Download as PDf
        Route::get('/{billId}/download-pdf', [
            'as' => 'manage.billing.read.download_pdf',
            'uses' => 'BillingController@downloadPdf',
        ]);

        // delete bill
        Route::post('/{billId}/delete-bill-process', [
            'as' => 'manage.billing.write.delete',
            'uses' => 'BillingController@billDeleteProcess',
        ]);

        // delete bill
        Route::post('/{billId}/{transactionId}/delete-bill-transaction-process', [
            'as' => 'manage.billing.write.delete_transaction',
            'uses' => 'BillingController@deleteStockTransaction',
        ]);
    });

    /*
          Start Product Components Manage Section Related Routes
        ------------------------------------------------------------------- */
    Route::group([
        'namespace' => 'Product\Controllers',
        'prefix' => '/product',
    ], function () {
        // Product list
        Route::get('/list', [
            'as' => 'manage.product.read.list',
            'uses' => 'ProductController@prepareProductList',
        ]);

        // Product delete process
        Route::post('/{productIdOrUid}/delete-process', [
            'as' => 'manage.product.write.delete',
            'uses' => 'ProductController@processProductDelete',
        ]);

        // Product create support data
        Route::get('/add-support-data', [
            'as' => 'manage.product.read.support_data',
            'uses' => 'ProductController@prepareProductSupportData',
        ]);

        // Product create process
        Route::post('/add-process', [
            'as' => 'manage.product.write.create',
            'uses' => 'ProductController@processProductCreate',
        ]);

        // Product get the data
        Route::get('/{productIdOrUid}/get-update-data', [
            'as' => 'manage.product.read.update.data',
            'uses' => 'ProductController@updateProductData',
        ]);

        // Product get the data
        Route::get('/{productIdOrUid}/get-details', [
            'as' => 'manage.product.read.get_details',
            'uses' => 'ProductController@getDetails',
        ]);

        // Product update process
        Route::post('/{productIdOrUid}/update-process', [
            'as' => 'manage.product.write.update',
            'uses' => 'ProductController@processProductUpdate',
        ]);

        // Product Option combination delete process
        Route::post('/{productId}/{combinationId}/combination-value-delete-process', [
            'as' => 'manage.product.combination.write.delete',
            'uses' => 'ProductController@processProductCombinationDelete',
        ]);

        // Product Option value delete process
        Route::post('/{productId}/{comboId}/{valueId}/option-value-delete-process', [
            'as' => 'manage.product.value.write.delete',
            'uses' => 'ProductController@processProductOptionValueDelete',
        ]);
    });

    /*
          Start Barcodes Components Manage Section Related Routes
        ------------------------------------------------------------------- */
    Route::group([
        'namespace' => 'Barcodes\Controllers',
        'prefix' => '/barcode',
    ], function () {
        // Product list
        Route::post('/process-delete', [
            'as' => 'manage.barcode.write.delete',
            'uses' => 'BarcodesController@delete',
        ]);
    });
    /*
          End Barcode Components Manage Section Related Routes
        ------------------------------------------------------------------- */

    /*
          Start Suppliers Components Manage Section Related Routes
        ------------------------------------------------------------------- */
    Route::group([
        'namespace' => 'Suppliers\Controllers',
        'prefix' => '/suppliers',
    ], function () {
        // Suppliers list
        Route::get('/list', [
            'as' => 'manage.suppliers.read.list',
            'uses' => 'SuppliersController@prepareSuppliersList',
        ]);

        // Suppliers delete process
        Route::post('/{suppliersIdOrUid}/delete-process', [
            'as' => 'manage.suppliers.write.delete',
            'uses' => 'SuppliersController@processSuppliersDelete',
        ]);

        // Suppliers create process
        Route::post('/add-process', [
            'as' => 'manage.suppliers.write.create',
            'uses' => 'SuppliersController@processSuppliersCreate',
        ]);

        // Suppliers get the data
        Route::get('/{suppliersIdOrUid}/get-update-data', [
            'as' => 'manage.suppliers.read.update.data',
            'uses' => 'SuppliersController@updateSuppliersData',
        ]);

        // Suppliers update process
        Route::post('/{suppliersIdOrUid}/update-process', [
            'as' => 'manage.suppliers.write.update',
            'uses' => 'SuppliersController@processSuppliersUpdate',
        ]);
    });

    /*
          Start Customer Components Manage Section Related Routes
        ------------------------------------------------------------------- */

    Route::group([
        'namespace' => 'Customer\Controllers',
        'prefix' => '/customer',
    ], function () {
        // Customer list
        Route::get('/list', [
            'as' => 'manage.customer.read.list',
            'uses' => 'CustomerController@prepareCustomerList',
        ]);

        // Customer delete process
        Route::post('/{customerIdOrUid}/delete-process', [
            'as' => 'manage.customer.write.delete',
            'uses' => 'CustomerController@processCustomerDelete',
        ]);

        // Customer create support data
        Route::get('/add-support-data', [
            'as' => 'manage.customer.read.support_data',
            'uses' => 'CustomerController@prepareCustomerSupportData',
        ]);

        // Customer create process
        Route::post('/add-process', [
            'as' => 'manage.customer.write.create',
            'uses' => 'CustomerController@processCustomerCreate',
        ]);

        // Customer get the data
        Route::get('/{customerIdOrUid}/get-update-data', [
            'as' => 'manage.customer.read.update.data',
            'uses' => 'CustomerController@updateCustomerData',
        ]);

        // Customer update process
        Route::post('/{customerIdOrUid}/update-process', [
            'as' => 'manage.customer.write.update',
            'uses' => 'CustomerController@processCustomerUpdate',
        ]);
    });

    /*
          Start Report Components Manage Section Related Routes
        ------------------------------------------------------------------- */
    Route::group([
        'namespace' => 'Report\Controllers',
        'prefix' => '/report',
    ], function () {
        // Report list
        Route::get('/list/{start}/{end}/{subtype}/{locations}', [
            'as' => 'manage.report.read.list',
            'uses' => 'ReportController@prepareReportList',
        ]);

        // Report Support list
        Route::get('/support-data', [
            'as' => 'manage.report.read.support_data',
            'uses' => 'ReportController@supportData',
        ]);
    });
    /*
          End Report Components Manage Section Related Routes
        ------------------------------------------------------------------- */

    /*
          Start Category Components Manage Section Related Routes
        ------------------------------------------------------------------- */

    Route::group([
        'namespace' => 'Category\Controllers',
        'prefix' => 'category',
    ], function () {
        // get list of category
        Route::get('/list', [
            'as' => 'manage.category.read.list',
            'uses' => 'CategoryController@index',
        ]);

        // add new category
        Route::post('/add-process', [
            'as' => 'manage.category.write.create',
            'uses' => 'CategoryController@addProcess',
        ]);

        // add new category from add product
        Route::post('/add-from-product-process', [
            'as' => 'manage.category.write.create_from_product',
            'uses' => 'CategoryController@addFromProductProcess',
        ]);

        // get category update data
        Route::get('/{categoryId}/edit-support-data', [
            'as' => 'manage.category.read.update_data',
            'uses' => 'CategoryController@getUpdateData',
        ]);

        // update category
        Route::post('/{categoryId}/update-process', [
            'as' => 'manage.category.write.update',
            'uses' => 'CategoryController@updateProcess',
        ]);

        // delete category
        Route::post('/{categoryId}/delete-process', [
            'as' => 'manage.category.write.delete',
            'uses' => 'CategoryController@deleteProcess',
        ]);
    });

    /*
          Start Tax Preset  Components Manage Section Related Routes
        ------------------------------------------------------------------- */

    Route::group([
        'namespace' => 'TaxPreset\Controllers',
        'prefix' => '/tax-preset',
    ], function () {
        // TaxPreset list
        Route::get('/tax-preset-list', [
            'as' => 'manage.tax_preset.read.list',
            'uses' => 'TaxPresetController@prepareTaxpresetList',
        ]);

        // TaxPreset delete process
        Route::post('/{taxPresetIdOrUid}/tax-preset-delete-process', [
            'as' => 'manage.tax_preset.write.delete',
            'uses' => 'TaxPresetController@processTaxpresetDelete',
        ]);

        // TaxPreset create support data
        Route::get('/tax-preset-add-support-data', [
            'as' => 'manage.tax_preset.read.support_data',
            'uses' => 'TaxPresetController@prepareTaxpresetSupportData',
        ]);

        // TaxPreset create process
        Route::post('/tax-preset-add-process', [
            'as' => 'manage.tax_preset.write.create',
            'uses' => 'TaxPresetController@processTaxpresetCreate',
        ]);

        // TaxPreset get the data
        Route::get('/{taxPresetIdOrUid}/tax-preset-get-update-data', [
            'as' => 'manage.tax_preset.read.update.data',
            'uses' => 'TaxPresetController@updateTaxpresetData',
        ]);

        // TaxPreset update process
        Route::post('/{taxPresetIdOrUid}/tax-preset-update-process', [
            'as' => 'manage.tax_preset.write.update',
            'uses' => 'TaxPresetController@processTaxpresetUpdate',
        ]);
    });

    /*
          End Tax preset Components Manage Section Related Routes
        ------------------------------------------------------------------- */

    /*
          End Tax Components Manage Section Related Routes
        ------------------------------------------------------------------- */

    Route::group([
        'namespace' => 'Tax\Controllers',
        'prefix' => '{taxPresetIdOrUid}/tax',
    ], function () {
        // Tax list
        Route::get('/list', [
            'as' => 'manage.tax.read.list',
            'uses' => 'TaxController@prepareTaxList',
        ]);

        // Tax delete process
        Route::post('/{taxIdOrUid}/delete-process', [
            'as' => 'manage.tax.write.delete',
            'uses' => 'TaxController@processTaxDelete',
        ]);

        // Tax create support data
        Route::get('/add-support-data', [
            'as' => 'manage.tax.read.support_data',
            'uses' => 'TaxController@prepareTaxSupportData',
        ]);

        // Tax create process
        Route::post('/add-process', [
            'as' => 'manage.tax.write.create',
            'uses' => 'TaxController@processTaxCreate',
        ]);

        // Tax get the data
        Route::get('/{taxIdOrUid}/get-update-data', [
            'as' => 'manage.tax.read.update.data',
            'uses' => 'TaxController@updateTaxData',
        ]);

        // Tax update process
        Route::post('/{taxIdOrUid}/update-process', [
            'as' => 'manage.tax.write.update',
            'uses' => 'TaxController@processTaxUpdate',
        ]);
    });

    /*
          End Tax Components Manage Section Related Routes
        ------------------------------------------------------------------- */

    /*
          Start Dashboard Components Manage Section Related Routes
        ------------------------------------------------------------------- */

    Route::group([
        'namespace' => 'Dashboard\Controllers',
        'prefix' => 'dashboard',
    ], function () {
        // get details of dashboard data
        Route::get('/get-data', [
            'as' => 'manage.dashboard.read.support_data',
            'uses' => 'DashboardController@dashboardSupportData',
        ]);

        // search for products
        Route::get('/{searchTerm}/search-products', [
            'as' => 'manage.dashboard.read.search_products',
            'uses' => 'DashboardController@searchProducts',
        ]);

        // get product inventory details
        Route::get('/{productId}/product-inventory-details', [
            'as' => 'manage.dashboard.read.product_inventory_details',
            'uses' => 'DashboardController@getProductInvetoryDetails',
        ]);
    });

    Route::get('/base-data', [
        'as' => 'base_data',
        'uses' => '__Igniter@baseData',
    ]);

    // Request Initialization for evenry request
    Route::get('/{routeName}/request-initialization', [
        'as' => 'request.initialization',
        'uses' => '__Igniter@getRequestInitialization',
    ]);

    Route::group([
        'namespace' => 'Media\Controllers',
        'prefix' => 'media',
    ], function () {
        // upload image media detail
        Route::get('/read-uploaded-user-profile-files-detail', [
            'as' => 'media.upload.read_user_profile',
            'uses' => 'MediaController@readUserProfileFiles',
        ]);

        Route::post('/upload-user-profile', [
            'as' => 'media.upload.write.user_profile',
            'uses' => 'MediaController@uploadUserProfile',
        ]);
    });

    /**
     * Start api section routes after login
     *
     * ---------------------------------------------------------------------------------------- */
    Route::group(['prefix' => 'api'], function () {
        /*
            Start Company Activity-log Component Related Routes
            ----------------------------------------------------------------------- */
        //Activity Log
        Route::group([
            'namespace' => 'ActivityLog\Controllers',
            'prefix' => '/activity',
        ], function () {
            // Activity list
            Route::get('/{startDate}/{endDate}/list', [
                'as' => 'manage.activity_log.read.list',
                'uses' => 'ActivityLogController@prepareActivityLogList',
            ]);
        });

        /*
            Start User Role Permission Components Manage Section Related Routes
            ------------------------------------------------------------------- */
        Route::group([
            'namespace' => 'User\Controllers',
            'prefix' => '/role-permission',
        ], function () {
            // Get Role Add Support Data
            Route::get('/role-add-support-data', [
                'as' => 'manage.user.role_permission.read.add_support_data',
                'uses' => 'RolePermissionController@getAddSuppotData',
            ]);

            // Get role all permission using id
            Route::get('/{roleId}/get-permission', [
                'as' => 'manage.user.role_permission.read.using_id',
                'uses' => 'RolePermissionController@getPermissionById',
            ]);

            // Add New Role Permissions
            Route::post('/add-process', [
                'as' => 'manage.user.role_permission.write.role.create',
                'uses' => 'RolePermissionController@addNewRole',
            ]);

            // Role Permission list
            Route::get('/list', [
                'as' => 'manage.user.role_permission.read.list',
                'uses' => 'RolePermissionController@prepareRolePermissionList',
            ]);

            // Role Permission delete process
            Route::post('/{rolePermissionIdOrUid}/delete-process', [
                'as' => 'manage.user.role_permission.write.delete',
                'uses' => 'RolePermissionController@processRolePermissionDelete',
            ]);

            // Get Role Permissions
            Route::get('/{roleId}/permissions', [
                'as' => 'manage.user.role_permission.read',
                'uses' => 'RolePermissionController@getPermissions',
            ]);

            // Create User Role Dynamic Permission
            Route::post('/{roleId}/add-dynamic-permission-process', [
                'as' => 'manage.user.role_permission.write.create',
                'uses' => 'RolePermissionController@processDynamicRolePermission',
            ]);
        });
        /*
            End User Role Permission Components Manage Section Related Routes
            ------------------------------------------------------------------- */

        /*
            Start User Components Manage Section Related Routes
            ------------------------------------------------------------------- */

        Route::group([
            'namespace' => 'User\Controllers',
            'prefix' => 'user',
        ], function () {
            Route::post('/logout', [
                'as' => 'user.logout',
                'uses' => 'UserController@logout',
            ]);

            // change email support data
            Route::get('/change-email-support-data', [
                'as' => 'user.change_email.support_data',
                'uses' => 'UserController@getChangeEmailSupportData',
            ]);

            // change password process
            Route::post('/change-password', [
                'as' => 'user.change_password.process',
                'uses' => 'UserController@changePasswordProcess',
            ]);

            // profile details
            Route::get('/profile-details', [
                'as' => 'user.profile.details',
                'uses' => 'UserController@profileDetails',
            ]);

            // profile edit support data
            Route::get('/profile/edit-support-data', [
                'as' => 'user.profile.edit_support_data',
                'uses' => 'UserController@profileEditSupportData',
            ]);

            // profile update
            Route::get('/profile/edit', [
                'as' => 'user.profile.update',
                'uses' => 'UserController@updateProfile',
            ]);

            // profile update process
            Route::post('/profile/edit', [
                'as' => 'user.profile.update.process',
                'uses' => 'UserController@updateProfileProcess',
            ]);

            // change email process
            Route::post('/change-email', [
                'as' => 'user.change_email.process',
                'uses' => 'UserController@changeEmailProcess',
            ]);

            // fetch users list for datatable
            Route::get('/{status}/fetch-list', [
                'as' => 'manage.user.read.datatable.list',
                'uses' => 'UserController@index',
            ])->where('status', '[0-9]+');

            // fetch users detail data
            Route::get('/{userID}/user-detail', [
                'as' => 'manage.user.read.detail.data',
                'uses' => 'UserController@userDetailData',
            ]);

            // delete user
            Route::post('/{userID}/delete', [
                'as' => 'manage.user.write.delete',
                'uses' => 'UserController@delete',
            ])->where('userID', '[0-9]+');

            // restore user
            Route::post('/{userID}/restore', [
                'as' => 'manage.user.write.restore',
                'uses' => 'UserController@restore',
            ])->where('userID', '[0-9]+');

            // change password by admin process
            Route::post('/{userID}/change-password', [
                'as' => 'manage.user.write.change_password.process',
                'uses' => 'UserController@changePasswordByAdmin',
            ])->where('userID', '[0-9]+');

            // fetch users details
            Route::get('/{userID}/contact', [
                'as' => 'manage.user.read.contact',
                'uses' => 'UserController@contact',
            ])->where('status', '[0-9]+');

            // process contact form
            Route::get('/{userId}/get-user-info', [
                'as' => 'manage.user.read.info',
                'uses' => 'UserController@getInfo',
            ]);

            // Get add support data
            Route::get('/add-support-data', [
                'as' => 'manage.user.read.create.support_data',
                'uses' => 'UserController@getAddSupportData',
            ]);

            // Get User available permissions
            Route::get('/{userId}/get-user-permissions', [
                'as' => 'manage.user.read.get_user_permissions',
                'uses' => 'UserController@getUserPermissions',
            ]);

            // Get User Edit Data
            Route::get('/{userId}/get-user-edit data', [
                'as' => 'manage.user.read.edit_suppport_data',
                'uses' => 'UserController@getUserEditSupportData',
            ]);

            // Store user dynamic permissions
            Route::post('/{userId}/user-update-process', [
                'as' => 'manage.user.write.update_process',
                'uses' => 'UserController@processUserUpdate',
            ]);

            // Store user dynamic permissions
            Route::post('/{userId}/user-dynamic-permission-process', [
                'as' => 'manage.user.write.user_dynamic_permission',
                'uses' => 'UserController@processUserPermissions',
            ]);

            // Add New User
            Route::post('/add', [
                'as' => 'manage.user.write.create',
                'uses' => 'UserController@add',
            ]);

            // get users list
            Route::get('/get-users-list', [
                'as' => 'manage.user.read.list',
                'uses' => 'UserController@prepareReadUsers',
            ]);
        });
        /*
            End User Components Manage Section Related Routes
            ------------------------------------------------------------------- */

        /*
            Start Configuration Components Manage Section Related Routes
            ------------------------------------------------------------------- */

        Route::group([
            'namespace' => 'Configuration\Controllers',
            'prefix' => 'configuration',
        ], function () {
            // process configuration
            Route::post('/process/{formType}', [
                'as' => 'manage.configuration.process',
                'uses' => 'ConfigurationController@process',
            ]);

            // process get configuration
            Route::get('/get-support-data/{formType}', [
                'as' => 'manage.configuration.get.support.data',
                'uses' => 'ConfigurationController@getSupportData',
            ]);
        });
        /*
            End Configuration Components Manage Section Related Routes
            ------------------------------------------------------------------- */
    });

    /**
     * End console section routes after login
     *
     * ---------------------------------------------------------------------------------------- */

    /**
     * Public section routes after login
     *
     * ---------------------------------------------------------------------------------------- */

    /*
            Media Components Public Section Related Routes
            ----------------------------------------------------------------------- */

    Route::group([
        'namespace' => 'Media\Controllers',
        'prefix' => 'media',
    ], function () {
        // upload image media
        Route::post('/upload-files', [
            'as' => 'media.upload.write',
            'uses' => 'MediaController@upload',
        ]);

        // upload all media
        Route::post('/upload-logo', [
            'as' => 'media.upload.write.logo',
            'uses' => 'MediaController@uploadLogo',
        ]);

        // delete media file
        Route::post('/{fileName}/delete', [
            'as' => 'media.upload.delete',
            'uses' => 'MediaController@delete',
        ]);

        // delete multiple media files
        Route::post('/multiple-delete', [
            'as' => 'media.upload.delete_multiple',
            'uses' => 'MediaController@multipleDeleteFiles',
        ]);

        // select media files
        Route::post('/select-files', [
            'as' => 'media.upload.select_files',
            'uses' => 'MediaController@selectFiles',
        ]);

        // upload image media
        Route::get('/read-files', [
            'as' => 'media.upload.read.files',
            'uses' => 'MediaController@readFiles',
        ]);

        // upload image media detail
        Route::get('/read-uploaded-files-detail', [
            'as' => 'media.upload.read_logo',
            'uses' => 'MediaController@readLogoFiles',
        ]);

        // upload image media detail
        Route::get('/read-uploaded-favicon-files-detail', [
            'as' => 'media.upload.read_favicon',
            'uses' => 'MediaController@readFaviconFiles',
        ]);

        // upload image media detail
        Route::get('/read-uploaded-attachment-files-detail', [
            'as' => 'media.upload.read_message_attachment',
            'uses' => 'MediaController@readMessageAttachmentFiles',
        ]);

        // upload image media detail
        Route::get('/read-uploaded-requirement-attachment-files-detail', [
            'as' => 'media.upload.read_requirement_attachment',
            'uses' => 'MediaController@readRequirementAttachmentFiles',
        ]);
    });
});

/*
      End After Authentication Accessible Routes
      ---------------------------------------------------------------------- */

/*
     start  Guest Auth Routes
    -------------------------------------------------------------------------- */

Route::group(['middleware' => 'guest'], function () {
    /*
          Start User Components Public Section Related Routes
          ----------------------------------------------------------------------- */

    Route::group([
        'namespace' => 'User\Controllers',
        'prefix' => 'user',
    ], function () {
        // login process
        Route::post('/process-login', [
            'as' => 'user.login.process',
            'uses' => 'UserController@loginProcess',
        ]);

        // register
        Route::get('/register', [
            'as' => 'user.register',
            'uses' => 'UserController@register',
        ]);

        // register process
        Route::post('/register', [
            'as' => 'user.register.process',
            'uses' => 'UserController@registerProcess',
        ]);

        // register success
        Route::get('/register/success', [
            'as' => 'user.register.success',
            'uses' => 'UserController@registerSuccess',
        ]);

        // account activation
        Route::get('/{userID}/{activationKey}/account-activation', [
            'as' => 'user.account.activation',
            'uses' => 'UserController@accountActivation',
        ])->where('userID', '[0-9]+');

        // login attempts
        Route::get('/login-attempts', [
            'as' => 'user.login.attempts',
            'uses' => 'UserController@loginAttempts',
        ]);

        // forgot password
        Route::post('/forgot-password', [
            'as' => 'user.forgot_password.process',
            'uses' => 'UserController@forgotPasswordProcess',
        ]);

        // forgot password success
        Route::get('/forgot-password-success', [
            'as' => 'user.forgot_password.success',
            'uses' => 'UserController@forgotPasswordSuccess',
        ]);

        // reset password
        Route::get('/reset-password/{reminderToken}', [
            'as' => 'user.reset_password',
            'uses' => 'UserController@restPassword',
        ]);

        // reset password process
        Route::post('/reset-password/{reminderToken}', [
            'as' => 'user.reset_password.process',
            'uses' => 'UserController@restPasswordProcess',
        ]);

        // new user activation process
        Route::post('/resend-activation-email', [
            'as' => 'user.resend.activation.email.proccess',
            'uses' => 'UserController@resendActivationEmailProccess',
        ]);
    });

    /*
          End User Components Public Section Related Routes
          ----------------------------------------------------------------------- */
});

/*
 end  Guest Auth Routes
-------------------------------------------------------------------------- */

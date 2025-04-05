(function() {
'use strict';

  angular.module('ManageApp', [
    'ngMessages',
    'ngAnimate',
    'ngSanitize',
    'ui.router',
    'ui.router.state.events',
    'ngNotify',
    'ngDialog',
    'angular-loading-bar',
    'selectize',
    'NgSwitchery',
    'lw.core.utils',
    'lw.security.main',
    'lw.auth',
    'lw.data.datastore',
    'lw.data.datatable',
    'lw.form.main',
    'app.service',
    'app.http',
    'app.notification',   
    'app.form',
    'app.directives',
    'app.fileUploader',
    'ui.router.state.events',
    'ManageApp.master',
    'app.UploaderDataService',
    'app.UploaderEngine',

    'ManageApp.ManageUserDataService',
    'ManageApp.users',

    'CommonApp.CommonUserDataService',
    'CommonApp.users',

    'Manage-app.users',
    'ManageApp.UserDataService',

    'ManageApp.ConfigurationDataService',
    'ManageApp.configuration',

    'ManageApp.DashboardDataService',
    'ManageApp.DashboardEngine',

    'app.RolePermissionDataServices',
    'app.RolePermissionEngine',
 	
 	'app.ActivityDataServices',
	'app.ActivityEngine',
	
	'app.CategoryDataServices',
	'app.CategoryEngine',
	
	'app.CustomerDataServices',
	'app.CustomerEngine',
	
	'app.SuppliersDataServices',
	'app.SuppliersEngine',
	
	'app.ProductDataServices',
	'app.ProductEngine',
	
	'app.LocationDataServices',
	'app.LocationEngine',
	
	'app.InventoryDataServices',
	'app.InventoryEngine',
	'app.ReportDataServices', 
	'app.ReportEngine',
	
	'app.BillingDataServices',
	'app.BillingEngine',

	'app.TaxPresetDataServices',
	'app.TaxPresetEngine',

	'app.TaxDataServices',
	'app.TaxEngine'
  ]).
  //constant('__ngSupport', window.__ngSupport).
  run([
    '__Auth', '$state', '$rootScope', '$transitions','$trace', function(__Auth, $state, $rootScope, $transitions, $trace) {

        _.delay(function() {

            __Auth.verifyRoute($state); 


            $rootScope.$on('$viewContentLoading', function(event, viewConfig) { 
               
                var accessObject = $state.current;

                if( accessObject  && _.has( accessObject, 'loginRequired' ) && accessObject.loginRequired === false) {

                    if (__Auth.isLoggedIn()) {
                        $state.go( 'dashboard' );
                    }

                    event.preventDefault();

                    return false;
                }
            });

        }, 100);

        $rootScope.__ngSupport = window.__ngSupport;

    }
  ]).
  config([
    '$stateProvider', '$urlRouterProvider', '$interpolateProvider','$compileProvider', routes
  ]);


  /**
    * Application Routes Configuration
    *
    * @inject $stateProvider
    * @inject $urlRouterProvider
    * @inject $interpolateProvider
    * @inject $compileProvider
    *
    * @return void
    *---------------------------------------------------------------- */

  function routes($stateProvider, $urlRouterProvider, $interpolateProvider, $compileProvider) {

    if( window.appConfig && window.appConfig.debug === false) {
        $compileProvider.debugInfoEnabled(false);
    }

    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');

    /*$urlRouterProvider
       .otherwise('/dashboard');*/

    $urlRouterProvider.otherwise(function($injector, $location, $transitions) {
       var state = $injector.get('$state'),
            auth = $injector.get('__Auth'),
            redirectState = __globals.appTemps('stateViaRoute');
        if(_.has(redirectState, 'stateName')
            && !_.isUndefined(redirectState.stateName)
            && !_.isEmpty(redirectState.stateName)) {
            if (_.isEmpty(redirectState.stateParams)) {
                state.go(redirectState.stateName);
            } else {
                state.go(redirectState.stateName, redirectState.stateParams);
            }
        } else {
            state.go('dashboard');
        }
	
        return $location.path();
    });

    //state configurations
    $stateProvider

        .state('base', {
            abstract: true,
            resolve: {
                baseData: ['$stateParams', 'BaseDataService', function($stateParams, BaseDataService) {
                    
                    return BaseDataService.getBaseData('account_logged');
                }]
        }})

        // login
        .state('login',
            __globals.stateConfig('/login', 'user.login', {
                
                parent : 'base',
                loginRequired : false,
            }) )

        // forgot password
        .state('forgot_password',
            __globals.stateConfig('/forgot-password', 'user.forgot-password', {

                parent : 'base',
                loginRequired : false,
            })
        )

		// forgot password
        .state('reset_password',
            __globals.stateConfig('/reset-password/{reminderToken}', 'user.reset-password')
        )

        // Forgot Password Success
        .state('forgot_password_sucess',
            __globals.stateConfig('/forgot-password', 'user.forgot-password-success')
        )

        // home
        .state('home',
             __globals.stateConfig('/home', 'home', {
                access  : {
                    authority : 'public.app'
                },
                parent : 'base'
              }
            )
        )

        // invalid request
        .state('invalid_request', __globals.stateConfig('/invalid-request',
            'errors/invalid-request'
        ))

        // not found
        .state('not_found', __globals.stateConfig('/not-found',
            'errors.manage-not-exist'
        ))

        // not exist
        .state('not_exist', __globals.stateConfig('/not-exist',
            'errors.manage-not-exist'
        ))

        // unauthorized
        .state('unauthorized', __globals.stateConfig('/unauthorized',
            'errors.unauthorized'
		))

        // dashboard
        .state('dashboard',
             __globals.stateConfig('/dashboard', 'dashboard.admin-dashboard', {
                controller : 'AdminDashboardController as AdminDashboardCtrl',
                access  : {
                    authority : 'manage.dashboard.read.support_data'
                },
                parent : 'base',
                resolve: {
                    GetDatshboardData: ["DashboardDataService", function(DashboardDataService) {
                        return DashboardDataService.getSupportData();
                    }]
                },
              }
            )
        )

        // users
        .state('users',
             __globals.stateConfig('/users', 'user/manage/list', {
                controller : 'ManageUsersController as manageUsersCtrl',
                access  : {
                    authority : 'manage.user.read.datatable.list'
                },
                parent : 'base'
              }
            )
        )

        // RolePermission list
        .state('role_permission',
            __globals.stateConfig('/role-permissions', 'user/role-permission/list', {
            access  : {
                authority:'manage.user.role_permission.read.list'
            },
            controller : 'RolePermissionListController as rolePermissionListCtrl',
            parent : 'base'
        } ))

        // profile
        .state('profile',
             __globals.stateConfig('/profile', 'user/manage-profile', {
                access  : {
                    authority : 'user.profile.update'
                },
                parent : 'base'
              }
            )
        )

        // profile edit
        .state('profileEdit',
             __globals.stateConfig('/profile/edit', 'user/profile-edit', {
                access  : {
                    authority : 'user.profile.update.process'
                },
                parent : 'base'
              }
            )
        )

        // change password
        .state('changePassword',
             __globals.stateConfig('/change-password', 'user/change-password', {
                access  : {
                    authority : 'user.change_password.process'
                },
                parent : 'base'
              }
            )
        )

        // change email
        .state('changeEmail',
             __globals.stateConfig('/change-email', 'user/change-email', {
                access  : {
                    authority : 'user.change_email.process'
                },
                parent : 'base'
              }
            )
        )

        // activity log
        .state('activity_log',
			__globals.stateConfig('/activity-log', 'activity/activity-log', {
            	controller  : 'ActivityLogListController as activityLogListCtrl',
                parent : 'base'
        } ))

        // categories
        .state('categories',
            __globals.stateConfig('/categories', 'category/list', {
                controller  : 'CategoryListController as CategoryListCtrl',
                access  : {
                    authority : 'manage.category.read.list'
                },
                parent : 'base'
        } ))
        
        
        // Customer list
        .state('customer', 
            __globals.stateConfig('/customers', 'customer/list', {
            controller : 'CustomerListController as customerListCtrl',
            access  : {
                authority : 'manage.customer.read.list'
            },
            parent : 'base'
        } ))
        
        
        // Suppliers list
        .state('suppliers', 
            __globals.stateConfig('/suppliers', 'suppliers/list', {
            controller : 'SuppliersListController as suppliersListCtrl',
            access  : {
                authority : 'manage.suppliers.read.list'
            },
            parent : 'base'
        } ))
        
        
        // Product list
        .state('product', 
            __globals.stateConfig('/products', 'product/list', {
                access  : {
                    authority : 'manage.product.read.list'
                },
                parent : 'base',
                controller : 'ProductListController as productListCtrl',
            } 
        ))
        

         // Product add
        .state('product_add', 
            __globals.stateConfig('/product/add', 'product/add', {
                access  : {
                    authority : 'manage.product.write.create'
                },
                parent : 'base',
                controller : 'ProductAddController as productAddCtrl',
                resolve : {
                    productAddData : ["ProductDataService", function(ProductDataService) {
                        return ProductDataService
                                .getAddSupportData();
                    }]
                }
            }
        ))


        // Product edit
        .state('product_edit', 
            __globals.stateConfig('/product/:productIdOrUid/edit', 'product/edit', {
                access  : {
                    authority : 'manage.product.write.update'
                },
                parent : 'base',
                controller : 'ProductEditController as productEditCtrl',
                resolve : {
                    productEditData : ["ProductDataService", "$stateParams", function(ProductDataService, $stateParams) {
                        return ProductDataService
                                .getEditSupportData($stateParams.productIdOrUid);
                    }]
                }
            }
        ))
        
        // Location list
        .state('location', 
            __globals.stateConfig('/locations', 'location/list', {
            access  : {
                authority : 'manage.location.read.list'
            },
            parent : 'base',
            controller : 'LocationListController as locationListCtrl'
        } ))

        // Inventory
        .state('inventory', 
            __globals.stateConfig('/inventory', 'inventory/list', {
            access  : {
                authority : 'manage.inventory.read.list'
            },
            parent : 'base',
            controller : 'InventoryListController as InventoryListCtrl'
        } ))


        // configuration general
        .state('configuration_general',
             __globals.stateConfig('/general', 'configuration.general', {
                parent : 'base',
                controller  : 'GeneralController as generalCtrl',
                access  : {
                    authority : 'manage.configuration.process'
                },
                resolve: {
                    getGeneralData: ["ConfigurationDataService", function(ConfigurationDataService) {

                       return ConfigurationDataService
                                .readConfigurationData(1) // general form
                                .then(function(response) {
                                    return response;
                                });
                    }]
                }

              }

            )
        )

        // configuration currency
        .state('configuration_currency',
             __globals.stateConfig('/currency', 'configuration.currency', {
                parent : 'base',
                controller  : 'CurrencyConfigurationController as currencyConfigurationCtrl',
                access  : {
                    authority : 'manage.configuration.process'
                },
                resolve: {
                    getCurrencyData: ["ConfigurationDataService", function(ConfigurationDataService) {

                       return ConfigurationDataService
                                .readConfigurationData(2) // Currency form
                                .then(function(response) {
                                    return response;
                                });
                    }]
                }
              }
            )
        )
        
        
        // Report list
        .state('report', 
            __globals.stateConfig('/reports', 'report/list', {
                access  : {
                    authority : 'manage.report.read.list'
                },
                resolve: {
                    GetSupportData: ["ReportDataService", function(ReportDataService) {

                       return ReportDataService.getSupportData(2);
                    }]
                },
                controller : 'ReportListController as reportListCtrl',
                parent : 'base'
        } ))
        
        
        // Billing list
        .state('billing', 
            __globals.stateConfig('/billings', 'billing/list', {
            access  : {
                authority : 'manage.billing.read.list'
            },
            controller : 'BillingListController as billingListCtrl',
            parent : 'base'
        } ))

        // Add Billing
        .state('billing_add', 
            __globals.stateConfig('/billing/create', 'billing/add', {
            access  : {
                authority : 'manage.billing.read.list'
            },
            resolve: {
                GetAddSupportData: ["BillingDataService", function(BillingDataService) {
                   return BillingDataService.getAddSupportData();
                }]
            },
            controller : 'BillingAddController as billingAddCtrl',
            parent : 'base'
        } ))

        // Edit Billing
        .state('billing_edit', 
            __globals.stateConfig('/billing/:billUid/edit', 'billing/edit', {
            access  : {
                authority : 'manage.billing.read.list'
            },
            resolve: {
                GetEditSupportData: ["BillingDataService", "$stateParams", function(BillingDataService, $stateParams) {
                   return BillingDataService.getEditSupportData($stateParams.billUid);
                }]
            },
            controller : 'BillingEditController as billingEditCtrl',
            parent : 'base'
        } ))

        // Edit Billing
        .state('billing_details', 
            __globals.stateConfig('/billing/:billUid/details', 'billing/details', {
            access  : {
                authority : 'manage.billing.read.list'
            },
            resolve: {
                GetEditSupportData: ["BillingDataService", "$stateParams", function(BillingDataService, $stateParams) {
                   return BillingDataService.getDetailsSupportData($stateParams.billUid);
                }]
            },
            controller : 'BillingDetailsController as billingDetailsCtrl',
            parent : 'base'
        } ))

        // TaxPreset list
        .state('tax_preset', 
            __globals.stateConfig('/tax-presets', 'tax-preset/list', {
            access  : {
            },
            controller : 'TaxpresetListController as taxpresetListCtrl',
            parent : 'base',
        } ))

		// Tax list
        .state('tax', 
            __globals.stateConfig('/tax-preset/:taxPresetIdOrUid/taxes', 'tax/list', {
            access  : {
            },
            controller : 'TaxListController as taxListCtrl',
            parent : 'base',
        } ))

        ;
    };

})();;
(function () {
    'use strict';

    /*
     ManageController
    -------------------------------------------------------------------------- */

    angular
        .module('ManageApp.master', [])
        .controller('ManageController', [
            '$rootScope',
            '__DataStore',
            '$scope',
            '__Auth',
            'appServices',
            'appNotify',
            '__Form',
            '$state',
            'appToastNotify',
            'ConfigurationDataService',
            ManageController
        ]).controller('HelpController', [
            '$rootScope',
            '$scope',
            HelpController
        ]);

    /**
       * ManageController for manage page application
       *
       * @inject $rootScope
       * @inject __DataStore
       * @inject $scope
       * @inject __Auth
       * @inject appServices
       * @inject appNotify
       *
       * @return void
       *-------------------------------------------------------- */

    function ManageController($rootScope, __DataStore, $scope, __Auth, appServices, appNotify, __Form, $state, appToastNotify, ConfigurationDataService) {

        var scope = this;


        scope.pageStatus = false;

        scope.refreshAuthObj = function () {

            __Auth.refresh(function (authInfo) {

                scope.auth_info = authInfo;
            });

        };
        scope.refreshAuthObj();

        scope.notify = __globals.getAppImmutables('notifyToAdmin');
        scope.restrict_user_email_update = __globals.getAppImmutables('restrict_user_email_update');

        scope.unhandledError = function () {

            appNotify.error(__globals.getReactionMessage(19)); // Unhanded errors

        };

        $rootScope.isAdmin = function () {
            return scope.auth_info.designation === 1;
        };

        $rootScope.$on('auth_info_updated', function (event, args) {
            $rootScope.auth_info = args.auth_info;
            if (!_.isEmpty(args.userFullName)) {
                scope.userUpdateData = args.userFullName;
            }
            scope.auth_info = $rootScope.auth_info;

        });

        $rootScope.$on('lw.events.logged_in_user', function () {
            scope.refreshAuthObj();
        });

        $rootScope.$on('lw.events.state.change_start', function () {
            appServices.closeAllDialog();
        });

        $rootScope.$on('lw.datastore.event.post.started', __globals.showButtonLoader);

        $rootScope.$on('lw.datastore.event.fetch.started', __globals.showFormLoader);

        $rootScope.$on('lw.form.event.process.started');

        $rootScope.$on('lw.form.event.fetch.started', __globals.showFormLoader);

        $rootScope.$on('lw.datastore.event.fetch.finished', __globals.hideFormLoader);

        $rootScope.$on('lw.datastore.event.post.finished', __globals.hideButtonLoader);

        $rootScope.$on('lw.form.event.process.finished', __globals.hideButtonLoader);

        $rootScope.$on('lw.datastore.event.fetch.error', scope.unhandledError);

        $rootScope.$on('lw.form.event.process.error', scope.unhandledError);

        $rootScope.$on('$stateChangeSuccess', function ($stateEvent, $stateInfo) {

            var scrollOffsets = __globals.getScrollOffsets(),
                yOffset = Math.round(scrollOffsets.y);
            // document.body.scrollTop = document.documentElement.scrollTop = 0;
            $('html, body').animate({ scrollTop: 0 }, yOffset < 500 ? 500 : yOffset);
        });

        // Dialog Opened Event
        $rootScope.$on('ngDialog.opened', function (e, $dialog) {
            _.defer(function () {
                $('.ngdialog').scrollTop(0);
            });
        });

        scope.showUploadManagerDialog = function () {
            appServices.showDialog(scope, {
                templateUrl: __globals.getTemplateURL('upload-manager.upload-manager-dialog')
            },
                function (promiseObj) {

                });
        };

        /**
        * Check if user logged in
        *
        * @return boolean
        *---------------------------------------------------------------- */

        scope.isLoggedIn = function () {
            return __Auth.isLoggedIn();   // isLoggedIn
        };

        /**
        * Check if user logged in
        *
        * @return boolean
        *---------------------------------------------------------------- */

        scope.logoutUser = function () {

            __Auth.registerIntended("dashboard");

            __DataStore.post({
                'apiURL': 'user.logout'
            })
                .success(function (responseData) {

                    if (responseData.reaction == 1) {
                        //__globals.setCookie('auth_access_token', '');

                        __Auth.checkOut({}, function (authInfo) {
                            $state.go('login');
                        });
                    }

                });
        };

        /**
          * Open help dialog
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.openHelpDailog = function (templateUrl, templateTitle) {

            scope.templateTitle = templateTitle;

            appServices.showDialog(scope,
                {
                    templateUrl: __globals.getTemplateURL(templateUrl),

                    controller: 'HelpController as helpCtrl'
                },
                function (promiseObj) {

                });

        };

        scope.showGeneralSetting = function () {

            ConfigurationDataService
                .readConfigurationData(1)
                .then(function (responseData) {

                    var logo_background_color = responseData.data.configuration.logo_background_color;

                    appServices.showDialog({
                        'responseData': responseData
                    }, {
                        templateUrl: __globals.getTemplateURL('configuration.general')
                    }, function (promiseObj) {

                        $('#lwchangeBgHeaderColor').css('background', "#" + logo_background_color);
                    });
                });
        };

        scope.themeColors = __globals.getAppImmutables('config')['theme_colors'];

        /**
        * Set Theme color
        *---------------------------------------------------------------- */
        scope.setThemeColor = function (colorName) {
            __DataStore.fetch({
                'apiURL': 'theme_color',
                'colorName': colorName
            }).success(function (responseData) {
                location.reload();
            });
        }

        /**
        * Show Hide Theme Color
        *---------------------------------------------------------------- */
        scope.showHideThemeContainer = function () {
            if (!$('.lw-theme-color-container').hasClass('lw-theme-container-active')) {
                $('.lw-theme-color-container').addClass('lw-theme-container-active');
                $('.lw-switch i:first').replaceWith("<span>&times;</span>");
            } else {
                $('.lw-theme-color-container').removeClass('lw-theme-container-active');
                $('.lw-switch span:first').replaceWith("<i class='fa fa-cog'></i>");
            }
        }

        $rootScope.$on('lw-open-login-dialog', function (event, response) {


            event.preventDefault();

            appServices.loginRequiredDialog('login-dialog', response.data, function (result, newData) {

                __DataStore.reset();

                if (result) {
                    scope.refreshAuthObj();
                    $state.reload();
                }
            });

        });
    };

    /**
    * HelpController for helping information
    *
    * @inject $rootScope
    * @inject $scope
    *
    * @return void
    *-------------------------------------------------------- */

    function HelpController($rootScope, $scope) {

        var scope = this;

        if (_.has($scope.ngDialogData, 'templateTitle')) {
            scope.templateTitle = $scope.ngDialogData.templateTitle;
        }

        /**
          * Close dialog
          *
          * @return void
          *---------------------------------------------------------------- */

        scope.closeDialog = function () {
            $scope.closeThisDialog();
        };

    };

})();;
/*!
*  Component  : Manage Users
*  File       : ManageUserDataService.js  
*  Engine     : ManageUserDataService 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('ManageApp.ManageUserDataService', [])

        /**
          Manage User Data Service  
        ---------------------------------------------------------------- */
        .service('ManageUserDataService', [
            '$q',
            '__DataStore',
            '__Form',
            'appServices',
            ManageUserDataService
        ]);

    function ManageUserDataService($q, __DataStore, __Form, appServices) {

        /*
        Get User Info
        -----------------------------------------------------------------*/
        this.getUserInfo = function (userId) {

            //create a differed object          
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.user.read.info',
                'userId': userId
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData);

                });

            });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get User Add Support Data
        -----------------------------------------------------------------*/
        this.getUserAddSupportData = function () {

            //create a differed object          
            var defferedObject = $q.defer();

            __DataStore.fetch('manage.user.read.create.support_data')
                .success(function (responseData) {

                    appServices.processResponse(responseData, null, function (reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData);

                    });

                });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get User User Permissions
        -----------------------------------------------------------------*/
        this.getUserPermissions = function (userId) {

            //create a differed object          
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.user.read.get_user_permissions',
                'userId': userId
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);

                });

            });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get User User Permissions
        -----------------------------------------------------------------*/
        this.getUserDetailData = function (userId) {

            //create a differed object          
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.user.read.detail.data',
                'userID': userId
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);

                });

            });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get User Edit Data
        -----------------------------------------------------------------*/
        this.getUserEditData = function (userId) {

            //create a differed object          
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.user.read.edit_suppport_data',
                'userId': userId
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);

                });

            });

            //return promise to caller          
            return defferedObject.promise;
        };
    }
    ;

})(window, window.angular);;
/*!
*  Component  : Manage User
*  File       : ManageUserEngine.js  
*  Engine     : ManageUserEngine 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('ManageApp.users', [])

        /**
         * Manage Users Controller
         *
         * @inject $scope
         * @inject __DataStore
         * @inject appServices
         *
         * @return void
         *-------------------------------------------------------- */
        .controller('ManageUsersController', [
            '$scope',
            '__DataStore',
            'appServices',
            'ManageUserDataService',
            'ConfigurationDataService',
            'LocationDataService',
            function ManageUsersController($scope, __DataStore, appServices, ManageUserDataService, ConfigurationDataService, LocationDataService) {

                var dtUsersColumnsData = [
                    {
                        "name": null,
                        "template": "#profileImageColumnTemplate"
                    },
                    {
                        "name": "name",
                        "template": "#userNameColumnTemplate",
                        "orderable": true
                    },
                    {
                        "name": "username",
                        "orderable": true
                    },
                    {
                        "name": "email",
                        "orderable": true
                    },
                    {
                        "name": "updated_at",
                        "template": "#userUpdatedDateColumnTemplate",
                        "orderable": true
                    },
                    {
                        "name": "user_role",
                        "orderable": true
                    },
                    {
                        "name": null,
                        "template": "#userActionColumnTemplate"
                    }
                ],
                    dtDeletedUsersColumnsData = [
                        {
                            "name": null,
                            "template": "#profileImageColumnTemplate"
                        },
                        {
                            "name": "name",
                            "template": "#userNameColumnTemplate",
                            "orderable": true
                        },
                        {
                            "name": "username",
                            "orderable": true
                        },
                        {
                            "name": "email",
                            "orderable": true
                        },
                        {
                            "name": "updated_at",
                            "template": "#userUpdatedDateColumnTemplate",
                            "orderable": true
                        },
                        {
                            "name": "user_role",
                            "orderable": true
                        },
                        {
                            "name": null,
                            "template": "#userActionColumnTemplate"
                        }
                    ],
                    tabs = {
                        'active': {
                            id: 'activeUsersTabList',
                            status: 1 // Active
                        },
                        'inactive': {
                            id: 'inactiveUsersTabList',
                            status: 2 // Inactive
                        },
                        'deleted': {
                            id: 'deletedUsersTabList',
                            status: 5 // Soft Deleted
                        }
                    },
                    currentStatus = 1,
                    scope = this;


                // Manage users tab action
                // When clicking on tab, its related tab data load on same page

                $('#manageUsersTabs a').click(function (e) {

                    e.preventDefault();

                    var $this = $(this),
                        tabName = $this.attr('aria-controls'),
                        selectedTab = tabs[tabName];

                    // Check if selected tab exist    
                    if (!_.isEmpty(selectedTab)) {

                        $(this).tab('show')

                        currentStatus = selectedTab.status;
                        scope.getUsers(selectedTab.id, selectedTab.status);

                    }

                });

                /**
                  * Get users as a datatable source  
                  *
                  * @param string tableID
                  * @param number status
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.getUsers = function (tableID, status) {

                    // destroy if existing instatnce available
                    if (scope.usersListDataTable) {
                        scope.usersListDataTable.destroy();
                    }

                    scope.usersListDataTable = __DataStore.dataTable('#' + tableID, {
                        url: {
                            'apiURL': 'manage.user.read.datatable.list',
                            'status': status
                        },
                        dtOptions: {
                            "searching": true,
                            "pageLength": 25,
                            "order": [[1, "asc"]]
                        },
                        columnsData: status == 5 ? dtDeletedUsersColumnsData : dtUsersColumnsData,
                        scope: $scope

                    });

                };

                // load initial data for first tab
                scope.getUsers('activeUsersTabList', 1);

                /*
                  Reload current datatable
                  ------------------------------------------------------------------- */

                scope.reloadDT = function () {
                    __DataStore.reloadDT(scope.usersListDataTable);
                };

                /**
                  * Delete user 
                  *
                  * @param number userID
                  * @param string userName
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.delete = function (userID, userName, deleteType) {

                    scope.deletingUserName = unescape(userName);

                    _.defer(function () {

                        var $lwUserDeleteConfirmTextMsg = $('#lwUserDeleteConfirmTextMsg');

                        var $lwUserPerDeleteConfirmTextMsg = $('#lwUserPerDeleteConfirmTextMsg');

                        if (deleteType == 1) { // Soft delete
                            scope.deleteText = $lwUserDeleteConfirmTextMsg.attr('data-message');
                            scope.deleteConfirmBtnText = $lwUserDeleteConfirmTextMsg.attr('data-delete-button-text');
                            scope.successMsgText = $lwUserDeleteConfirmTextMsg.attr('success-msg');
                        } else { // Permanent delete
                            scope.deleteText = $lwUserPerDeleteConfirmTextMsg.attr('data-message');
                            scope.deleteConfirmBtnText = $lwUserPerDeleteConfirmTextMsg.attr('data-delete-button-text');
                            scope.successMsgText = $lwUserPerDeleteConfirmTextMsg.attr('success-msg');
                        }

                    });

                    _.defer(function () {

                        __globals.showConfirmation({
                            html: scope.deleteText,
                            confirmButtonText: scope.deleteConfirmBtnText
                        },
                            function () {

                                __DataStore.post({
                                    'apiURL': 'manage.user.write.delete',
                                    'userID': userID,
                                })
                                    .success(function (responseData) {

                                        var message = responseData.data.message;

                                        appServices.processResponse(responseData, {

                                            error: function () {

                                                __globals.showConfirmation({
                                                    title: 'Deleted!',
                                                    text: message,
                                                    type: 'error'
                                                });

                                            }
                                        },
                                            function () {

                                                __globals.showConfirmation({
                                                    title: 'Deleted!',
                                                    text: scope.successMsgText,
                                                    type: 'success'
                                                });
                                                scope.reloadDT();   // reload datatable

                                            }
                                        );

                                    })

                            })

                    });

                };

                /**
                  * Restore deleted user 
                  *
                  * @param number userID
                  * @param string userName
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.restore = function (userID, userName) {

                    scope.restoringUserName = unescape(userName);

                    _.defer(function () {

                        var $lwUserRestoreConfirmTextMsg = $('#lwUserRestoreConfirmTextMsg');

                        __globals.showConfirmation({
                            text: $lwUserRestoreConfirmTextMsg.attr('data-message'),
                            confirmButtonText: $lwUserRestoreConfirmTextMsg.attr('data-restore-button-text')
                        },
                            function () {

                                __DataStore.post({
                                    'apiURL': 'manage.user.write.restore',
                                    'userID': userID,
                                })
                                    .success(function (responseData) {

                                        var message = responseData.data.message;

                                        appServices.processResponse(responseData, {

                                            error: function () {
                                                __globals.showConfirmation({
                                                    title: 'Restore!',
                                                    text: message,
                                                    type: 'error'
                                                });
                                            }
                                        },
                                            function () {

                                                __globals.showConfirmation({
                                                    title: 'Restore!',
                                                    text: message,
                                                    type: 'success'
                                                });
                                                scope.reloadDT();   // reload datatable

                                            })

                                    })

                            })

                    });

                };

                /**
                  * Change password of user by Admin 
                  *
                  * @param number userID
                  * @param number name
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.changePassword = function (userID, name) {

                    // open change password dialog
                    appServices.showDialog({
                        userID: userID,
                        name: unescape(name)
                    },
                        {
                            templateUrl: __globals.getTemplateURL('user.manage.change-password'),
                            controller: 'ManageUserChangePasswordController as userChangePassword'
                        },
                        function (promiseObj) {

                        });
                };

                /**
                  * Show add new user dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.showAddNewDialog = function () {

                    appServices.showDialog(
                        {
                            'showRoleSelectBox': true
                        },
                        {
                            templateUrl: __globals.getTemplateURL('user.manage.add-dialog'),
                        },
                        function (promiseObj) {

                            // Check if category updated
                            if (_.has(promiseObj.value, 'user_added')
                                && promiseObj.value.user_added == true && currentStatus == 1) {
                                scope.reloadDT();
                            }

                        });
                };

                /**
                  * Edit User Dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.editUserDialog = function (userId, name) {
                    appServices.showDialog(
                        {
                            'userId': userId,
                            'name': name
                        },
                        {
                            templateUrl: 'user.manage.edit-dialog',
                            controller: 'EditUserDialogController as EditUserDialogCtrl',
                            resolve: {
                                EditUserData: function () {
                                    return ManageUserDataService
                                        .getUserEditData(userId);
                                }
                            }
                        },
                        function (promiseObj) {

                            // Check if category updated
                            if (_.has(promiseObj.value, 'user_updated')
                                && promiseObj.value.user_updated == true) {
                                scope.reloadDT();
                            }

                        });
                };

                scope.showUsersConfigurationDialog = function () {

                    ConfigurationDataService
                        .readConfigurationData(5)
                        .then(function (responseData) {

                            appServices.showDialog({
                                'responseData': responseData
                            }, {
                                templateUrl: __globals.getTemplateURL('configuration.users')
                            }, function (promiseObj) {

                            });

                        });

                };

                /**
                  * Show User Permission Dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.usersPermissionDialog = function (userId, fullName) {

                    appServices.showDialog({
                        'userId': userId,
                        'fullName': _.unescape(fullName)
                    }, {
                        templateUrl: __globals.getTemplateURL('user.manage.user-dynamic-permission'),
                        controller: 'ManageUsersDynamicPermissionController as manageUsersDynamicPermissionCtrl',
                        resolve: {
                            UserPermission: function () {
                                return ManageUserDataService.getUserPermissions(userId);
                            }
                        }
                    }, function (promiseObj) {

                    });
                };

                /**
                  * Show User Permission Dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.openUserDetailsDialog = function (userId) {

                    appServices.showDialog({}, {
                        templateUrl: __globals.getTemplateURL('user.manage.user-detail-dialog'),
                        controller: 'ManageUsersDetailController as manageUsersDetailCtrl',
                        resolve: {
                            UserDetailData: function () {
                                return ManageUserDataService.getUserDetailData(userId);
                            }
                        }
                    }, function (promiseObj) {

                    });
                };

                /**
                  * Show Assign location dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.showAssignLocationDialog = function (userAuthorityId, name) {

                    appServices.showDialog(
                        {
                            userAuthorityId: userAuthorityId,
                            name: unescape(name)
                        },
                        {
                            templateUrl: __globals.getTemplateURL('location.assign-location-dialog'),
                            controller: 'AssignLocationController as AssignLocationCtrl',
                            resolve: {
                                assignLocationData: function () {
                                    return LocationDataService.getAssignLocationData(userAuthorityId);
                                }
                            }
                        }, function (promiseObj) {

                        });
                };
            }
        ])

        /**
          * User Detail Dialog Controller
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('ManageUsersDetailController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'appServices',
            'UserDetailData',
            function ($scope, __DataStore, __Form, $stateParams, appServices, UserDetailData) {

                var scope = this,
                    requestData = UserDetailData;

                scope.userData = requestData.userData;


                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };
            }
        ])

        /**
          * Add User Dialog Controller handle add new user dialog scope
          * 
          * @inject $scope
          * @inject __Form
          * @inject appServices
          * 
          * @return void
          *-------------------------------------------------------- */

        .controller('AddUserDialogController', [
            '$scope',
            '__Form',
            'appServices',
            'ManageUserDataService',
            function ($scope, __Form, appServices, ManageUserDataService) {

                var scope = this;

                scope = __Form.setup(scope, 'add_user_form', 'userData', { secured: true });

                scope.showRoleSelectBox = $scope.ngDialogData.showRoleSelectBox;

                // Get User add Support Data
                ManageUserDataService
                    .getUserAddSupportData()
                    .then(function (responseData) {
                        var requestData = responseData.data;
                        scope.userRoles = requestData.userRoles;
                    });
                /*
                 Submit form action
                -------------------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process('manage.user.write.create', scope)
                        .success(function (responseData) {

                            appServices.processResponse(responseData, null, function () {

                                // close dialog
                                $scope.closeThisDialog({
                                    user_added: true,
                                    'user_data': responseData.data.userData
                                });

                            });

                        });

                };

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };

            }
        ])

        /**
         * ManageUserChangePasswordController handle change password by admin
         * 
         * @inject $scope
         * @inject __Form
         * @inject appServices
         * 
         * @return void
         *-------------------------------------------------------- */
        .controller('ManageUserChangePasswordController', [
            '$scope',
            '__Form',
            'appServices',
            function ManageUserChangePasswordController($scope, __Form, appServices) {

                var scope = this;

                scope = __Form.setup(scope, 'change_password_form', 'changePasswordData', {
                    secured: true
                });

                scope.ngDialogData = $scope.ngDialogData;

                scope.title = unescape(scope.ngDialogData.name);

                // get id of user
                scope.userID = scope.ngDialogData.userID;


                /*
                 Submit form action
                -------------------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process({
                        'apiURL': 'manage.user.write.change_password.process',
                        'userID': scope.userID
                    }, scope)
                        .success(function (responseData) {

                            appServices.processResponse(responseData, null, function () {

                                // close dialog
                                $scope.closeThisDialog();

                            });

                        });

                };

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };
            }
        ])

        /**
          * Manage User Details Dialog for manage product list
          *
          * @inject $scope
          * @inject __Form
          * 
          * @return void
          *-------------------------------------------------------- */
        .controller('ManageUserDetailsDialog', [
            '$scope',
            function ManageUserDetailsDialog($scope) {

                var scope = this;

                scope.ngDialogData = $scope.ngDialogData;
                scope.userDetails = scope.ngDialogData;

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };

            }
        ])/*.filter('searchFilterList', function() {

		  	return function(items, searchText) {__pr(items);

				var filtered = [];

			    angular.forEach(items, function(el, index) {
					var oldObj = index;
					var obj = [];

			      	if (index && index.indexOf(searchText)>-1) {
			        	obj.push(el);
			      	}

					filtered.push(obj);

			    });
				__pr(filtered);
			    return filtered;
		  	};
		})
*/
        /**
          * Manage User Dynamic Permissions
          *
          * @inject $scope
          * @inject __Form
          * 
          * @return void
          *-------------------------------------------------------- */
        .controller('ManageUsersDynamicPermissionController', [
            '$scope',
            '__Form',
            '__DataStore',
            'appServices',
            'UserPermission',
            function ManageUsersDynamicPermissionController($scope, __Form, __DataStore, appServices, UserPermission) {

                var scope = this;

                scope = __Form.setup(scope, 'user_dynamic_access', 'accessData', {
                    secured: true,
                    unsecuredFields: []
                });

                scope.ngDialogData = $scope.ngDialogData;
                scope.userId = scope.ngDialogData.userId;
                scope.fullName = scope.ngDialogData.fullName;
                scope.requestData = UserPermission;
                scope.permissions = scope.requestData.permissions;

                scope.accessData.allow_permissions = scope.requestData.allow_permissions;
                scope.accessData.deny_permissions = scope.requestData.deny_permissions;
                scope.accessData.inherit_permissions = scope.requestData.inherit_permissions;

                scope.disablePermissions = function (eachPermission, permissionID) {
                    _.map(eachPermission.children, function (key) {
                        if (_.includes(key.dependencies, permissionID)) {
                            _.delay(function (text) {
                                $('input[name="' + key.id + '"]').attr('disabled', true);
                            }, 500);
                        }
                    });
                }

                scope.checkedPermission = {};

                _.map(scope.accessData.allow_permissions, function (permission) {
                    scope.checkedPermission[permission] = "2";
                });

                _.map(scope.accessData.deny_permissions, function (permission) {
                    scope.checkedPermission[permission] = "3";


                    _.map(scope.permissions, function (eachPermission) {

                        var pluckedIDs = _.pluck(eachPermission.children, 'id');

                        if (_.includes(pluckedIDs, permission)) {
                            scope.disablePermissions(eachPermission, permission)
                        }

                        if (_.has(eachPermission, 'children_permission_group')) {

                            _.map(eachPermission.children_permission_group, function (groupchild) {

                                var pluckedIDs = _.pluck(groupchild.children, 'id');

                                if (_.includes(pluckedIDs, permission)) {
                                    scope.disablePermissions(groupchild, permission)
                                }
                            });
                        }
                    });

                });

                _.map(scope.accessData.inherit_permissions, function (permission) {
                    scope.checkedPermission[permission] = "1";

                    _.map(scope.permissions, function (eachPermission) {

                        var pluckedIDs = _.pluck(eachPermission.children, 'id');

                        if (_.includes(pluckedIDs, permission) && eachPermission.children[0].inheritStatus == false && eachPermission.children[0].result == "1") {
                            scope.disablePermissions(eachPermission, permission);
                        }

                        if (_.has(eachPermission, 'children_permission_group')) {

                            _.map(eachPermission.children_permission_group, function (groupchild) {

                                var pluckedIDs = _.pluck(groupchild.children, 'id');

                                if (_.includes(pluckedIDs, permission) && groupchild.children[0].inheritStatus == false && groupchild.children[0].result == "1") {
                                    scope.disablePermissions(groupchild, permission);
                                }

                            });
                        }
                    });
                });

                //for updating permissions
                scope.checkPermission = function (childId, status) {

                    if (!_.isString(status)) {
                        status = status.toString();
                    }

                    scope.checkedPermission[childId] = status;

                    if (status == "2") {
                        if (!_.includes(scope.accessData.allow_permissions, childId)) {
                            scope.accessData.allow_permissions.push(childId);
                        }
                        if (_.includes(scope.accessData.deny_permissions, childId)) {
                            scope.accessData.deny_permissions = _.without(scope.accessData.deny_permissions, childId);
                        }
                    } else if (status == "3") {

                        if (!_.includes(scope.accessData.deny_permissions, childId)) {
                            scope.accessData.deny_permissions.push(childId);
                        }
                        if (_.includes(scope.accessData.allow_permissions, childId)) {
                            scope.accessData.allow_permissions = _.without(scope.accessData.allow_permissions, childId);
                        }
                    } else {
                        if (_.includes(scope.accessData.deny_permissions, childId)) {
                            scope.accessData.deny_permissions = _.without(scope.accessData.deny_permissions, childId);
                        }
                        if (_.includes(scope.accessData.allow_permissions, childId)) {
                            scope.accessData.allow_permissions = _.without(scope.accessData.allow_permissions, childId);
                        }
                    }

                    _.map(scope.permissions, function (permission) {

                        var pluckedIDs = _.pluck(permission.children, 'id'),
                            keyPermissions = [];

                        if (_.includes(pluckedIDs, childId) && permission.children[0].id == childId) {

                            _.map(permission.children, function (key) {
                                if (_.includes(key.dependencies, childId) && status == "3") {

                                    $('input[name="' + key.id + '"]').attr('disabled', true);

                                } else if (_.includes(key.dependencies, childId) && status == "1" && permission.children[0].result && permission.children[0].inheritStatus == false) {

                                    $('input[name="' + key.id + '"]').attr('disabled', true);

                                }
                                else {
                                    $('input[name="' + key.id + '"]').attr('disabled', false);
                                }
                            });

                        }

                        if (_.has(permission, 'children_permission_group')) {
                            _.map(permission.children_permission_group, function (groupchild) {

                                var pluckedGroupChildIDs = _.pluck(groupchild.children, 'id'),
                                    keyPermissionsGroup = [];

                                //for disabling options if read option  in denied
                                if (_.includes(pluckedGroupChildIDs, childId) && groupchild.children[0].id == childId) {

                                    _.map(groupchild.children, function (groupchildkey) {
                                        if (_.includes(groupchildkey.dependencies, childId) && status == "3") {
                                            $('input[name="' + groupchildkey.id + '"]').attr('disabled', true);

                                        } else if (_.includes(groupchildkey.dependencies, childId) && status == "1" && groupchild.children[0].result && groupchild.children[0].inheritStatus == false) {

                                            $('input[name="' + groupchildkey.id + '"]').attr('disabled', true);

                                        } else {
                                            $('input[name="' + groupchildkey.id + '"]').attr('disabled', false);
                                        }


                                    });

                                }
                            })
                        }
                    })
                }

                /*
                 Submit form action
                -------------------------------------------------------------------------- */

                scope.submit = function () {
                    // scope.preparePermissions();
                    __Form.process({
                        'apiURL': 'manage.user.write.user_dynamic_permission',
                        'userId': scope.userId
                    }, scope)
                        .success(function (responseData) {
                            appServices.processResponse(responseData, null, function () {
                                // close dialog
                                $scope.closeThisDialog();
                            });
                        });
                };

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };

            }
        ])

        /**
          * Edit User Dialog Controller
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('EditUserDialogController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'appServices',
            'EditUserData',
            function ($scope, __DataStore, __Form, $stateParams, appServices, EditUserData) {

                var scope = this,
                    requestData = EditUserData,
                    ngDialogData = $scope.ngDialogData;

                scope.userRoles = requestData.userRoles;
                scope = __Form.setup(scope, 'user_edit_form', 'userData');
                scope = __Form.updateModel(scope, requestData.userUpdateData);

                /*
                 Submit form action
                -------------------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process({
                        'apiURL': 'manage.user.write.update_process',
                        'userId': ngDialogData.userId
                    }, scope)
                        .success(function (responseData) {
                            appServices.processResponse(responseData, null, function () {
                                // close dialog
                                $scope.closeThisDialog({ 'user_updated': true });
                            });
                        });
                };

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };
            }
        ])

})(window, window.angular);;
/*!
*  Component  : Users
*  File       : UserDataService.js  
*  Engine     : UserDataService 
----------------------------------------------------------------------------- */
(function (window, angular, undefined) {

    'use strict';

    angular
        .module('ManageApp.UserDataService', [])

        /**
          User Data Service  
        ---------------------------------------------------------------- */
        .service('UserDataService', [
            '$q',
            '__DataStore',
            '__Form',
            'appServices',
            UserDataService
        ]);

    function UserDataService($q, __DataStore, __Form, appServices) {

        /*
        Get Login attempts 
        -----------------------------------------------------------------*/
        this.getLoginAttempts = function () {

            //create a differed object          
            var defferedObject = $q.defer();

            __Form.fetch('user.login.attempts')
                .success(function (responseData) {

                    appServices.processResponse(responseData, null, function (reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData);

                    });

                });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get Countries List 
        -----------------------------------------------------------------*/
        this.getCountries = function () {

            //create a differed object          
            var defferedObject = $q.defer();

            __DataStore.fetch('user.get.country_list')
                .success(function (responseData) {

                    appServices.processResponse(responseData, null, function (reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData);

                    });

                });

            //return promise to caller          
            return defferedObject.promise;
        };
    }
    ;
})(window, window.angular);;
/*!
*  Component  : User
*  File       : UserEngine.js  
*  Engine     : UserEngine 
----------------------------------------------------------------------------- */
(function (window, angular, undefined) {

    'use strict';

    angular
        .module('Manage-app.users', [])

        /**
          * UserLoginController - login a user in application
          *
          * @inject __Form
          * @inject __Auth
          * @inject appServices
          * @inject __Utils
          * 
          * @return void
          *-------------------------------------------------------- */

        .controller('UserLoginController', [
            '__Form',
            '__Auth',
            'appServices',
            '__Utils',
            'UserDataService',
            '$state',
            '$rootScope',
            function (__Form, __Auth, appServices, __Utils, UserDataService, $state, $rootScope) {

                var scope = this;

                scope = __Form.setup(scope, 'form_user_login', 'loginData', {
                    secured: true
                });

                scope.show_captcha = false;
                scope.request_completed = false;

                /**
                  * Get login attempts for this client ip
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                UserDataService.getLoginAttempts()
                    .then(function (responseData) {
                        scope.show_captcha = responseData.data.show_captcha;
                        scope.site_key = responseData.data.site_key;
                        scope.request_completed = true;
                    });

                /**
                  * Fetch captch url
                  *
                  * @return string
                  *---------------------------------------------------------------- */

                scope.getCaptchaURL = function () {
                    return __Utils.apiURL('security.captcha') + '?ver=' + Math.random();
                };

                /**
                  * Refresh captch 
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.refreshCaptcha = function () {
                    scope.captchaURL = scope.getCaptchaURL();
                };

                scope.captchaURL = scope.getCaptchaURL();

                scope.redirectToIntended = function () {

                    if (__globals.intended && __globals.intended.name && __globals.intended.params) {

                        return $state.go(__globals.intended.name, __globals.intended.params);

                    } else if (__globals.intended && __globals.intended.name) {

                        return $state.go(__globals.intended.name);
                    }

                    return $state.go('dashboard');

                };

                /**
                * Submit login form action
                *
                * @return void
                *---------------------------------------------------------------- */

                scope.submit = function () {

                    scope.isInActive = false;
                    scope.accountDeleted = false;

                    __Form.process('user.login.process', scope).success(function (responseData) {

                        var requestData = responseData.data;

                        if (responseData.reaction != 1) {
                            scope[scope.ngFormModelName].recaptcha = null;
                        }

                        appServices.processResponse(responseData, {
                            error: function () {

                                scope.show_captcha = requestData.show_captcha;

                                // reset password field
                                scope[scope.ngFormModelName].password = "";

                                // Check if show captcha exist then refresh captcha
                                if (scope.show_captcha) {
                                    scope[scope.ngFormModelName].confirmation_code = "";
                                    scope.refreshCaptcha();
                                }

                            },
                            otherError: function (reactionCode) {

                                scope.isInActive = requestData.isInActive;
                                scope.accountDeleted = requestData.accountDeleted;

                                // If reaction code is Server Side Validation Error Then 
                                // Unset the form fields
                                if (reactionCode == 3) {

                                    // Check if show captcha exist then refresh captcha
                                    if (scope.show_captcha) {
                                        scope.refreshCaptcha();
                                    }

                                }

                                // If reaction code 10 is already authenticate.
                                if (reactionCode == 10) {

                                    // Check if show captcha exist then refresh captcha
                                    scope.redirectToIntended();
                                    //__globals.redirectBrowser(__Utils.apiURL('dashboard'));

                                }

                            }
                        },
                            function () {

                                __Auth.checkIn(requestData.auth_info, function () {

                                    //__globals.setCookie('auth_access_token', requestData.access_token);

                                    if (requestData.availableRoutes) {
                                        __globals.appImmutable('availableRoutes',
                                            requestData.availableRoutes);
                                    }

                                    if (requestData.ckeditor) {
                                        __globals.appImmutable('ckeditor', requestData.ckeditor);
                                    }


                                    if (requestData.intendedUrl) {

                                        __globals.redirectBrowser(requestData.intendedUrl);

                                    } else {

                                        /*if (requestData.auth_info.designation == 1) {

                                            __globals.redirectBrowser(__Utils.apiURL('manage.app'));

                                        } else {

                                            __globals.redirectBrowser(window.appConfig.appBaseURL);
                                        }*/
                                        //__globals.redirectBrowser(__Utils.apiURL('manage.app'));
                                        $rootScope.$emit('lw.events.logged_in_user', { data: true });

                                        scope.redirectToIntended();

                                    }

                                });
                            });

                    });

                };

            }

        ])


        /**
          * UserLogoutController for login logout
          *
          * @inject __DataStore
          * @inject __Auth
          * @inject appServices
          * 
          * @return void
          *-------------------------------------------------------- */
        .controller('UserLogoutController', [
            '__DataStore',
            '__Auth',
            'appServices',
            function UserLogoutController(__DataStore, __Auth, appServices) {

                var scope = this;

                __DataStore.post('user.logout').success(function (responseData) {

                    appServices.processResponse(responseData, function (reactionCode) {

                        // set user auth information
                        __Auth.checkIn(responseData.data.auth_info);

                    });

                });

            }
        ])

        /**
          * UserForgotPasswordController - request to send password reminder
          *
          * @inject __Form
          * @inject appServices
          * 
          * @return void
          *-------------------------------------------------------- */

        .controller('UserForgotPasswordController', [
            '__Form',
            'appServices',
            '__Utils',
            '$state',
            function (__Form, appServices, __Utils, $state) {

                var scope = this;


                scope = __Form.setup(scope, 'user_forgot_password_form', 'userData', {
                    secured: true
                });

                /**
                  * Submit form
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process('user.forgot_password.process', scope)
                        .success(function (responseData) {

                            appServices.processResponse(responseData, null, function () {

                                $state.go('forgot_password_sucess');

                            });

                        });

                };

            }

        ])

        /**
          * UserResetPasswordController for reset user password
          *
          * @inject __Form
          * @inject appServices
          * @inject __Utils
          * 
          * @return void
          *-------------------------------------------------------- */

        .controller('UserResetPasswordController', [
            '__Form',
            'appServices',
            '__Utils',
            '$state',
            function (__Form, appServices, __Utils, $state) {

                var scope = this;

                scope = __Form.setup(scope, 'user_reset_password_form', 'userData', {
                    secured: true
                });

                /**
                  * Submit reset password form action
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process({
                        'apiURL': 'user.reset_password.process',
                        'reminderToken': $state.params.reminderToken
                    }, scope)
                        .success(function (responseData) {

                            appServices.processResponse(responseData, null,
                                function (reactionCode) {
                                    $state.go('login');
                                });

                        });

                };

            }
        ])

        /**
          * UserContactController handle register form & send request to server
          * to submit form data. 
          *
          * @inject __Form
          * @inject $state
          * @inject appServices
          * 
          * @return void
          *-------------------------------------------------------- */
        .controller('UserContactController', [
            '__Form',
            '$state',
            'appServices',
            '__Utils',
            '__Auth',
            '$scope',
            function UserContactController(__Form, $state, appServices, __Utils, __Auth, $scope) {

                var scope = this;

                scope.showCaptcha = false;

                scope = __Form.setup(scope, 'user_contact_form', 'userData', {
                    secured: true,
                    unsecuredFields: ['message']
                });

                // additional option for ck-editor
                scope.AdditionalOptionOfCkeditor = {
                    toolbar: [
                        {
                            name: 'links',
                            items: [
                                'Link',
                                'Unlink',
                                'Anchor'
                            ]
                        },
                        {
                            name: 'insert',
                            items: ['Smiley']
                        }
                    ],
                    extraPlugins: 'smiley'
                };

                // get logged in user Info
                __Auth.refresh(function (authInfo) {
                    scope.auth_info = authInfo;
                });

                scope.isLoggedIn = scope.auth_info.authorized;

                // check if user is logged in or not
                if (!scope.isLoggedIn) {
                    scope.showCaptcha = true;
                }

                if (scope.auth_info.reaction_code != 9) { // not authenticate
                    scope.userData.email = scope.auth_info.profile.email;
                    scope.userData.name = scope.auth_info.profile.full_name;
                }

                if (!_.isEmpty($scope.ngDialogData)) {

                    scope.userData.orderUID = $scope.ngDialogData.orderUID;
                }

                /**
                  * Fetch captch url
                  *
                  * @return string
                  *---------------------------------------------------------------- */

                scope.getCaptchaURL = function () {
                    return __Utils.apiURL('security.captcha') + '?ver=' + Math.random();
                };

                /**
                  * Refresh captch 
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.refreshCaptcha = function () {
                    scope.captchaURL = scope.getCaptchaURL();
                };

                scope.captchaURL = scope.getCaptchaURL();


                /**
                  * Submit register form action
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.requestSuccess = false;
                scope.submit = function (formType) {

                    scope.userData.formType = formType;

                    __Form.process('user.contact.process', scope)
                        .success(function (responseData) {

                            appServices.processResponse(responseData, {
                                error: function () {

                                },
                                otherError: function () {
                                    // refresh captcha
                                    scope[scope.ngFormModelName].confirmation_code = "";
                                    scope.refreshCaptcha();
                                }
                            },
                                function () {

                                    scope.userData = '';

                                    CKEDITOR.instances['message'].setData('');


                                    // Check if form type dialog or form
                                    if (scope.userData.formType == 2) { // dialog

                                        $scope.closeThisDialog();

                                    }

                                    scope.requestSuccess = true;

                                    $('.lw-contact-form').slideUp();
                                });

                        });

                };

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };

            }
        ])

        /**
          * UserAddCountryController handle address form & send request to server
          * to submit form data. 
          *
          * @inject $scope
          * @inject __Form
          * @inject $state
          * @inject appServices
          * @inject __Utils
          * 
          * @return void
          *-------------------------------------------------------- */
        .controller('UserAddCountryController', [
            '$scope',
            '__Form',
            '$state',
            'appServices',
            '__Utils',
            'UserDataService',
            function UserAddCountryController($scope, __Form, $state, appServices, __Utils, UserDataService) {

                var scope = this;

                scope = __Form.setup(scope, 'user_country_add_form', 'userData');

                scope.request_completed = false;
                scope.countries = [];

                UserDataService.getCountries()
                    .then(function (responseData) {
                        var requestData = responseData.data;
                        scope.countries = requestData.countries;
                        scope.request_completed = true;
                    });

                scope.countrySelectConfig = __globals.getSelectizeOptions();

                /**
                  * Submit profile edit form action
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process('user.add.country.process', scope)
                        .success(function (responseData) {

                            appServices.processResponse(responseData, null, function () {

                                $scope.closeThisDialog({ 'countryAdded': true });

                            });

                        });

                };


                /**
                 * Close dialog and return promise object
                 *
                 * @return void
                 *---------------------------------------------------------------- */

                scope.close = function () {

                    $scope.closeThisDialog({ 'countryAdded': false });
                };

            }
        ])
})(window, window.angular);;
/*!
*  Component  : Configuration
*  File       : ConfigurationDataService.js  
*  Engine     : ConfigurationDataService 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('ManageApp.ConfigurationDataService', [])
        .service('ConfigurationDataService', [
            '$q',
            '__DataStore',
            'appServices',
            ConfigurationDataService
        ])

    /*!
     This service use for to get the promise on data
    ----------------------------------------------------------------------------- */

    function ConfigurationDataService($q, __DataStore, appServices) {

        /*
        Get the data of configuration
        -----------------------------------------------------------------*/

        this.readConfigurationData = function (formType) {

            //create a differed object          
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.configuration.get.support.data',
                'formType': formType // different form type like 1, 2,3,4 etc
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData);

                });

            });

            //return promise to caller          
            return defferedObject.promise;
        };

    };


})(window, window.angular);
;

/*!
*  Component  : Configuration
*  File       : ConfigurationEngine.js
*  Engine     : ConfigurationEngine
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('ManageApp.configuration', [])

        /**
         * GeneralDialogController for update request
         *
         * @inject $scope
         * @inject __DataStore
         * @inject appServices
         * @inject __Form
         *
         * @return void
         *-------------------------------------------------------- */
        .controller('GeneralController', [
            '$scope',
            '__Form',
            '$state',
            'appServices',
            'lwFileUploader',
            '__Utils',
            '$rootScope',
            'getGeneralData',
            function GeneralController(
                $scope, __Form, $state, appServices, lwFileUploader, __Utils, $rootScope, getGeneralData
            ) {

                var scope = this;
                // scope.default_header_background_color = getGeneralData.data.configuration.default_header_background_color;
                //  scope.default_header_text_link_color = getGeneralData.data.configuration.default_header_text_link_color;
                //  scope.themeColors = getGeneralData.data.configuration.theme_colors;

                scope = __Form.setup(scope, 'general_edit', 'editData', {
                    secured: true,
                    modelUpdateWatcher: true,
                    unsecuredFields: ['logoURL', 'faviconURL', 'smallLogoURL']
                });

                scope.pageStatus = false;

                scope.timezone_select_config = __globals.getSelectizeOptions({
                    valueField: 'value',
                    labelField: 'text',
                    searchField: ['text']
                });

                scope.home_page_select_config = __globals.getSelectizeOptions({
                    valueField: 'id'
                });

                /*scope.selectSiteColor = function(themeColor) {
                    scope.editData.header_background_color = themeColor.background;
                    scope.editData.header_text_link_color = themeColor.text;
                }*/

                scope.checkLogo = function (from) {
                    var isSame = false;

                    if (scope.editData.invoice_logo_image == scope.editData.logo_image) {
                        isSame = true;
                    }

                    if (isSame) {
                        if (from == 1) { // Logo
                            scope.editData.invoice_logo_image = '';
                        } else if (from == 2) { // Invoice Logo
                            scope.editData.logo_image = '';
                        }
                    }
                };

                /**
                  * Clear Color 
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                /*scope.clearColor = function() {
                    // var logo_background_color = ngDialogData.responseData.data.configuration.logo_background_color;
                    scope.editData.header_background_color = scope.default_header_background_color;
                	
                }*/

                /**
                  * Clear Color 
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                /*scope.clearPrimaryColor = function() {
                    scope.editData.header_text_link_color = scope.default_header_text_link_color;
                }*/

                /**
                  * Fetch support data
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                var requestData = getGeneralData.data;
                scope.timezoneData = requestData.configuration.timezone_list;
                scope.homePageData = __globals.generateKeyValueItems(requestData.configuration.home_page_list);

                scope.languages = requestData.configuration.locale_list;
                var configuration = requestData.configuration;

                __Form.updateModel(scope, configuration);

                scope.pageStatus = true;

                scope.imagesSelectConfig = __globals.getSelectizeOptions({
                    valueField: 'name',
                    labelField: 'name',
                    render: {
                        item: function (item, escape) {
                            return __Utils.template('#imageListItemTemplate',
                                item
                            );
                        },
                        option: function (item, escape) {
                            return __Utils.template('#imageListOptionTemplate',
                                item
                            );
                        }
                    },
                    searchField: ['name']
                });


                /**
                  * Retrieve files required for account logo
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.retrieveSpecificFiles = function () {

                    lwFileUploader.getTempUploadedFiles(scope, {
                        'url': __Utils.apiURL('media.upload.read_logo')
                    }, function (uploadedFile) {
                        scope.logoFiles = uploadedFile;
                        scope.logoFilesCount = uploadedFile.length;
                    });

                };
                scope.retrieveSpecificFiles();

                scope.retrieveFaviconFiles = function () {
                    lwFileUploader.getTempUploadedFiles(scope, {
                        'url': __Utils.apiURL('media.upload.read_favicon')
                    }, function (uploadedFile) {
                        scope.faviconFiles = uploadedFile;
                        scope.faviconFilesCount = uploadedFile.length;
                    });
                };

                scope.retrieveFaviconFiles();

                $rootScope.$on('lw-loader-event-start', function (event, data) {
                    $scope.loading = true;
                    $("#lwFileupload").attr("disabled", true);
                });

                $rootScope.$on('lw-loader-event-stop', function (event, data) {
                    $scope.loading = false;
                    $("#lwFileupload").attr("disabled", false);
                });

                // uploader file instance
                $scope.upload = function () {

                    lwFileUploader.upload({
                        'url': __Utils.apiURL('media.upload.write.logo')
                    }, function (response) {

                        scope.retrieveSpecificFiles();
                        scope.retrieveFaviconFiles();

                    });
                };

                /**
                  * Show uploaded media files
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                $scope.showUploadedMediaDialog = function () {

                    lwFileUploader.openDialog(scope, {
                        'url': __Utils.apiURL('media.upload.read_logo')
                    },
                        function (promiseObject) {
                            scope.retrieveSpecificFiles();
                            scope.retrieveFaviconFiles();
                        });

                };

                /**
                  * update blog data
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process({
                        'apiURL': 'manage.configuration.process',
                        'formType': 1
                    }, scope)
                        .success(function (responseData) {

                            appServices.processResponse(responseData, null, function () {

                                var requestData = responseData.data;
                                if (requestData.showRealodButton == true) {
                                    __globals.showConfirmation({
                                        title: responseData.data.message,
                                        text: responseData.data.textMessage,
                                        type: "success",
                                        confirmButtonClass: "btn-success",
                                        confirmButtonText: $("#lwReloadBtnText").attr('data-message'),
                                        confirmButtonColor: "#337ab7",
                                    }, function () {
                                        location.reload();
                                    });
                                }
                            });
                        });
                };

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                var logo_background_color = getGeneralData.data.configuration.logo_background_color;

                scope.closeDialog = function () {

                    // $('#lwchangeHeaderColor').css('background', "#"+logo_background_color);
                    $scope.closeThisDialog();
                };
            }
        ])

        /**
         * CurrencyConfigurationController for manage currency of store
         *
         * @inject $scope
         * @inject __Form
         * @inject appServices
         *
         * @return void
         *-------------------------------------------------------- */
        .controller('CurrencyConfigurationController', [
            '$scope',
            '__Form',
            'appServices',
            'getCurrencyData',
            function CurrencyConfigurationController($scope, __Form, appServices, getCurrencyData) {

                var scope = this,
                    ngDialogData = $scope.ngDialogData;

                scope.isZeroDecimalCurrency = false;

                /**
                  * Generate key value
                  *
                  * @param bool responseKeyValue
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.generateCurrenciesArray = function (currencies, responseKeyValue) {

                    if (!responseKeyValue) {
                        return currencies;
                    }

                    var currenciesArray = [];

                    _.forEach(currencies, function (value, key) {

                        currenciesArray.push({
                            'currency_code': key,
                            'currency_name': value.name
                        });

                    });

                    var $lwCurrencySettingTxtMsg = $('#lwCurrencySettingTxtMsg');

                    currenciesArray.push({
                        'currency_code': 'other',
                        'currency_name': $lwCurrencySettingTxtMsg.attr('other-text')
                    });

                    return currenciesArray;

                };

                /**
                  *  Check the the currency match with zero decimal
                  *
                  * @param array zeroDecimalCurrecies
                  * @param string selectedCurrencyValue
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.checkIsZeroDecimalCurrency = function (zeroDecimalCurrecies, selectedCurrencyValue) {

                    var isMatch = _.filter(zeroDecimalCurrecies, function (value, key) {

                        return (key === selectedCurrencyValue);
                    });

                    scope.isZeroDecimalCurrency = Boolean(isMatch.length);

                };

                /**
                  * Check if current currency is Paypal supported or not
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.checkIsPaypalSupported = function (currencyValue) {

                    var isPaypalSupported = _.filter(scope.options, function (value, key) {

                        return (key == currencyValue);
                    });

                    scope.isPaypalSupport = Boolean(isPaypalSupported.length);
                };

                /**
                  * format currency symbol and currency value
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.formatCurrency = function (currencySymbol, currency) {

                    _.defer(function () {

                        var $lwCurrencyFormat = $('#lwCurrencyFormat');

                        var string = $lwCurrencyFormat.attr('data-format');

                        scope.currency_format_preview = string.split('{__currencySymbol__}').join(currencySymbol)
                            .split('{__amount__}').join(100)
                            .split('{__currencyCode__}').join(currency);
                    });
                };

                scope.pageStatus = false;

                scope = __Form.setup(scope, 'edit_currency_configuration', 'editData', {
                    secured: true,
                    unsecuredFields: [
                        'currency_symbol',
                        'currency_format'
                    ]
                });

                scope.currencies_select_config = __globals.getSelectizeOptions({
                    valueField: 'currency_code',
                    labelField: 'currency_name',
                    searchField: ['currency_code', 'currency_name']
                });

                scope.multi_currencies_select_config = __globals.getSelectizeOptions({
                    valueField: 'currency_code',
                    labelField: 'currency_name',
                    searchField: ['currency_code', 'currency_name'],
                    plugins: ['remove_button'],
                    maxItems: 1000,
                    delimiter: ',',
                    persist: false
                });

                scope.is_support_paypal = true;


                var requestData = getCurrencyData.data,
                    currenciesData = requestData.configuration.currencies;

                scope.options = currenciesData.options;
                scope.currencies = currenciesData.details;
                scope.zeroDecimal = currenciesData.zero_decimal;
                /*scope.currencies_options
                        = scope.generateCurrenciesArray(currenciesData.details, true);*/

                _.defer(function () {
                    scope.currencies_options
                        = scope.generateCurrenciesArray(currenciesData.details, true);
                });

                scope.checkIsZeroDecimalCurrency(scope.zeroDecimal, requestData.configuration.currency_value);

                scope.checkIsPaypalSupported(requestData.configuration.currency);

                scope.default_currency_format = requestData.configuration.default_currency_format;

                scope.placeholders = requestData.placeholders;
                scope = __Form.updateModel(scope, requestData.configuration);

                _.forEach(scope.currencies, function (currencyObj, key) {

                    if (key == scope.editData.currency_value) {
                        scope.currencySymbol = currencyObj.symbol;
                    }
                });

                if (requestData.configuration.currency == 'other') {
                    scope.currencySymbol = requestData.configuration.currency_symbol;
                }

                scope.formatCurrency(scope.currencySymbol, scope.editData.currency_value);


                scope.pageStatus = true;


                /**
                  * Use default format for currency
                  *
                  * @param string defaultCurrencyFormat
                  *
                  * @return string
                  *---------------------------------------------------------------- */
                scope.useDefaultFormat = function (defaultCurrencyFormat, currency_symbol, currency_value) {

                    scope.editData.currency_format = defaultCurrencyFormat;

                    var string = scope.editData.currency_format;

                    scope.currency_format_preview = string.split('{__currencySymbol__}').join(currency_symbol)
                        .split('{__amount__}').join(100)
                        .split('{__currencyCode__}').join(currency_value);
                };


                /**
                  * Use default format for currency
                  *
                  * @param string defaultCurrencyFormat
                  *
                  * @return string
                  *---------------------------------------------------------------- */
                scope.updateCurrencyPreview = function (currency_symbol, currency_value) {

                    if (_.isUndefined(currency_symbol)) {
                        currency_symbol = '';
                    }

                    if (_.isUndefined(currency_value)) {
                        currency_value = '';
                    }

                    var $lwCurrencyFormat = $('#lwCurrencyFormat');

                    var string = $lwCurrencyFormat.attr('data-format');

                    scope.currency_format_preview = string.split('{__currencySymbol__}').join(currency_symbol)
                        .split('{__amount__}').join(100)
                        .split('{__currencyCode__}').join(currency_value);

                };

                /**
                  * Submit currency Data
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process({
                        'apiURL': 'manage.configuration.process',
                        'formType': 2 // currency
                    }, scope)
                        .success(function (responseData) {

                            appServices.processResponse(responseData, null, function () {

                                var requestData = responseData.data;

                                if (requestData.showRealodButton == true) {

                                    __globals.showConfirmation({
                                        title: responseData.data.message,
                                        text: responseData.data.textMessage,
                                        type: "success",
                                        showCancelButton: true,
                                        confirmButtonClass: "btn-success",
                                        confirmButtonText: $("#lwReloadBtnText").attr('data-message'),
                                        confirmButtonColor: "#337ab7"
                                    }, function () {

                                        location.reload();

                                    });
                                }
                            });

                        });
                };


                /**
                  * currency change
                  *
                  * @param selectedCurrency
                  * @return void
                  *---------------------------------------------------------------- */
                scope.currencyChange = function (selectedCurrency) {

                    scope.checkIsZeroDecimalCurrency(scope.zeroDecimal, selectedCurrency);

                    if (!_.isEmpty(selectedCurrency) && selectedCurrency != 'other') {

                        _.forEach(scope.currencies, function (currencyObj, key) {

                            if (key == selectedCurrency) {
                                scope.editData.currency_value = selectedCurrency;
                                scope.editData.currency_symbol = currencyObj.ASCII;
                                scope.currencySymbol = currencyObj.symbol;
                            }

                        });

                        scope.is_support_paypal = true;

                    } else {

                        scope.editData.currency_value = '';
                        scope.editData.currency_symbol = '';

                    }

                    scope.updateCurrencyPreview(scope.currencySymbol, scope.editData.currency_value);

                    scope.checkIsPaypalSupported(scope.editData.currency_value);

                };

                /**
                  * currency value change
                  *
                  * @param currencyValue
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.currencyValueChange = function (currencyValue) {

                    scope.checkIsZeroDecimalCurrency(scope.zeroDecimal, currencyValue);

                    if (!_.isEmpty(currencyValue) && currencyValue != 'other') {

                        var currency = {};
                        _.forEach(scope.currencies, function (currencyObj, key) {

                            if (key == currencyValue) {
                                currency = currencyObj;
                            }

                        });

                        if (_.isEmpty(currency)) {
                            //scope.is_support_paypal = false;
                            scope.editData.currency = 'other';
                        } else {
                            //scope.is_support_paypal     = true;
                            scope.editData.currency = currencyValue;
                            scope.editData.currency_symbol = currency.ASCII;
                            scope.currencySymbol = currency.symbol;
                        }

                    } else if (!_.isEmpty(currencyValue)) {

                        //scope.is_support_paypal     = false;
                        scope.editData.currency = 'other';

                    } else {

                        //scope.is_support_paypal  = true;
                        scope.editData.currency = '';

                    }

                    scope.checkIsPaypalSupported(currencyValue);

                    if (_.isUndefined(scope.editData.currency_value)) {
                        scope.currencySymbol = '';
                    }

                    scope.updateCurrencyPreview(scope.currencySymbol, scope.editData.currency_value);
                };

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog(scope.currencySymbol, scope.editData.currency);
                };
            }
        ])
        ;

})(window, window.angular);;
/*!
*  Component  : Dashboard
*  File       : DashboardDataService.js  
*  Engine     : DashboardDataService 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('ManageApp.DashboardDataService', [])
        .service('DashboardDataService', [
            '$q',
            '__DataStore',
            'appServices',
            DashboardDataService
        ])

    /*!
     This service use for to get the promise on data
    ----------------------------------------------------------------------------- */

    function DashboardDataService($q, __DataStore, appServices) {

        /*
        Get support data
        -----------------------------------------------------------------*/

        this.getSupportData = function (formType) {

            //create a differed object          
            var defferedObject = $q.defer();

            __DataStore.fetch('manage.dashboard.read.support_data')
                .success(function (responseData) {

                    appServices.processResponse(responseData, null, function (reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData.data);

                    });

                });

            //return promise to caller          
            return defferedObject.promise;
        };


        /*
         Get product search data
         -----------------------------------------------------------------*/

        this.getProductSearchData = function (searchTerm) {

            //create a differed object          
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.dashboard.read.search_products',
                'searchTerm': searchTerm
            }, { fresh: true }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData);

                });

            });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get support data
        -----------------------------------------------------------------*/

        this.getProductInventoryDetails = function (productId) {

            //create a differed object          
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.dashboard.read.product_inventory_details',
                'productId': productId
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);

                });

            });

            //return promise to caller          
            return defferedObject.promise;
        };
    };


})(window, window.angular);
;
/*!
*  Component  : Dashboard
*  File       : DashboardEngine.js  
*  Engine     : DashboardEngine 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('ManageApp.DashboardEngine', [])

        /**
          * Admin Dashboard Controller 
          *
          * @inject object $scope
          * @inject object DashboardDataService
          * @inject object __Form
          * @inject object $stateParams
          *
          * @return void
          *---------------------------------------------------------------- */

        .controller('AdminDashboardController', [
            '$scope',
            'GetDatshboardData',
            '$rootScope',
            'appServices',
            'DashboardDataService',
            'InventoryDataService',
            '__Utils',
            '$compile',
            function ($scope, GetDatshboardData, $rootScope, appServices, DashboardDataService, InventoryDataService, __Utils, $compile) {

                var scope = this;
                scope.pageStatus = false;

                scope.dashboardData = GetDatshboardData.dashboard.dashboardData;
                scope.myLocations = scope.dashboardData.myLocations;
                scope.isAdmin = scope.dashboardData.isAdmin;
                scope.options = [];

                $.typeahead({
                    input: '.lw-product-search-input',
                    minLength: 1,
                    order: "asc",
                    dynamic: true,
                    delay: 500,
                    hint: false,
                    searchOnFocus: false,
                    emptyTemplate: "no result found for '{{query}}' ",
                    template: function (query, item) {

                        return '<span>' +
                            '<span>{{product_name}}</span> ' +
                            '<span>{{name}}</span>' +
                            "</span>"
                    },
                    source: {
                        productList: {
                            display: ["product_name", "name", "barcode", "location_name"],
                            ajax: function (query, callback) {

                                return {
                                    type: "GET",
                                    path: "productList",
                                    url: __Utils.apiURL({
                                        'apiURL': 'manage.dashboard.read.search_products',
                                        'searchTerm': query
                                    }),
                                    callback: {
                                        done: function (responseData) {
                                            scope.options = responseData.data.productList;
                                            return responseData.data;
                                        }
                                    }
                                }
                            },
                        }
                    },
                    callback: {
                        onClick: function (node, a, item, event) {
                            event.preventDefault();

                            if (!_.isEmpty(item)) {
                                scope.updateInventory(item.id, null, null);
                            }

                        },
                    },
                    debug: true
                });

                /* scope.productSelectConfig = __globals.getSelectizeOptions({
                     valueField  : 'id',
                     labelField  : 'name',
                     searchField : ['name', 'product_name', 'barcode'],
                     options: [],
                     create: false,
                     loadThrottle: 600,
                     // render: {
                     //     option: function(item, escape) {
                     //     	__pr(item);
                     //         return $compile(__Utils.template('#lwSelectizeOp', {
                     //             item : item
                     //         }))(scope);
                     //     }
                     // },
                     load: function(searchTerm, callback) {
 
                         var $this = this;
 
                         if (searchTerm.length == 0) {
                             return callback();
                         }
                         _.defer(function() {
                                       DashboardDataService
                                 .getProductSearchData(searchTerm)
                                 .then(function(responseData) {
                                     scope.options = responseData.data.productList;
 
                                     if (scope.options.length == 1) {
                                         _.forEach(scope.options, function(item) {
                                             if (item.barcode == searchTerm) {
                                                 scope.updateInventory(item.id, null, null)
                                             }
                                         });
 
                                         $this.clearOptions();
                                     }
                                 	
                                     if (!_.isEmpty(scope.options)) {
                                         callback(responseData.data.productList);
                                     } else {
                                         callback();
                                         $this.blur();
                                         $this.clearOptions();
                                         $this.focus();
                                     }
                                 });
                         });
                     },
                     onChange : function(value) {
                           this.clearOptions();
                     },
                     onFocus  : function(value) {
                           this.clearOptions();
                     },
                 });*/



                /*
                Update Inventory
                ------------------------------------------------------------ */
                scope.updateInventory = function (combinationId, locationId, type) {

                    var productId = null,
                        productName = null,
                        supplierId = null;
                    _.forEach(scope.options, function (item) {
                        if (item.id == combinationId) {
                            productId = item.product_id;
                            productName = item.product_name;
                            supplierId = item.supplier_id;
                        }
                    });

                    if (_.isUndefined(combinationId)) {
                        return false;
                    }

                    appServices.showDialog(
                        {
                            productName: productName,
                            productId: productId,
                            type: type,
                            combinationId: parseInt(combinationId),
                            supplierId: supplierId,
                            showProductList: false
                        },
                        {
                            templateUrl: "inventory.update-inventory-dialog",
                            controller: 'UpdateInventoryController as UpdateInventoryCtrl',
                            resolve: {
                                InventoryUpdateData: function () {
                                    return InventoryDataService
                                        .getInventoryUpdateData(productId, combinationId, locationId, supplierId);
                                }
                            }
                        },
                        function (promiseObj) {
                            if (_.has(promiseObj.value, 'inventory_updated')
                                && promiseObj.value.inventory_updated) {
                                //scope.getInventories(null);
                            }
                        });
                }
            }
        ])

        /**
          * Inventory Detail Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('InventoryDetailController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'InventoryDataService',
            'appServices',
            'DashboardDataService',
            function ($scope, __DataStore, __Form, $stateParams, InventoryDataService, appServices, DashboardDataService) {

                var scope = this;
                scope.initialContentLoaded = false;

                scope.getproductInventoryDetails = function () {
                    DashboardDataService
                        .getProductInventoryDetails($scope.ngDialogData.productId)
                        .then(function (responseData) {
                            scope.inventoryData = responseData.invetoryData;
                            scope.initialContentLoaded = true;
                        });
                }
                scope.getproductInventoryDetails();

                /*
                Update Inventory
                ------------------------------------------------------------ */
                scope.updateInventory = function (productId, productName, comboKey, locationId, type, supplierId) {
                    appServices.showDialog(
                        {
                            productName: productName,
                            productId: productId,
                            type: type
                        },
                        {
                            templateUrl: "inventory.update-inventory-dialog",
                            controller: 'UpdateInventoryController as UpdateInventoryCtrl',
                            resolve: {
                                InventoryUpdateData: function () {
                                    return InventoryDataService
                                        .getInventoryUpdateData(productId, comboKey, locationId, supplierId);
                                }
                            }
                        },
                        function (promiseObj) {
                            if (_.has(promiseObj.value, 'inventory_updated')
                                && promiseObj.value.inventory_updated) {
                                scope.getproductInventoryDetails();
                            }
                        });
                }

                /**
                  * Close dialog
                  *
                  * @return  void
                  *---------------------------------------------------------------- */
                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };
            }
        ])
        ;

})(window, window.angular);;
/*!
*  Component  : RolePermission
*  File       : RolePermissionDataServices.js  
*  Engine     : RolePermissionServices 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.RolePermissionDataServices', [])
        .service('RolePermissionDataService', [
            '$q',
            '__DataStore',
            'appServices',
            RolePermissionDataService
        ])

    /*!
     This service use for to get the promise on data
    ----------------------------------------------------------------------------- */

    function RolePermissionDataService($q, __DataStore, appServices) {

        /*
        Get Permissions
        -----------------------------------------------------------------*/
        this.getPermissions = function (roleId) {

            //create a differed object          
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.user.role_permission.read',
                'roleId': roleId
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);

                });

            });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get Add Role Support Data
        -----------------------------------------------------------------*/
        this.getAddSupportData = function () {

            //create a differed object          
            var defferedObject = $q.defer();

            __DataStore.fetch('manage.user.role_permission.read.add_support_data')
                .success(function (responseData) {

                    appServices.processResponse(responseData, null, function (reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData.data);

                    });

                });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get add support Data 
        -----------------------------------------------------------------*/
        this.getAllPermissionsById = function (roleId) {

            //create a differed object          
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.user.role_permission.read.using_id',
                'roleId': roleId
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);

                });

            });

            //return promise to caller          
            return defferedObject.promise;
        };

    };

})(window, window.angular);
;
/*!
*  Component  : RolePermission
*  File       : RolePermission.js  
*  Engine     : RolePermission 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.RolePermissionEngine', [])

        /**
          * Role Permission Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('RolePermissionController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            function ($scope, __DataStore, __Form, $stateParams) {

                var scope = this;

            }
        ])


        /**
        * Role Permission List Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object RolePermissionDataService
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('RolePermissionListController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'RolePermissionDataService',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, RolePermissionDataService) {
                var dtColumnsData = [
                    {
                        "name": "title",
                        "orderable": true,
                    },
                    {
                        "name": null,
                        "template": "#rolePermissionActionColumnTemplate"
                    }
                ],
                    scope = this;

                /**
                * Get general user test as a datatable source object  
                *
                * @return  void
                *---------------------------------------------------------- */

                scope.rolePermissionDataTable = __DataStore.dataTable('#lwrolePermissionList', {
                    url: 'manage.user.role_permission.read.list',
                    dtOptions: {
                        "searching": true,
                        "pageLength": 25
                    },
                    columnsData: dtColumnsData,
                    scope: $scope
                });

                /*
                Reload current datatable
                ------------------------------------------------------------ */
                scope.reloadDT = function () {
                    __DataStore.reloadDT(scope.rolePermissionDataTable);
                };

                /**
                 * rolePermission delete 
                 *
                 * inject rolePermissionIdUid
                 *
                 * @return    void
                 *---------------------------------------------------------------- */

                scope.delete = function (rolePermissionIdOrUid, name) {

                    var $lwRolePermissionDeleteTextMsg = $('#lwRolePermissionDeleteTextMsg');

                    __globals.showConfirmation({
                        html: __globals.getReplacedString($lwRolePermissionDeleteTextMsg,
                            '__name__',
                            _.unescape(name)
                        ),
                        confirmButtonText: $lwRolePermissionDeleteTextMsg.attr('data-delete-button-text')
                    }, function () {

                        __DataStore.post({
                            'apiURL': 'manage.user.role_permission.write.delete',
                            'rolePermissionIdOrUid': rolePermissionIdOrUid
                        }).success(function (responseData) {

                            var message = responseData.data.message;

                            appServices.processResponse(responseData, {

                                error: function (data) {
                                    __globals.showConfirmation({
                                        title: $lwRolePermissionDeleteTextMsg.attr('data-error-text'),
                                        text: message,
                                        type: 'error'
                                    });
                                }

                            }, function (data) {
                                __globals.showConfirmation({
                                    title: $lwRolePermissionDeleteTextMsg.attr('data-success-text'),
                                    text: message,
                                    type: 'success'
                                });
                                scope.reloadDT();
                            });

                        });

                    });
                };

                /**
                * Show add new role dialog 
                *
                * @return    void
                *---------------------------------------------------------------- */
                scope.showAddNewDialog = function () {

                    appServices.showDialog({},
                        {
                            templateUrl: __globals.getTemplateURL(
                                'user.role-permission.add-dialog'
                            ),
                            controller: 'AddRoleController as addRoleCtrl',
                            resolve: {
                                addSupportData: function () {
                                    return RolePermissionDataService
                                        .getAddSupportData();
                                }
                            }
                        },
                        function (promiseObj) {
                            if (_.has(promiseObj.value, 'role_Added')
                                && (promiseObj.value.role_Added === true)) {
                                scope.reloadDT();
                            }
                        });
                };

                /**
                  * Role Permission Dialog 
                  *
                  * inject roleId
                  *
                  * @return    void
                  *---------------------------------------------------------------- */
                scope.rolePermissionDialog = function (roleId, title) {

                    appServices.showDialog({
                        'roleId': roleId,
                        'title': _.unescape(title)
                    },
                        {
                            templateUrl: __globals.getTemplateURL(
                                'user.role-permission.dynamic-role-permissions'
                            ),
                            controller: 'DynamicRolePermissionController as DynamicRolePermissionCtrl',
                            resolve: {
                                permissionData: function () {
                                    return RolePermissionDataService
                                        .getPermissions(roleId);
                                }
                            }
                        },
                        function (promiseObj) {

                        });
                };
            }
        ])
        // Role Permission List Controller ends here

        /**
          * Dynamic Role Permission Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('DynamicRolePermissionController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'appServices',
            'permissionData',
            function ($scope, __DataStore, __Form, $stateParams, appServices, permissionData) {
                var scope = this,
                    ngDialog = $scope.ngDialogData,
                    roleId = ngDialog.roleId;

                scope = __Form.setup(scope, 'user_role_dynamic_access', 'accessData', {
                    secured: true,
                    unsecuredFields: []
                });

                scope.title = ngDialog.title;
                scope.permissions = permissionData.permissions;

                scope.accessData.allow_permissions = permissionData.allow_permissions;
                scope.accessData.deny_permissions = permissionData.deny_permissions;
                scope.checkedPermission = {};

                scope.disablePermissions = function (eachPermission, permissionID) {

                    _.map(eachPermission.children, function (key) {
                        if (_.includes(key.dependencies, permissionID)) {
                            _.delay(function (text) {
                                $('input[name="' + key.id + '"]').attr('disabled', true);
                            }, 500);
                        }
                    });

                }

                _.map(scope.accessData.allow_permissions, function (permission) {
                    scope.checkedPermission[permission] = "2";
                })
                _.map(scope.accessData.deny_permissions, function (permission) {
                    scope.checkedPermission[permission] = "3";

                    _.map(scope.permissions, function (eachPermission) {

                        var pluckedIDs = _.pluck(eachPermission.children, 'id');

                        if (_.includes(pluckedIDs, permission)) {
                            scope.disablePermissions(eachPermission, permission)
                        }

                        if (_.has(eachPermission, 'children_permission_group')) {

                            _.map(eachPermission.children_permission_group, function (groupchild) {

                                var pluckedIDs = _.pluck(groupchild.children, 'id');

                                if (_.includes(pluckedIDs, permission)) {
                                    scope.disablePermissions(groupchild, permission)
                                }
                            });
                        }
                    });
                })

                scope = __Form.updateModel(scope, scope.accessData);

                //for updating permissions
                scope.checkPermission = function (childId, status) {

                    if (!_.isString(status)) {
                        status = status.toString();
                    }

                    scope.checkedPermission[childId] = status;

                    if (status == "2") {
                        if (!_.includes(scope.accessData.allow_permissions, childId)) {
                            scope.accessData.allow_permissions.push(childId);
                        }
                        if (_.includes(scope.accessData.deny_permissions, childId)) {
                            scope.accessData.deny_permissions = _.without(scope.accessData.deny_permissions, childId);
                        }
                    } else if (status == "3") {

                        if (!_.includes(scope.accessData.deny_permissions, childId)) {
                            scope.accessData.deny_permissions.push(childId);
                        }
                        if (_.includes(scope.accessData.allow_permissions, childId)) {
                            scope.accessData.allow_permissions = _.without(scope.accessData.allow_permissions, childId);
                        }
                    } else {

                        if (_.includes(scope.accessData.deny_permissions, childId)) {
                            scope.accessData.deny_permissions = _.without(scope.accessData.deny_permissions, childId);
                        }
                        if (_.includes(scope.accessData.allow_permissions, childId)) {
                            scope.accessData.allow_permissions = _.without(scope.accessData.allow_permissions, childId);
                        }
                    }

                    _.map(scope.permissions, function (permission) {

                        var pluckedIDs = _.pluck(permission.children, 'id'),
                            keyPermissions = [];
                        if (_.includes(pluckedIDs, childId) && permission.children[0].id != childId) {
                            // _.map(permission.children, function(key) {

                            // 	if (permission.children[0].id != key.id && !_.isUndefined(scope.checkedPermission[key.id])) {
                            // 		keyPermissions.push(scope.checkedPermission[key.id]);
                            // 	}
                            //     // if (key.id == childId && permission.children[0].id != childId) {
                            //     //     _.map(key.dependencies, function(dependency) {
                            //     //         scope.checkedPermission[dependency] = "2";
                            //     //     })
                            //     // }
                            // });

                            // scope.checkedPermission[permission.children[0].id] = "3";

                            // if (_.includes(keyPermissions, "2")) {
                            // 	scope.checkedPermission[permission.children[0].id] = "2";
                            // }

                        } else if (_.includes(pluckedIDs, childId) && permission.children[0].id == childId) {

                            _.map(permission.children, function (key) {

                                if (key.id != permission.children[0].id) {
                                    _.map(key.dependencies, function (dependency) {

                                        if (_.includes(key.dependencies, childId) && status == "3") {

                                            $('input[name="' + key.id + '"]').attr('disabled', true);

                                        } else {
                                            $('input[name="' + key.id + '"]').attr('disabled', false);

                                        }
                                    });
                                }
                            })
                        }

                        if (_.has(permission, 'children_permission_group')) {
                            _.map(permission.children_permission_group, function (groupchild) {

                                var pluckedGroupChildIDs = _.pluck(groupchild.children, 'id'),
                                    keyPermissionsGroup = [];

                                if (_.includes(pluckedGroupChildIDs, childId) && groupchild.children[0].id != childId) {
                                    //                            _.map(groupchild.children, function(groupchildkey) {
                                    //                                // if (groupchildkey.id == childId && groupchild.children[0].id != childId) {
                                    //                                //     _.map(groupchildkey.dependencies, function(dependency) {
                                    //                                //         scope.checkedPermission[dependency] = "2";
                                    //                                //     })
                                    //                                // }

                                    //                                if (groupchild.children[0].id != groupchildkey.id && !_.isUndefined(scope.checkedPermission[groupchildkey.id])) {
                                    //                      		keyPermissionsGroup.push(scope.checkedPermission[groupchildkey.id]);
                                    //                      	}

                                    //                            });

                                    //                            scope.checkedPermission[groupchild.children[0].id] = "3";

                                    // if (_.includes(keyPermissionsGroup, "2")) {
                                    // 	scope.checkedPermission[groupchild.children[0].id] = "2";
                                    // }

                                } else if (_.includes(pluckedGroupChildIDs, childId) && groupchild.children[0].id == childId) {

                                    _.map(groupchild.children, function (key2) {

                                        if (key2.id != groupchild.children[0].id) {
                                            _.map(key2.dependencies, function (dependency) {

                                                if (_.includes(key2.dependencies, childId) && status == "3") {

                                                    $('input[name="' + key2.id + '"]').attr('disabled', true);

                                                } else {
                                                    $('input[name="' + key2.id + '"]').attr('disabled', false);

                                                }
                                            })
                                        }
                                    });
                                }
                            })
                        }
                    })
                }

                // scope.preparePermissionData = function() {
                //     scope.accessData.allow_permissions = [];
                //     scope.accessData.deny_permissions = [];

                //     if (!_.isEmpty(scope.accessData.selected_permissions)) {
                //         _.forEach(scope.accessData.selected_permissions, function(item) {
                //             var number = item.split("_").pop();
                //             if (number == 2) {
                //                 scope.accessData.allow_permissions.push(_.trimRight(item, '_'+number));
                //             } else if (number == 3) {
                //                 scope.accessData.deny_permissions.push(_.trimRight(item, '_'+number));
                //             }
                //         });
                //     }                    
                // }

                /*
                 Submit form action
                -------------------------------------------------------------------------- */
                //           scope.filterPermissions = function(match) {

                //         		var treeInstance = $("#permissionTree").fancytree("getTree"),
                //         			filteredNodes,
                //           		filteredbranches,
                //        opts = {
                //       	'autoApply' : true,
                // 	'autoExpand' : true,
                // 	'fuzzy' : false,
                // 	'hideExpanders' : true,
                // 	'highlight' : true,
                // 	'leavesOnly' : true,
                // 	'nodata' : 'No results found.',
                // 	'mode' : "hide",
                // 	'counter': true,
                //       };

                // 	// Pass function to perform match
                // filteredNodes = treeInstance.filterNodes(match, opts);
                // filteredbranches = treeInstance.filterBranches(match, opts);
                //             }

                /*
                 Submit form action
                -------------------------------------------------------------------------- */

                scope.submit = function () {
                    // scope.preparePermissionData();
                    __Form.process({
                        'apiURL': 'manage.user.role_permission.write.create',
                        'roleId': roleId
                    }, scope)
                        .success(function (responseData) {
                            appServices.processResponse(responseData, null, function () {
                                // close dialog
                                $scope.closeThisDialog();
                            });
                        });
                };

                /*
                 * Check if value updated then enable and disable radio button according to 
                 * current radio button
                 *
                 * @param string name  
                 * @param number value
                 * @param array dependencies
                 * @param bool inheritStatus
                 *
                 * return array
                 * -------------------------------------------------------------------------- */
                scope.valueUpdated = function (name, value, dependencies, inheritStatus) {

                    _.forEach(scope.accessData.permissions, function (permission) {
                        if (permission[0].name == name) {

                            if (permission[0].allow == 2) { //Allow

                                _.map(permission, function (item) {
                                    if (!_.isEmpty(item.dependencies)) {
                                        item.disabled = false;
                                    }
                                });

                            } else if (permission[0].allow == 3) { // Deny

                                _.map(permission, function (item) {
                                    if (!_.isEmpty(item.dependencies)) {
                                        item.disabled = true;
                                        item.allow = 3;
                                    }
                                });

                            } else if (permission[0].allow == 1) { // Inherited

                                if (permission[0].currentStatus) {

                                    _.map(permission, function (item) {
                                        if (!_.isEmpty(item.dependencies)) {
                                            item.disabled = false;
                                            item.allow = 1;
                                        }
                                    });

                                } else {

                                    _.map(permission, function (item) {
                                        if (!_.isEmpty(item.dependencies)) {
                                            item.disabled = true;
                                            item.allow = 1;
                                        }
                                    });
                                }
                            }
                        }
                    });
                };

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };
            }
        ])

        /**
  * Add new Role Permission Controller 
  *
  * inject object $scope
  * inject object __DataStore
  * inject object __Form
  * inject object $stateParams
  *
  * @return  void
  *---------------------------------------------------------------- */

        .controller('AddRoleController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'addSupportData',
            'appServices',
            'RolePermissionDataService',
            function ($scope, __DataStore, __Form, $stateParams, addSupportData, appServices, RolePermissionDataService) {

                var scope = this;

                scope = __Form.setup(scope, 'add_role', 'roleData', {
                    secured: true,
                    unsecuredFields: []
                });

                scope.userRoles = addSupportData.userRoles;
                scope.permissions = addSupportData.permissionData;
                scope.roleData.allow_permissions = [];
                scope.roleData.deny_permissions = [];
                scope.checkedPermission = {};


                /*
                 Get Permission basis on the role id
                -------------------------------------------------------------------------- */
                scope.getPermissions = function (roleId) {

                    RolePermissionDataService
                        .getAllPermissionsById(roleId)
                        .then(function (responseData) {

                            scope.permissions = responseData.permissionData;
                            scope.roleData.selected_permissions = responseData.allowedData;

                            scope.roleData.allow_permissions = responseData.allow_permissions;
                            scope.roleData.deny_permissions = responseData.deny_permissions;
                            scope.checkedPermission = {};

                            _.map(scope.roleData.allow_permissions, function (permission) {
                                scope.checkedPermission[permission] = "2";
                            })
                            _.map(scope.roleData.deny_permissions, function (permission) {
                                scope.checkedPermission[permission] = "3";
                            })
                        })
                };

                //for updating permissions
                scope.checkPermission = function (childId, status) {

                    if (!_.isString(status)) {
                        status = status.toString();
                    }

                    scope.checkedPermission[childId] = status;

                    if (status == "2") {

                        if (!_.includes(scope.roleData.allow_permissions, childId)) {
                            scope.roleData.allow_permissions.push(childId);
                        }
                        if (_.includes(scope.roleData.deny_permissions, childId)) {
                            scope.roleData.deny_permissions = _.without(scope.roleData.deny_permissions, childId);
                        }

                    } else if (status == "3") {

                        if (!_.includes(scope.roleData.deny_permissions, childId)) {
                            scope.roleData.deny_permissions.push(childId);
                        }
                        if (_.includes(scope.roleData.allow_permissions, childId)) {
                            scope.roleData.allow_permissions = _.without(scope.roleData.allow_permissions, childId);
                        }
                    }

                    _.map(scope.permissions, function (permission) {

                        var pluckedIDs = _.pluck(permission.children, 'id'),
                            keyPermissions = [];
                        if (_.includes(pluckedIDs, childId) && permission.children[0].id != childId) {

                            // _.map(permission.children, function(key) {
                            // 	if (permission.children[0].id != key.id && !_.isUndefined(scope.checkedPermission[key.id])) {
                            // 		keyPermissions.push(scope.checkedPermission[key.id]);
                            // 	}

                            //     // if (key.id == childId && permission.children[0].id != childId) {
                            //     //     _.map(key.dependencies, function(dependency) {
                            //     //         scope.checkedPermission[dependency] = "2";
                            //     //     });
                            //     // }
                            // });

                            // scope.checkedPermission[permission.children[0].id] = "3";

                            // if (_.includes(keyPermissions, "2")) {
                            // 	scope.checkedPermission[permission.children[0].id] = "2";
                            // }

                        } else if (_.includes(pluckedIDs, childId) && permission.children[0].id == childId) {

                            _.map(permission.children, function (key) {

                                if (key.id != permission.children[0].id) {
                                    _.map(key.dependencies, function (dependency) {

                                        if (_.includes(key.dependencies, childId) && status == "3") {

                                            $('input[name="' + key.id + '"]').attr('disabled', true);

                                        } else {
                                            $('input[name="' + key.id + '"]').attr('disabled', false);

                                        }
                                    });
                                }
                            })
                        }

                        if (_.has(permission, 'children_permission_group')) {
                            _.map(permission.children_permission_group, function (groupchild) {

                                var pluckedGroupChildIDs = _.pluck(groupchild.children, 'id'),
                                    keyPermissionsGroup = [];

                                if (_.includes(pluckedGroupChildIDs, childId) && groupchild.children[0].id != childId) {
                                    //                            _.map(groupchild.children, function(groupchildkey) {

                                    //                            	if (groupchild.children[0].id != groupchildkey.id && !_.isUndefined(scope.checkedPermission[groupchildkey.id])) {
                                    //                      		keyPermissionsGroup.push(scope.checkedPermission[groupchildkey.id]);
                                    //                      	}

                                    //                                // if (groupchildkey.id == childId && groupchild.children[0].id != childId) {
                                    //                                //     _.map(groupchildkey.dependencies, function(dependency) {
                                    //                                //         scope.checkedPermission[dependency] = "2";
                                    //                                //     })
                                    //                                // }
                                    //                            });

                                    //                            scope.checkedPermission[groupchild.children[0].id] = "3";

                                    // if (_.includes(keyPermissionsGroup, "2")) {
                                    // 	scope.checkedPermission[groupchild.children[0].id] = "2";
                                    // }

                                } else if (_.includes(pluckedGroupChildIDs, childId) && groupchild.children[0].id == childId) {

                                    _.map(groupchild.children, function (key2) {

                                        if (key2.id != groupchild.children[0].id) {
                                            _.map(key2.dependencies, function (dependency) {

                                                if (_.includes(key2.dependencies, childId) && status == "3") {

                                                    $('input[name="' + key2.id + '"]').attr('disabled', true);

                                                } else {
                                                    $('input[name="' + key2.id + '"]').attr('disabled', false);

                                                }
                                            })
                                        }
                                    });
                                }

                            });
                        }
                    })
                }

                /*
                 Prepare Permissions
                -------------------------------------------------------------------------- */
                // scope.preparePermissions = function() {
                //     scope.roleData.allow_permissions = [];
                //     scope.roleData.deny_permissions = [];

                //     if (!_.isEmpty(scope.roleData.selected_permissions)) {
                //         _.forEach(scope.roleData.selected_permissions, function(item) {
                //             var number = item.split("_").pop();

                //             if (number == 2) {
                //                 scope.roleData.allow_permissions.push(_.trimRight(item, '_'+number));
                //             } else if (number == 3) {
                //                 scope.roleData.deny_permissions.push(_.trimRight(item, '_'+number));
                //             }
                //         });
                //     }  
                // }

                /*
                 Submit form action
                -------------------------------------------------------------------------- */
                scope.submit = function () {
                    // scope.preparePermissions();
                    __Form.process('manage.user.role_permission.write.role.create', scope)
                        .success(function (responseData) {
                            appServices.processResponse(responseData, null, function () {
                                // close dialog
                                $scope.closeThisDialog({ 'role_Added': true });
                            });
                        });
                };

                /**
                  * Close dialog
                  *
                  * @return void
                  *---------------------------------------------------------------- */
                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };
            }
        ])
        ;

})(window, window.angular);;
/*!
*  Component  : Dashboard
*  File       : ActivityDataServices.js  
*  Engine     : ActivityDataServices 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.ActivityDataServices', [])
        .service('ActivityDataServices', [
            '$q',
            '__DataStore',
            'appServices',
            ActivityDataServices
        ])

    /*!
     This service use for to get the promise on data
    ----------------------------------------------------------------------------- */

    function ActivityDataServices($q, __DataStore, appServices) {

    };


})(window, window.angular);
;
/*!
*  Component  : Activity
*  File       : ActivityEngine.js  
*  Engine     : ActivityEngine 
----------------------------------------------------------------------------- */
(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.ActivityEngine', [])

        /**
          * Calendar Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('ActivityLogListController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'appServices',
            'ActivityDataServices',
            function ($scope, __DataStore, __Form, $stateParams, appServices, ActivityDataServices) {
                var dtColumnsData = [
                    {
                        "name": "created_at",
                        "orderable": true,
                    },
                    {
                        "name": "created_by_user",
                        "orderable": true,
                    },
                    {
                        "name": "entity_type",
                        "orderable": true,
                    },
                    {
                        "name": "ip",
                        "orderable": false,
                    },
                    {
                        "name": 'activity'
                    },
                    {
                        "name": 'description'
                    }
                ],
                    scope = this;

                //form setup
                scope = __Form.setup(scope, 'activity_form_filters', 'activityLogsData', {
                    secured: false,
                    unsecuredFields: []
                });

                /**
                * Declare separate variables do not use non-repeatedly
                *
                * @return  void
                *---------------------------------------------------------- */
                scope.getDate = function (duration, dateFrom, dateTo) {
                    scope.startDate = dateFrom;
                    scope.endDate = dateTo;
                    scope.duration = duration;
                };

                /**
                * Start Date greater than end date ,then convert start date to end date
                *
                * @return  void
                *---------------------------------------------------------- */
                scope.changeDate = function (startDate, endDate) {

                    if (scope.startDate > scope.endDate) {

                        scope.endDate = scope.startDate;
                    }
                };

                /**
                * define all Variables data
                *
                * @return  void
                *---------------------------------------------------------- */
                var dateFrom, dateTo,

                    startCurrentMonth = moment().startOf('month').format('YYYY-MM-D'),
                    endCurrentMonth = moment().endOf('month').format('YYYY-MM-D'),

                    startLastMonth = moment().subtract(1, 'months').startOf('month').format('YYYY-MM-D'),
                    endLastMonth = moment().subtract(1, 'months').endOf('month').format('YYYY-MM-D'),

                    startCurrentWeek = moment().startOf('week').format('YYYY-MM-D'),
                    endCurrentWeek = moment().endOf('week').format('YYYY-MM-D'),

                    startLastWeek = moment().subtract(1, 'week').startOf('week').format('YYYY-MM-D'),
                    endLastWeek = moment().subtract(1, 'week').endOf('week').format('YYYY-MM-D'),

                    startToday = moment().startOf('day').format('YYYY-MM-D'),
                    endToday = moment().endOf('day').format('YYYY-MM-D'),

                    startYesterday = moment().subtract(1, 'day').format('YYYY-MM-D'),
                    endYesterday = moment().subtract(1, 'day').format('YYYY-MM-D'),

                    startLastYear = moment().subtract(1, 'years').startOf('years').format('YYYY-MM-D'),
                    endLastYear = moment().subtract(1, 'years').endOf('years').format('YYYY-MM-D'),

                    startCurrentYear = moment().startOf('years').format('YYYY-MM-D'),
                    endCurrentYear = moment().endOf('years').format('YYYY-MM-D'),

                    startLastThirtyDays = moment().subtract(30, 'days').format('YYYY-MM-D'),
                    endLastThirtyDays = moment().format('YYYY-MM-D');

                /**
                * Get the all Duration value and use moment library to fetch date
                *
                * @return  void
                *---------------------------------------------------------- */
                scope.activityDataTable = function (duration, startDate, endDate) {

                    switch (parseInt(duration)) {
                        case 1:
                            dateFrom = startCurrentMonth;
                            dateTo = endCurrentMonth;
                            scope.getDate(duration, dateFrom, dateTo);

                            break;

                        case 2:
                            dateFrom = startLastMonth;
                            dateTo = endLastMonth;
                            scope.getDate(duration, dateFrom, dateTo);

                            break;

                        case 3:
                            dateFrom = startCurrentWeek;
                            dateTo = endCurrentWeek;
                            scope.getDate(duration, dateFrom, dateTo);

                            break;

                        case 4:
                            dateFrom = startLastWeek;
                            dateTo = endLastWeek;
                            scope.getDate(duration, dateFrom, dateTo);

                            break;

                        case 5:
                            dateFrom = startToday;
                            dateTo = endToday;
                            scope.getDate(duration, dateFrom, dateTo);

                            break;

                        case 6:
                            dateFrom = startYesterday;
                            dateTo = endYesterday;
                            scope.getDate(duration, dateFrom, dateTo);

                            break;

                        case 7:
                            dateFrom = startLastYear;
                            dateTo = endLastYear;
                            scope.getDate(duration, dateFrom, dateTo);

                            break;
                        case 8:
                            dateFrom = startCurrentYear;
                            dateTo = endCurrentYear;
                            scope.getDate(duration, dateFrom, dateTo);

                            break;
                        case 9:
                            dateFrom = startLastThirtyDays;
                            dateTo = endLastThirtyDays;
                            scope.getDate(duration, dateFrom, dateTo);

                            break;
                        case 10:
                            var manipulateDate = "Add Custom date";
                            break;
                    };

                };

                /**
                * Request to server
                *
                * @return  void
                *---------------------------------------------------------- */
                scope.dateChange = function () {

                    if (scope.activityLogDataTable) {
                        scope.activityLogDataTable.destroy();
                    }

                    scope.activityLogDataTable = __DataStore.dataTable('#lwActivityLogList', {
                        url: {
                            'apiURL': 'manage.activity_log.read.list',
                            'startDate': scope.startDate,
                            'endDate': scope.endDate
                        },
                        dtOptions: {
                            "searching": true,
                            "order": [[0, 'desc']],
                            "pageLength": 25
                        },
                        columnsData: dtColumnsData,
                        scope: $scope
                    }, null, function (responseData) {
                        scope.durations = responseData._options.durations;
                    });
                };
                /*
                Reload current datatable
                ------------------------------------------------------------ */
                scope.reloadDT = function () {
                    __DataStore.reloadDT(scope.activityLogDataTable);
                };

                // when add new record 
                $scope.$on('activity_added_or_updated', function (data) {

                    if (data) {
                        // scope.reloadDT();
                        scope.dateChange('Today', moment().format('YYYY-MM-D'), moment().format('YYYY-MM-D'));
                    }

                });

                /**
                * Fire Event for caching start date is greater than end date.
                *
                * @return  void
                *---------------------------------------------------------- */
                $scope.$watch('activityLogListCtrl.startDate', function (currentValue, oldValue) {

                    var $element = angular.element('#endDate');

                    // Check if currentValue exist
                    if (_.isEmpty(currentValue)) {
                        $element.bootstrapMaterialDatePicker('setMinDate', '');
                    } else {
                        $element.bootstrapMaterialDatePicker('setMinDate', currentValue);
                    }

                });


                /**
                * Calling activityDataTable() function to get the current value.
                *
                * @return  void
                *---------------------------------------------------------- */
                scope.activityDataTable('1', moment().format('YYYY-MM-D'), moment().format('YYYY-MM-D'));

                scope.dateChange();
            }
        ])
        ;

})(window, window.angular);;
/*!
*  Component  : Category
*  File       : CategoryDataServices.js  
*  Engine     : CategoryServices 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.CategoryDataServices', [])
        .service('CategoryDataService', [
            '$q',
            '__DataStore',
            'appServices',
            CategoryDataService
        ])

    /*!
     This service use for to get the promise on data
    ----------------------------------------------------------------------------- */

    function CategoryDataService($q, __DataStore, appServices) {
        /*
        Get Edit Support Data
        -------------------------------------------------------------- */
        this.getEditSupportData = function (categoryId) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.category.read.update_data',
                'categoryId': categoryId
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };
    };

})(window, window.angular);
;
/*!
*  Component  : Category
*  File       : Category.js  
*  Engine     : Category 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.CategoryEngine', [])

        /**
          * Category List Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('CategoryListController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'appServices',
            'CategoryDataService',
            function ($scope, __DataStore, __Form, $stateParams, appServices, CategoryDataService) {

                var dtColumnsData = [
                    {
                        "name": "name",
                        "orderable": true
                    },
                    {
                        "name": "status",
                        "orderable": true,
                    },
                    {
                        "name": "created_at",
                        "orderable": true,
                    },
                    {
                        "name": null,
                        "template": "#categoryActionColumnTemplate"
                    }
                ],
                    scope = this;

                /**
                * Request to server
                *
                * @return  void
                *---------------------------------------------------------- */

                scope.categoryDataTable = __DataStore.dataTable('#lwCategoryList', {
                    url: 'manage.category.read.list',
                    dtOptions: {
                        "searching": true,
                        "order": [[2, "desc"]]
                    },
                    columnsData: dtColumnsData,
                    scope: $scope
                });

                /*
                Reload current datatable
                ------------------------------------------------------------ */
                scope.reloadDT = function () {
                    __DataStore.reloadDT(scope.categoryDataTable);
                };

                scope = __Form.setup(scope, 'category_add_form', 'categoryData');

                /**
                  * Submit form
                  *
                  * @return  void
                  *---------------------------------------------------------------- */
                scope.submit = function () {

                    __Form.process('manage.category.write.create', scope)
                        .success(function (responseData) {
                            appServices.processResponse(responseData, null, function (reaction) {
                                if (reaction == 1) {
                                    scope.categoryData.name = '';
                                    scope.reloadDT();
                                }
                            });

                        });
                };

                /*
                Open Category Edit Dialog
                ------------------------------------------------------------ */
                scope.openEditDialog = function (categoryId) {
                    appServices.showDialog({},
                        {
                            templateUrl: "category.edit-dialog",
                            controller: 'CategoryEditController as CategoryEditCtrl',
                            resolve: {
                                CategoryUpdateData: function () {
                                    return CategoryDataService
                                        .getEditSupportData(categoryId);
                                }
                            }
                        },
                        function (promiseObj) {
                            if (_.has(promiseObj.value, 'category_updated')
                                && promiseObj.value.category_updated) {
                                scope.reloadDT();
                            }
                        });
                }

                /**
                * category delete 
                *
                * inject issueIdUid
                *
                * @return    void
                *---------------------------------------------------------------- */

                scope.delete = function (categoryId, title) {

                    var $categoryDeleteConfirm = $('#categoryDeleteConfirm');

                    __globals.showConfirmation({
                        html: __globals.getReplacedString($categoryDeleteConfirm,
                            '__name__',
                            _.unescape(title)
                        ),
                        confirmButtonText: $categoryDeleteConfirm.attr('data-delete-button-text')
                    },
                        function () {

                            __DataStore.post({
                                'apiURL': 'manage.category.write.delete',
                                'categoryId': categoryId
                            })
                                .success(function (responseData) {

                                    var message = responseData.data.message;

                                    appServices.processResponse(responseData, {

                                        error: function (data) {
                                            __globals.showConfirmation({
                                                title: $categoryDeleteConfirm.attr('data-error-text'),
                                                text: message,
                                                type: 'error'
                                            });

                                        }

                                    },
                                        function (data) {

                                            __globals.showConfirmation({
                                                title: $categoryDeleteConfirm.attr('data-success-text'),
                                                text: message,
                                                type: 'success'
                                            });
                                            scope.reloadDT();   // reload datatable
                                        });
                                });
                        });
                };
            }
        ])

        /**
        * Category Edit Controller
        *
        * inject object $scope
        * inject object appServices
        * inject object __Form
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('CategoryEditController', [
            '$scope',
            'appServices',
            '__Form',
            'CategoryUpdateData',
            function ($scope, appServices, __Form, CategoryUpdateData) {

                var scope = this;
                scope = __Form.setup(scope, 'category_edit_form', 'categoryData');
                var category = CategoryUpdateData.updateData,
                    categoryId = category.id;

                scope = __Form.updateModel(scope, category);

                /**
                * Submit form
                *
                * @return  void
                *---------------------------------------------------------------- */

                scope.submit = function () {
                    __Form.process({
                        'apiURL': 'manage.category.write.update',
                        'categoryId': categoryId
                    }, scope).success(function (responseData) {

                        appServices.processResponse(responseData, null, function () {
                            $scope.closeThisDialog({ 'category_updated': true });
                        });
                    });

                };

                /**
                  * Close dialog
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };
            }
        ])
        ;

})(window, window.angular);;
/*!
*  Component  : Customer
*  File       : CustomerDataServices.js  
*  Engine     : CustomerServices 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.CustomerDataServices', [])
        .service('CustomerDataService', [
            '$q',
            '__DataStore',
            'appServices',
            CustomerDataService
        ])

    /*!
     This service use for to get the promise on data
    ----------------------------------------------------------------------------- */

    function CustomerDataService($q, __DataStore, appServices) {


        /*
        Get Add Support Data
        -------------------------------------------------------------- */
        this.getAddSupportData = function () {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch('manage.customer.read.support_data')
                .success(function (responseData) {

                    appServices.processResponse(responseData, null, function (reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData.data);
                    });
                });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get Edit Support Data
        -------------------------------------------------------------- */
        this.getEditSupportData = function (customerIdOrUid) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.customer.read.update.data',
                'customerIdOrUid': customerIdOrUid
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };


    };

})(window, window.angular);;
/*!
*  Component  : Customer
*  File       : Customer.js  
*  Engine     : Customer 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.CustomerEngine', [])

        /**
          * Customer Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('CustomerController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            function ($scope, __DataStore, __Form, $stateParams) {

                var scope = this;

            }
        ])


        /**
        * Customer List Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object CustomerDataService
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('CustomerListController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            'CustomerDataService',
            function ($scope, __DataStore, __Form, $state, appServices, CustomerDataService) {
                var dtColumnsData = [
                    {
                        "name": "name",
                        "orderable": true,
                    },
                    {
                        "name": "short_description",
                    },
                    {
                        "name": null,
                        "template": "#customerActionColumnTemplate"
                    }
                ],
                    scope = this;

                /**
                * Get general user test as a datatable source object  
                *
                * @return  void
                *---------------------------------------------------------- */

                scope.customerDataTable = __DataStore.dataTable('#lwcustomerList', {
                    url: 'manage.customer.read.list',
                    dtOptions: {
                        "searching": true
                    },
                    columnsData: dtColumnsData,
                    scope: $scope
                });

                /*
                Reload current datatable
                ------------------------------------------------------------ */
                scope.reloadDT = function () {
                    __DataStore.reloadDT(scope.customerDataTable);
                };

                /**
                 * customer delete 
                 *
                 * inject customerIdUid
                 *
                 * @return    void
                 *---------------------------------------------------------------- */

                scope.delete = function (customerIdOrUid, title) {

                    var $customerDeleteConfirm = $('#customerDeleteConfirm');

                    __globals.showConfirmation({
                        html: __globals.getReplacedString($customerDeleteConfirm,
                            '__name__',
                            _.unescape(title)
                        ),
                        confirmButtonText: $customerDeleteConfirm.attr('data-delete-button-text')
                    },
                        function () {

                            __DataStore.post({
                                'apiURL': 'manage.customer.write.delete',
                                'customerIdOrUid': customerIdOrUid
                            })
                                .success(function (responseData) {

                                    var message = responseData.data.message;

                                    appServices.processResponse(responseData, {

                                        error: function (data) {
                                            __globals.showConfirmation({
                                                title: $customerDeleteConfirm.attr('data-error-text'),
                                                text: message,
                                                type: 'error'
                                            });

                                        }

                                    },
                                        function (data) {

                                            __globals.showConfirmation({
                                                title: $customerDeleteConfirm.attr('data-success-text'),
                                                text: message,
                                                type: 'success'
                                            });
                                            scope.reloadDT();   // reload datatable
                                        });
                                });
                        });
                };

                /*
                add dialog
                ------------------------------------------------------------ */
                scope.openAddDialog = function () {

                    appServices.showDialog(scope, {
                        templateUrl: __globals.getTemplateURL("customer.add-dialog"),
                        controller: 'CustomerAddController as customerAddCtrl',
                        resolve: {
                            customerAddData: function () {
                                return CustomerDataService
                                    .getAddSupportData();
                            }
                        }
                    }, function (promiseObj) {
                        if (_.has(promiseObj.value, 'customer_added_or_updated')
                            && promiseObj.value.customer_added_or_updated) {
                            scope.reloadDT();
                        }
                    });

                };

                /*
                edit dialog
                ------------------------------------------------------------ */
                scope.openEditDialog = function (customerIdOrUid) {

                    appServices.showDialog({
                        'customerIdOrUid': customerIdOrUid
                    }, {
                        templateUrl: __globals.getTemplateURL("customer.edit-dialog"),
                        controller: 'CustomerEditController as customerEditCtrl',
                        resolve: {
                            customerEditData: function () {
                                return CustomerDataService
                                    .getEditSupportData(customerIdOrUid);
                            }
                        }
                    }, function (promiseObj) {

                        if (_.has(promiseObj.value, 'customer_added_or_updated')
                            && promiseObj.value.customer_added_or_updated) {
                            scope.reloadDT();
                        }
                    });
                };
            }
        ])
        // Customer List Controller ends here


        /**
        * Customer Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('CustomerAddController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'customerAddData',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, customerAddData) {

                var scope = this;

                scope.showLoader = true;
                scope = __Form.setup(scope, 'customer_form', 'customerData');

                scope.countries = customerAddData.countries;
                scope.countrySelectConfig = __globals.getSelectizeOptions();

                /**
                  * Submit form
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process('manage.customer.write.create', scope)
                        .success(function (responseData) {

                            appServices.processResponse(responseData, null, function () {
                                $scope.closeThisDialog({ 'customer_added_or_updated': true });
                            });

                        });
                };

                /**
                  * Close dialog
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };
            }
        ])
        // CustomerAddController ends here


        /**
        * Customer Edit Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object customerEditData
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('CustomerEditController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'customerEditData',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, customerEditData) {

                var scope = this;
                scope.showLoader = true;

                scope = __Form.setup(scope, 'customer_form', 'customerData');

                var requestData = customerEditData.customer;
                scope = __Form.updateModel(scope, requestData);
                scope.showLoader = false;

                var customerIdOrUid = $scope.ngDialogData.customerIdOrUid;
                scope.countries = customerEditData.countries;
                scope.countrySelectConfig = __globals.getSelectizeOptions();


                /**
                * Submit form
                *
                * @return  void
                *---------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process({
                        'apiURL': 'manage.customer.write.update',
                        'customerIdOrUid': customerIdOrUid
                    }, scope).success(function (responseData) {

                        appServices.processResponse(responseData, null, function () {
                            $scope.closeThisDialog({ 'customer_added_or_updated': true });
                        });
                    });

                };

                /**
                * Close dialog
                *
                * @return  void
                *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };

            }

        ])
        // Customer Edit Controller ends here

        ;

})(window, window.angular);;
/*!
*  Component  : Suppliers
*  File       : SuppliersDataServices.js  
*  Engine     : SuppliersServices 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.SuppliersDataServices', [])
        .service('SuppliersDataService', [
            '$q',
            '__DataStore',
            'appServices',
            SuppliersDataService
        ])

    /*!
     This service use for to get the promise on data
    ----------------------------------------------------------------------------- */

    function SuppliersDataService($q, __DataStore, appServices) {


        /*
        Get Edit Support Data
        -------------------------------------------------------------- */
        this.getEditSupportData = function (suppliersIdOrUid) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.suppliers.read.update.data',
                'suppliersIdOrUid': suppliersIdOrUid
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };


    };

})(window, window.angular);;
/*!
*  Component  : Suppliers
*  File       : Suppliers.js  
*  Engine     : Suppliers 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.SuppliersEngine', [])

        /**
          * Suppliers Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('SuppliersController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            function ($scope, __DataStore, __Form, $stateParams) {

                var scope = this;

            }
        ])


        /**
        * Suppliers List Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object SuppliersDataService
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('SuppliersListController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'SuppliersDataService',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, SuppliersDataService) {
                var dtColumnsData = [
                    {
                        "name": "name",
                        "orderable": true,
                    },
                    {
                        "name": "short_description",
                    },
                    {
                        "name": null,
                        "template": "#suppliersActionColumnTemplate"
                    }
                ],
                    scope = this;

                /**
                * Get general user test as a datatable source object  
                *
                * @return  void
                *---------------------------------------------------------- */

                scope.suppliersDataTable = __DataStore.dataTable('#lwsuppliersList', {
                    url: 'manage.suppliers.read.list',
                    dtOptions: {
                        "searching": true
                    },
                    columnsData: dtColumnsData,
                    scope: $scope
                });

                /*
                Reload current datatable
                ------------------------------------------------------------ */
                scope.reloadDT = function () {
                    __DataStore.reloadDT(scope.suppliersDataTable);
                };

                /**
                 * suppliers delete 
                 *
                 * inject suppliersIdUid
                 *
                 * @return    void
                 *---------------------------------------------------------------- */

                scope.delete = function (suppliersIdOrUid, title) {

                    var $suppliersDeleteConfirm = $('#suppliersDeleteConfirm');

                    __globals.showConfirmation({
                        html: __globals.getReplacedString($suppliersDeleteConfirm,
                            '__name__',
                            _.unescape(title)
                        ),
                        confirmButtonText: $suppliersDeleteConfirm.attr('data-delete-button-text')
                    },
                        function () {
                            __DataStore.post({
                                'apiURL': 'manage.suppliers.write.delete',
                                'suppliersIdOrUid': suppliersIdOrUid,
                            })
                                .success(function (responseData) {

                                    var message = responseData.data.message;

                                    appServices.processResponse(responseData, {
                                        error: function (data) {
                                            __globals.showConfirmation({
                                                title: $suppliersDeleteConfirm.attr('data-error-text'),
                                                text: message,
                                                type: 'error'
                                            });
                                        }
                                    },
                                        function (data) {
                                            __globals.showConfirmation({
                                                title: $suppliersDeleteConfirm.attr('data-success-text'),
                                                text: message,
                                                type: 'success'
                                            });
                                            scope.reloadDT();   // reload datatable
                                        });
                                });
                        });
                };


                /*
                add dialog
                ------------------------------------------------------------ */
                scope.openAddDialog = function () {

                    appServices.showDialog(scope, {
                        templateUrl: __globals.getTemplateURL("suppliers.add-dialog"),
                        controller: 'SuppliersAddController as suppliersAddCtrl'
                    }, function (promiseObj) {
                        if (_.has(promiseObj.value, 'suppliers_added_or_updated')
                            && promiseObj.value.suppliers_added_or_updated) {
                            scope.reloadDT();
                        }
                    });
                };

                /*
                edit dialog
                ------------------------------------------------------------ */
                scope.openEditDialog = function (suppliersIdOrUid) {

                    appServices.showDialog({
                        'suppliersIdOrUid': suppliersIdOrUid
                    }, {
                        templateUrl: __globals.getTemplateURL("suppliers.edit-dialog"),
                        controller: 'SuppliersEditController as suppliersEditCtrl',
                        resolve: {
                            suppliersEditData: function () {
                                return SuppliersDataService
                                    .getEditSupportData(suppliersIdOrUid);
                            }
                        }
                    }, function (promiseObj) {

                        if (_.has(promiseObj.value, 'suppliers_added_or_updated')
                            && promiseObj.value.suppliers_added_or_updated) {
                            scope.reloadDT();
                        }
                    });
                };


            }
        ])
        // Suppliers List Controller ends here


        /**
        * Suppliers Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('SuppliersAddController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope) {

                var scope = this;

                scope.showLoader = true;
                scope = __Form.setup(scope, 'suppliers_form', 'suppliersData');


                /**
                  * Submit form
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process('manage.suppliers.write.create', scope)
                        .success(function (responseData) {
                            var requestData = responseData.data;
                            appServices.processResponse(responseData, null, function () {
                                $scope.closeThisDialog({
                                    'suppliers_added_or_updated': true,
                                    'requestData': requestData
                                });
                            });

                        });
                };

                /**
                  * Close dialog
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };
            }
        ])
        // SuppliersAddController ends here


        /**
        * Suppliers Edit Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object suppliersEditData
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('SuppliersEditController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'suppliersEditData',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, suppliersEditData) {

                var scope = this;
                scope.showLoader = true;

                scope = __Form.setup(scope, 'suppliers_form', 'suppliersData');

                var requestData = suppliersEditData;
                scope = __Form.updateModel(scope, requestData);
                scope.showLoader = false;

                var suppliersIdOrUid = $scope.ngDialogData.suppliersIdOrUid;


                /**
                * Submit form
                *
                * @return  void
                *---------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process({
                        'apiURL': 'manage.suppliers.write.update',
                        'suppliersIdOrUid': suppliersIdOrUid
                    }, scope).success(function (responseData) {

                        appServices.processResponse(responseData, null, function () {
                            $scope.closeThisDialog({ 'suppliers_added_or_updated': true });
                        });
                    });

                };

                /**
                * Close dialog
                *
                * @return  void
                *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };

            }

        ])
        // Suppliers Edit Controller ends here

        ;

})(window, window.angular);;
/*!
*  Component  : Product
*  File       : ProductDataServices.js  
*  Engine     : ProductServices 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.ProductDataServices', [])
        .service('ProductDataService', [
            '$q',
            '__DataStore',
            'appServices',
            ProductDataService
        ])

    /*!
     This service use for to get the promise on data
    ----------------------------------------------------------------------------- */

    function ProductDataService($q, __DataStore, appServices) {


        /*
        Get Add Support Data
        -------------------------------------------------------------- */
        this.getAddSupportData = function () {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch('manage.product.read.support_data')
                .success(function (responseData) {

                    appServices.processResponse(responseData, null, function (reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData.data);
                    });
                });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get Edit Support Data
        -------------------------------------------------------------- */
        this.getEditSupportData = function (productIdOrUid) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.product.read.update.data',
                'productIdOrUid': productIdOrUid
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get Edit Support Data
        -------------------------------------------------------------- */
        this.getDetails = function (productIdOrUid) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.product.read.get_details',
                'productIdOrUid': productIdOrUid
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };

    };

})(window, window.angular);;
/*!
*  Component  : Product
*  File       : Product.js  
*  Engine     : Product 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.ProductEngine', [])

        /**
          * Product Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('ProductController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            function ($scope, __DataStore, __Form, $stateParams) {

                var scope = this;

            }
        ])



        /**
        * Product List Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object ProductDataService
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('ProductListController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'ProductDataService',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, ProductDataService) {

                var dtColumnsData = [
                    {
                        "name": "name",
                        "orderable": true,
                        "template": "#productNameColumnTemplate"
                    },
                    {
                        "name": "short_description",
                    },
                    {
                        "name": "category"
                    },
                    {
                        "name": "status",
                        "orderable": true,
                    },
                    {
                        "name": null,
                        "template": "#productActionColumnTemplate"
                    }
                ],
                    scope = this;

                /**
                * Get general user test as a datatable source object  
                *
                * @return  void
                *---------------------------------------------------------- */

                scope.productDataTable = __DataStore.dataTable('#lwproductList', {
                    url: 'manage.product.read.list',
                    dtOptions: {
                        "searching": true
                    },
                    columnsData: dtColumnsData,
                    scope: $scope
                });

                /*
                Reload current datatable
                ------------------------------------------------------------ */
                scope.reloadDT = function () {
                    __DataStore.reloadDT(scope.productDataTable);
                };

                // when add new record 
                $scope.$on('product_added_or_updated', function (data) {

                    if (data) {
                        scope.reloadDT();
                    }

                });

                /*
                details dialog
                ------------------------------------------------------------ */
                scope.openDetailsDialog = function (productUid) {

                    appServices.showDialog(scope, {
                        templateUrl: __globals.getTemplateURL("product.details-dialog"),
                        controller: 'ProductDetailsCotroller as ProductDetailsCtrl',
                        resolve: {
                            GetDetails: ["ProductDataService", function (ProductDataService) {
                                return ProductDataService.getDetails(productUid);
                            }]
                        }
                    }, function (promiseObj) {


                    });

                };

                /**
                 * product delete 
                 *
                 * inject productIdUid
                 *
                 * @return    void
                 *---------------------------------------------------------------- */

                scope.delete = function (productIdOrUid, title) {

                    var $productDeleteConfirm = $('#productDeleteConfirm');

                    __globals.showConfirmation({
                        html: __globals.getReplacedString($productDeleteConfirm,
                            '__name__',
                            _.unescape(title)
                        ),
                        confirmButtonText: $productDeleteConfirm.attr('data-delete-button-text')
                    },
                        function () {

                            __DataStore.post({
                                'apiURL': 'manage.product.write.delete',
                                'productIdOrUid': productIdOrUid,
                            })
                                .success(function (responseData) {

                                    var message = responseData.data.message;

                                    appServices.processResponse(responseData, {

                                        error: function (data) {
                                            __globals.showConfirmation({
                                                title: $productDeleteConfirm.attr('data-error-text'),
                                                text: message,
                                                type: 'error'
                                            });

                                        }

                                    },
                                        function (data) {

                                            __globals.showConfirmation({
                                                title: $productDeleteConfirm.attr('data-success-text'),
                                                text: message,
                                                type: 'success'
                                            });
                                            scope.reloadDT();   // reload datatable
                                        });
                                });
                        });
                };
            }
        ])
        // Product List Controller ends here


        /**
            * Product Add Controller
            *
            * inject object $scope
            * inject object __DataStore
            * inject object __Form
            * inject object $state
            * inject object appServices
            * inject object $rootScope
    	
            * inject object productAddData
            * @return  void
            *---------------------------------------------------------------- */
        .controller('ProductAddController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'productAddData',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, productAddData) {

                var scope = this;

                scope = __Form.setup(scope, 'product_form', 'productData');
                scope.categorySelectConfig = __globals.getSelectizeOptions();
                scope.supplierSelectConfig = __globals.getSelectizeOptions();
                scope.labelSelectConfig = __globals.getSelectizeOptions({
                    valueField: 'id',
                    labelField: 'name',
                    searchField: ['name'],
                    plugins: ['restore_on_backspace'],
                    create: function (input) {
                        return {
                            id: input,
                            name: input,
                        };
                    }
                });

                scope.barcodesData = [];

                scope.barcodesSelectConfig = __globals.getSelectizeOptions({
                    valueField: 'key',
                    labelField: 'value',
                    searchField: ['value'],
                    maxItems: 100,
                    plugins: ['remove_button'],
                    delimiter: ',',
                    persist: false,
                    create: function (input) {
                        if (scope.checkUniqueBarCode(input)) {
                            this.blur();
                            this.focus();
                            return {
                                key: null,
                                value: null,
                            };
                        } else {
                            return {
                                key: input,
                                value: input,
                            };
                        }
                    }
                });

                scope.presetSelectize = __globals.getSelectizeOptions();

                scope.categories = productAddData.categories;
                scope.suppliers = productAddData.suppliers;
                scope.currency = productAddData.currency;
                scope.currencySymbol = productAddData.currency_symbol;
                scope.taxPresets = productAddData.taxPresets;

                scope.labelData = productAddData.labelData;
                scope.productData.optionLabels = [];
                scope.productData.optionLabels = [
                    {
                        title: '',
                        product_id: '',
                        price: null,
                        barcodes: [],
                        values: [{
                            label_name: '',
                            value_name: '',
                        }]
                    }
                ];

                var barcodeExists = false;

                /**
                * Check barcode uniqueness
                *---------------------------------------------------------------- */
                scope.checkUniqueBarCode = function (barcode) {

                    barcodeExists = false;
                    _.map(scope.productData.optionLabels, function (option) {
                        if (_.includes(option.barcodes, barcode)) {
                            barcodeExists = true;
                        }
                    });

                    return barcodeExists;

                }

                /**
                * Add More Value
                *---------------------------------------------------------------- */
                scope.addMoreCombination = function (labelName) {
                    scope.labelData.push({
                        id: labelName,
                        name: labelName
                    });

                    scope.productData.optionLabels.push({
                        title: '',
                        product_id: '',
                        price: null,
                        barcodes: [],
                        values: [{
                            label_name: '',
                            value_name: '',
                        }]
                    });
                }

                /**
                * Remove Value
                *---------------------------------------------------------------- */
                scope.removeCombination = function (combinationKey) {
                    _.remove(scope.productData.optionLabels, function (item, index) {
                        return index == combinationKey;
                    });
                }

                /**
                * Add Value
                *---------------------------------------------------------------- */
                scope.addMoreValue = function (labelKey) {
                    scope.productData.optionLabels[labelKey]['values'].push({
                        label_name: '',
                        value_name: '',
                    });
                }

                /**
                * Remove Value
                *---------------------------------------------------------------- */
                scope.removeValue = function (labelKey, valueKey) {
                    _.remove(scope.productData.optionLabels[labelKey]['values'], function (item, index) {
                        return index == valueKey;
                    });
                }

                /**
                  * Submit form
                  *
                  * @return  void
                  *---------------------------------------------------------------- */
                scope.submit = function () {

                    __Form.process('manage.product.write.create', scope)
                        .success(function (responseData) {
                            appServices.processResponse(responseData, null, function (reaction) {
                                if (reaction == 1) {
                                    $state.go('product');
                                }
                            });

                        });
                };

                /**
                * Submit form
                *
                * @return  void
                *---------------------------------------------------------------- */

                scope.addNewCategory = function (categoryName) {
                    if (!_.isUndefined(categoryName)) {
                        var categoryData = {
                            'name': categoryName
                        };
                        __DataStore.post('manage.category.write.create_from_product', categoryData)
                            .success(function (responseData) {
                                appServices.processResponse(responseData, null, function (reactionCode) {
                                    if (reactionCode == 1) {
                                        scope.category_name = '';
                                        scope.categories.push(responseData.data.addedCategory);
                                    }
                                });
                            });
                    }
                };
            }
        ])
        // Product Add Controller ends here

        /**
        * Product Edit Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object 'productEditData'
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('ProductEditController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'productEditData',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, productEditData) {

                var scope = this,
                    productIdOrUid = $state.params.productIdOrUid;
                scope.showLoader = true;

                scope = __Form.setup(scope, 'product_form', 'productData');

                var requestData = productEditData;

                scope.barcodesOp = requestData.barcodesOp;
                scope.categorySelectConfig = __globals.getSelectizeOptions();
                scope.supplierSelectConfig = __globals.getSelectizeOptions();
                scope.labelSelectConfig = __globals.getSelectizeOptions({
                    valueField: 'id',
                    labelField: 'name',
                    searchField: ['name'],
                    plugins: ['restore_on_backspace'],
                    create: function (input) {
                        return {
                            id: input,
                            name: input,
                        };
                    }
                });

                scope.barcodesSelectConfig = __globals.getSelectizeOptions({
                    valueField: 'key',
                    labelField: 'value',
                    searchField: ['value'],
                    maxItems: 100,
                    plugins: ['remove_button'],
                    delimiter: ',',
                    persist: false,
                    create: function (input) {
                        if (scope.checkUniqueBarCode(input)) {
                            this.blur();
                            this.focus();
                            return {
                                key: null,
                                value: null,
                            };
                        } else {
                            return {
                                key: input,
                                value: input,
                            };
                        }
                    },
                    onDelete: function (values) {

                        var $instance = this;
                        var status = this;
                        status = scope.checkBarcodeDelete(values, $instance)
                            .then(function (success) {
                                return true;
                            })
                            .catch(function (error) {
                                return false;
                            });

                        return false;
                        /*var $barcodeDeleteConfirmation = $('#barcodeDeleteConfirmation'),
                                            code = _.get(values, 0);

                        if (_.includes(requestData.barcodesArray, code)) {

                            __globals.showConfirmation({
                                html : __globals.getReplacedString($barcodeDeleteConfirmation,
                                                        '__name__',
                                                        code
                                                    ),
                                confirmButtonText : $barcodeDeleteConfirmation.attr('data-delete-button-text')
                            },
                            function() {
        
                                __DataStore.post('manage.barcode.write.delete', {
                                    'barcode'  : code
                                }).success(function(responseData) {
        
                                    appServices.processResponse(responseData, null,function(data) {
                                        // scope.removeValue(labelIndex, valueIndex);
                                    });   
        
                                }); 

                            });
                        }*/
                    }
                });


                scope.checkBarcodeDelete = function (values, instance) {

                    return new Promise(function (resolve, reject) {

                        var $barcodeDeleteConfirmation = $('#barcodeDeleteConfirmation'),
                            code = _.get(values, 0);

                        if (_.includes(requestData.barcodesArray, code)) {

                            __globals.showConfirmation({
                                html: __globals.getReplacedString($barcodeDeleteConfirmation,
                                    '__name__',
                                    code
                                ),
                                confirmButtonText: $barcodeDeleteConfirmation.attr('data-delete-button-text')
                            },
                                function () {

                                    __DataStore.post('manage.barcode.write.delete', {
                                        'barcode': code
                                    }).success(function (responseData) {

                                        appServices.processResponse(responseData, null, function (data) {
                                            instance.removeOption(code);
                                            instance.refreshOptions();
                                            instance.clearCache();
                                            resolve('success');
                                        });

                                    }).error(function (responseData) {

                                        reject('error');

                                    });
                                });
                        } else {
                            instance.removeOption(code);
                            instance.refreshOptions();
                            instance.clearCache();
                        }

                    });
                }


                scope.presetSelectize = __globals.getSelectizeOptions();

                scope.currency = requestData.currency;
                scope.currencySymbol = requestData.currency_symbol;
                scope.categories = requestData.categories;
                scope.suppliers = requestData.suppliers;
                scope.labelData = requestData.labelData;
                scope.taxPresets = requestData.taxPresets;

                scope = __Form.updateModel(scope, requestData.updateData);

                if (_.isEmpty(scope.productData.optionLabels)) {
                    scope.productData.optionLabels = [
                        {
                            title: '',
                            product_id: '',
                            price: null,
                            barcodes: [],
                            values: [{
                                label_name: '',
                                value_name: '',
                            }]
                        }
                    ];
                }

                var barcodeExists = false;

                /**
                * Check barcode uniqueness
                *---------------------------------------------------------------- */
                scope.checkUniqueBarCode = function (barcode) {

                    barcodeExists = false;
                    _.map(scope.productData.optionLabels, function (option) {
                        if (_.includes(option.barcodes, barcode)) {
                            barcodeExists = true;
                        }
                    });

                    return barcodeExists;

                }

                /**
                * Add More Value
                *---------------------------------------------------------------- */
                scope.addMoreCombination = function (labelName) {
                    scope.labelData.push({
                        id: labelName,
                        name: labelName
                    });

                    scope.productData.optionLabels.push({
                        title: '',
                        product_id: '',
                        price: null,
                        barcodes: [],
                        values: [{
                            label_name: '',
                            value_name: '',
                        }]
                    });
                }

                /**
                * Remove Value
                *---------------------------------------------------------------- */
                scope.removeCombination = function (combinationKey) {
                    _.remove(scope.productData.optionLabels, function (item, index) {
                        return index == combinationKey;
                    });
                }

                /**
                * Add Value
                *---------------------------------------------------------------- */
                scope.addMoreValue = function (labelKey) {
                    scope.productData.optionLabels[labelKey]['values'].push({
                        label_name: '',
                        value_name: '',
                    });
                }

                /**
                * Remove Value
                *---------------------------------------------------------------- */
                scope.removeValue = function (labelKey, valueKey) {
                    _.remove(scope.productData.optionLabels[labelKey]['values'], function (item, index) {
                        return index == valueKey;
                    });
                }

                /**
                * Delete Value
                *---------------------------------------------------------------- */
                scope.deleteValue = function (valueId, labelIndex, valueIndex, comboId) {
                    __DataStore.post({
                        'apiURL': 'manage.product.value.write.delete',
                        'productId': productIdOrUid,
                        'comboId': comboId,
                        'valueId': valueId
                    })
                        .success(function (responseData) {
                            appServices.processResponse(responseData, null, function (data) {
                                scope.removeValue(labelIndex, valueIndex);
                            });
                        });
                }

                /**
                * Delete combination
                *---------------------------------------------------------------- */
                scope.deleteCombination = function (combinationId, labelIndex) {
                    __DataStore.post({
                        'apiURL': 'manage.product.combination.write.delete',
                        'productId': productIdOrUid,
                        'combinationId': combinationId
                    })
                        .success(function (responseData) {
                            appServices.processResponse(responseData, null, function (data) {
                                scope.removeCombination(labelIndex);
                            });
                        });
                }

                /**
                * Submit form
                *
                * @return  void
                *---------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process({
                        'apiURL': 'manage.product.write.update',
                        'productIdOrUid': productIdOrUid
                    }, scope).success(function (responseData) {
                        appServices.processResponse(responseData, null, function (reaction) {
                            if (reaction == 1) {
                                $state.go('product');
                            }
                            // scope.getProductDetails();
                        });
                    });
                };
            }
        ])
        // Product Edit Controller ends here 


        /**
        * Product Edit Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object 'productEditData'
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('ProductDetailsCotroller', [
            '$scope',
            'GetDetails',
            function ($scope, GetDetails) {

                var scope = this;
                scope.details = GetDetails.details;

                /**
                * Close dialog
                *
                * @return  void
                *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };

            }])
        // Product Details Controller ends here 
        ;

})(window, window.angular);;
/*!
*  Component  : Location
*  File       : LocationDataServices.js  
*  Engine     : LocationServices 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.LocationDataServices', [])
        .service('LocationDataService', [
            '$q',
            '__DataStore',
            'appServices',
            LocationDataService
        ])

    /*!
     This service use for to get the promise on data
    ----------------------------------------------------------------------------- */

    function LocationDataService($q, __DataStore, appServices) {

        /*
        Get Add Support Data
        -------------------------------------------------------------- */
        this.getAddSupportData = function () {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch('manage.location.read.support_data')
                .success(function (responseData) {

                    appServices.processResponse(responseData, null, function (reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData.data);
                    });
                });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get Edit Support Data
        -------------------------------------------------------------- */
        this.getEditSupportData = function (locationIdOrUid) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.location.read.update.data',
                'locationIdOrUid': locationIdOrUid
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get Assign Location DAta
        -------------------------------------------------------------- */
        this.getAssignLocationData = function (userAuthorityId) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.location.read.assign_location',
                'userAuthorityId': userAuthorityId
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get Assign Location DAta
        -------------------------------------------------------------- */
        this.getAssignUserData = function (locationId) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.location.read.assign_user',
                'locationId': locationId
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };
    };

})(window, window.angular);;
/*!
*  Component  : Location
*  File       : Location.js  
*  Engine     : Location 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.LocationEngine', [])

        /**
          * Location Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('LocationController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            function ($scope, __DataStore, __Form, $stateParams) {

                var scope = this;

            }
        ])


        /**
        * Location List Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object LocationDataService
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('LocationListController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'LocationDataService',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, LocationDataService) {
                var dtColumnsData = [
                    {
                        "name": "name",
                        "orderable": true,
                    },
                    {
                        "name": "location_id",
                        "orderable": true,
                    },
                    {
                        "name": "status",
                        "orderable": true,
                    },
                    {
                        "name": "short_description",
                    },
                    {
                        "name": null,
                        "template": "#locationActionColumnTemplate"
                    }
                ],
                    scope = this;

                /**
                * Get general user test as a datatable source object  
                *
                * @return  void
                *---------------------------------------------------------- */

                scope.locationDataTable = __DataStore.dataTable('#lwlocationList', {
                    url: 'manage.location.read.list',
                    dtOptions: {
                        "searching": true
                    },
                    columnsData: dtColumnsData,
                    scope: $scope
                });

                /*
                Reload current datatable
                ------------------------------------------------------------ */
                scope.reloadDT = function () {
                    __DataStore.reloadDT(scope.locationDataTable);
                };

                /**
                 * location delete 
                 *
                 * inject locationIdUid
                 *
                 * @return    void
                 *---------------------------------------------------------------- */

                scope.delete = function (locationIdOrUid, name) {

                    var $locationDeleteConfirm = $('#locationDeleteConfirm');

                    __globals.showConfirmation({
                        html: __globals.getReplacedString($locationDeleteConfirm,
                            '__name__',
                            _.unescape(name)
                        ),
                        confirmButtonText: $locationDeleteConfirm.attr('data-delete-button-text')
                    },
                        function () {

                            __DataStore.post({
                                'apiURL': 'manage.location.write.delete',
                                'locationIdOrUid': locationIdOrUid,
                            })
                                .success(function (responseData) {

                                    var message = responseData.data.message;

                                    appServices.processResponse(responseData, {

                                        error: function (data) {
                                            __globals.showConfirmation({
                                                title: $locationDeleteConfirm.attr('data-error-text'),
                                                text: message,
                                                type: 'error'
                                            });

                                        }

                                    },
                                        function (data) {

                                            __globals.showConfirmation({
                                                title: $locationDeleteConfirm.attr('data-success-text'),
                                                text: message,
                                                type: 'success'
                                            });
                                            scope.reloadDT();   // reload datatable
                                        });
                                });
                        });
                };

                /*
                add dialog
                ------------------------------------------------------------ */
                scope.openAddDialog = function () {

                    appServices.showDialog(scope, {
                        templateUrl: __globals.getTemplateURL("location.add-dialog"),
                        controller: 'LocationAddController as locationAddCtrl',
                        resolve: {
                            locationAddData: function () {
                                return LocationDataService
                                    .getAddSupportData();
                            }
                        }
                    }, function (promiseObj) {

                        if (_.has(promiseObj.value, 'location_added_or_updated')
                            && promiseObj.value.location_added_or_updated) {
                            scope.reloadDT();
                        }
                    });
                };

                /*
                edit dialog
                ------------------------------------------------------------ */
                scope.openEditDialog = function (locationIdOrUid) {

                    appServices.showDialog({
                        'locationIdOrUid': locationIdOrUid
                    }, {
                        templateUrl: __globals.getTemplateURL("location.edit-dialog"),
                        controller: 'LocationEditController as locationEditCtrl',
                        resolve: {
                            locationEditData: function () {
                                return LocationDataService
                                    .getEditSupportData(locationIdOrUid);
                            }
                        }
                    }, function (promiseObj) {

                        if (_.has(promiseObj.value, 'location_added_or_updated')
                            && promiseObj.value.location_added_or_updated) {
                            scope.reloadDT();
                        }
                    });
                };

                /*
                assign user dialog
                ------------------------------------------------------------ */
                scope.assignUser = function (locationIdOrUid, name) {
                    appServices.showDialog({
                        'name': _.unescape(name),
                        'locationId': locationIdOrUid
                    }, {
                        templateUrl: __globals.getTemplateURL("location.assign-user-dialog"),
                        controller: 'AssignUserDialogController as AssignUserDialogCtrl',
                        resolve: {
                            assignUserData: function () {
                                return LocationDataService
                                    .getAssignUserData(locationIdOrUid);
                            }
                        }
                    }, function (promiseObj) {

                        if (_.has(promiseObj.value, 'location_added_or_updated')
                            && promiseObj.value.location_added_or_updated) {
                            scope.reloadDT();
                        }
                    });
                };
            }
        ])
        // Location List Controller ends here


        /**
        * Location Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('LocationAddController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'locationAddData',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, locationAddData) {

                var scope = this;

                scope.showLoader = true;
                scope = __Form.setup(scope, 'location_form', 'locationData');
                scope.parentLocations = locationAddData.parentLocationData;
                scope.locationSelectConfig = __globals.getSelectizeOptions();


                /**
                  * Submit form
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process('manage.location.write.create', scope)
                        .success(function (responseData) {

                            appServices.processResponse(responseData, null, function () {
                                $scope.closeThisDialog({ 'location_added_or_updated': true });
                            });

                        });
                };

                /**
                  * Close dialog
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };
            }
        ])
        // LocationAddController ends here


        /**
        * Location Edit Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object locationEditData
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('LocationEditController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'locationEditData',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, locationEditData) {

                var scope = this;
                scope.showLoader = true;

                scope = __Form.setup(scope, 'location_form', 'locationData');
                scope.locationSelectConfig = __globals.getSelectizeOptions();
                var requestData = locationEditData;
                scope = __Form.updateModel(scope, requestData.locationData);
                scope.parentLocations = requestData.parentLocationData;
                scope.showLoader = false;
                var locationIdOrUid = $scope.ngDialogData.locationIdOrUid;


                /**
                * Submit form
                *
                * @return  void
                *---------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process({
                        'apiURL': 'manage.location.write.update',
                        'locationIdOrUid': locationIdOrUid
                    }, scope).success(function (responseData) {

                        appServices.processResponse(responseData, null, function () {
                            $scope.closeThisDialog({ 'location_added_or_updated': true });
                        });
                    });

                };

                /**
                * Close dialog
                *
                * @return  void
                *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };

            }

        ])
        // Location Edit Controller ends here

        /**
          * Location Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('AssignLocationController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'assignLocationData',
            'appServices',
            function ($scope, __DataStore, __Form, $stateParams, assignLocationData, appServices) {

                var scope = this,
                    ngDialogData = $scope.ngDialogData;

                scope.userName = ngDialogData.name
                scope.userAuthorityId = ngDialogData.userAuthorityId;
                scope = __Form.setup(scope, 'assign_location_form', 'assignData');
                scope.locationData = assignLocationData.locationData;

                if (!_.isEmpty(assignLocationData.locationIds)) {
                    scope = __Form.updateModel(scope, assignLocationData.locationIds);
                }

                scope.locationSelectConfig = __globals.getSelectizeOptions({
                    valueField: 'id',
                    labelField: 'name',
                    searchField: ['name'],
                    plugins: ['remove_button'],
                    maxItems: 1000,
                    delimiter: ','
                });

                /**
                * Submit form
                *
                * @return  void
                *---------------------------------------------------------------- */
                scope.submit = function () {
                    __Form.process({
                        'apiURL': 'manage.location.write.location_assign_process',
                        'userAuthorityId': scope.userAuthorityId
                    }, scope).success(function (responseData) {
                        appServices.processResponse(responseData, null, function () {
                            $scope.closeThisDialog();
                        });
                    });

                };

                /**
                  * Close dialog
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };
            }
        ])

        /**
          * Assign User Dialog Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('AssignUserDialogController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'assignUserData',
            'appServices',
            function ($scope, __DataStore, __Form, $stateParams, assignUserData, appServices) {

                var scope = this,
                    ngDialogData = $scope.ngDialogData;
                scope.userData = assignUserData.userData;
                scope.locationId = ngDialogData.locationId;
                scope.locationName = ngDialogData.name;
                scope = __Form.setup(scope, 'assign_user_form', 'assignData');
                if (!_.isEmpty(assignUserData.userAuthorityIds)) {
                    scope = __Form.updateModel(scope, assignUserData.userAuthorityIds);
                }

                scope.userSelectConfig = __globals.getSelectizeOptions({
                    valueField: 'id',
                    labelField: 'name',
                    searchField: ['name'],
                    plugins: ['remove_button'],
                    maxItems: 1000,
                    delimiter: ','
                });

                /**
                * Submit form
                *
                * @return  void
                *---------------------------------------------------------------- */
                scope.submit = function () {
                    __Form.process({
                        'apiURL': 'manage.location.write.user_assign_process',
                        'locationId': scope.locationId
                    }, scope).success(function (responseData) {
                        appServices.processResponse(responseData, null, function () {
                            $scope.closeThisDialog();
                        });
                    });

                };

                /**
                * Close dialog
                *
                * @return  void
                *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };
            }
        ])
        ;

})(window, window.angular);;
/*!
*  Component  : Inventory
*  File       : InventoryDataServices.js  
*  Engine     : InventoryServices 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.InventoryDataServices', [])
        .service('InventoryDataService', [
            '$q',
            '__DataStore',
            'appServices',
            InventoryDataService
        ])

    /*!
     This service use for to get the promise on data
    ----------------------------------------------------------------------------- */

    function InventoryDataService($q, __DataStore, appServices) {

        /*
        Get Inventory list
        -------------------------------------------------------------- */
        this.getInvetoryList = function (url) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch(url, { fresh: true }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get Inventory Update Data
        -------------------------------------------------------------- */
        this.getInventoryUpdateData = function (productId, combinationId, locationId, supplierId) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.inventory.read.update_data',
                'productId': productId,
                'combinationId': combinationId,
                'locationId': (!_.isUndefined(locationId)) ? locationId : null,
                'supplierId': (!_.isUndefined(supplierId)) ? supplierId : null,
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get product combination locations wise
        -------------------------------------------------------------- */
        this.getCombinationsLocationwise = function (combinationId, productId) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.billing.read.combinations_locationwise',
                'combinationId': combinationId,
                'productId': productId
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get Product Combination Data
        -------------------------------------------------------------- */
        this.getProductCombinationData = function (productId) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.inventory.read.product_combination',
                'productId': productId
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get Inventory Transaction Data
        -------------------------------------------------------------- */
        this.getInventoryTrasactionData = function (productId, combinationId, tranType, locationId) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.inventory.read.transaction_data',
                'productId': productId,
                'combinationId': combinationId,
                'tranType': tranType,
                'locationId': locationId
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };

    };

})(window, window.angular);
;
/*!
*  Component  : Inventory
*  File       : Inventory.js  
*  Engine     : Inventory 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.InventoryEngine', [])

        /**
          * Inventory List Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('InventoryListController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'appServices',
            'InventoryDataService',
            '__Utils',
            function ($scope, __DataStore, __Form, $stateParams, appServices, InventoryDataService, __Utils) {

                var scope = this;
                scope.sortBy = 'desc';
                scope.pageContentLoaded = false;
                scope.search_term = '';

                scope.supplierSelectConfig = __globals.getSelectizeOptions();
                scope.categorySelectConfig = __globals.getSelectizeOptions();
                scope.inventorySelectConfig = __globals.getSelectizeOptions();

                /**
                * Get Invetories
                *---------------------------------------------------------- */
                scope.getInventories = function (url) {

                    if (_.isEmpty(url)) {
                        var supplierId = (!_.isUndefined(scope.supplier)) ? scope.supplier : null,
                            categoryId = (!_.isUndefined(scope.category)) ? scope.category : null,
                            inventoryId = (!_.isUndefined(scope.inventory)) ? scope.inventory : null;

                        url = __Utils.apiURL('manage.inventory.read.list');
                        url = url + "?supplier_id=" + supplierId + "&category_id=" + categoryId + "&inventory_id=" + inventoryId + "&search_term=" + scope.search_term;
                    }

                    InventoryDataService
                        .getInvetoryList(url)
                        .then(function (responseData) {
                            var requestData = responseData;
                            scope.invetoryData = requestData.invetoryData;
                            scope.paginationLinks = requestData.paginationLinks;
                            scope.sortOrder = requestData.sortOrder;
                            scope.sortOrderUrl = requestData.sortOrderUrl;
                            scope.suppliers = requestData.suppliers;
                            scope.categories = requestData.categories;
                            scope.inventories = requestData.inventories;


                            if (scope.paginationLinks) {
                                var $paginationLinksElement = $(".lw-pagination-container").html(scope.paginationLinks);
                            }
                            scope.pageContentLoaded = true;
                        });
                };
                scope.getInventories(null);

                /*
                Clear Filter
                ------------------------------------------------------------ */
                scope.clearFilter = function () {
                    scope.supplier = '';
                    scope.category = '';
                    scope.inventory = '';
                    scope.search_term = '';
                    scope.getInventories(null);
                }

                /*
                Sort By
                ------------------------------------------------------------ */
                scope.sortBy = function (columnName, orderBy) {
                    var url = scope.sortOrderUrl + (_.includes(scope.sortOrderUrl, '?') ? '&' : '?') + "sort_by=" + columnName + "&sort_order=" + orderBy;
                    scope.getInventories(url);
                }

                /*
                Search Inventory
                ------------------------------------------------------------ */
                scope.searchInvetory = function (searchTerm) {
                    var searchUrl = scope.sortOrderUrl + (_.includes(scope.sortOrderUrl, '?') ? '&' : '?') + "search_term=" + searchTerm;
                    scope.getInventories(searchUrl);
                }

                $(".lw-pagination-container").on('click', 'a', function (event) {
                    event.preventDefault();
                    var $this = $(this),
                        url = $this.attr('href');
                    scope.getInventories(url);
                });

                scope.isArray = function (item) {
                    return _.isObject(item);
                }

                /*
                Update Inventory
                ------------------------------------------------------------ */
                scope.updateInventory = function (productId, productName, combinationId, locationId, type, supplierId) {
                    appServices.showDialog(
                        {
                            productName: productName,
                            productId: productId,
                            type: type,
                            combinationId: combinationId,
                            supplierId: supplierId,
                            showProductList: (_.isEmpty(productId)) ? true : false
                        },
                        {
                            templateUrl: "inventory.update-inventory-dialog",
                            controller: 'UpdateInventoryController as UpdateInventoryCtrl',
                            resolve: {
                                InventoryUpdateData: function () {
                                    return InventoryDataService
                                        .getInventoryUpdateData(productId, combinationId, locationId, supplierId);
                                }
                            }
                        },
                        function (promiseObj) {
                            if (_.has(promiseObj.value, 'inventory_updated')
                                && promiseObj.value.inventory_updated) {
                                scope.getInventories(null);
                            }
                        });
                }

                /*
                Show inventory transaction
                ------------------------------------------------------------ */
                scope.getInventoryTransaction = function (productId, productName, combinationId, tranType, locationId) {
                    appServices.showDialog(
                        {
                            productName: productName,
                            tranType: tranType
                        },
                        {
                            templateUrl: "inventory.transaction-dialog",
                            controller: 'InventoryTransactionController as InventoryTransactionCtrl',
                            resolve: {
                                InventoryTransactionData: function () {
                                    return InventoryDataService
                                        .getInventoryTrasactionData(productId, combinationId, tranType, locationId);
                                }
                            }
                        },
                        function (promiseObj) {
                        });
                }
            }
        ])

        /**
         * Update Inventory Controller 
         *
         * inject object $scope
         * inject object __DataStore
         * inject object __Form
         * inject object $stateParams
         *
         * @return  void
         *---------------------------------------------------------------- */

        .controller('UpdateInventoryController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'appServices',
            'InventoryUpdateData',
            'InventoryDataService',
            function ($scope, __DataStore, __Form, $stateParams, appServices, InventoryUpdateData, InventoryDataService) {

                var scope = this;
                scope = __Form.setup(scope, 'inventry_form', 'inventoryData');
                scope.combinationSelectConfig = __globals.getSelectizeOptions();
                scope.locationSelectConfig = __globals.getSelectizeOptions();
                scope.supplierSelectConfig = __globals.getSelectizeOptions();
                scope.ngDialogData = $scope.ngDialogData;
                scope.product_name = scope.ngDialogData.productName;
                scope.productId = scope.ngDialogData.productId;
                scope.inventoryData.sub_type = scope.ngDialogData.type;
                scope.showProductList = scope.ngDialogData.showProductList;
                scope.inventoryData.combination = scope.ngDialogData.combinationId;
                scope.inventoryData.supplier = scope.ngDialogData.supplierId;

                scope.supplierRequired = false;
                scope.disableSupplier = false;
                scope.productData = InventoryUpdateData;
                scope.availableQuantity = scope.productData.availableQuantity;
                scope.availableStockText = 'Current Stock';
                scope.combinationExist = scope.productData.combinationExist;
                scope.isLocationExist = scope.productData.locationExist;
                scope.inventoryData.location = scope.productData.locationId;
                scope.products = scope.productData.products;
                scope.combinations = scope.productData.combinations;
                scope.subTypes = scope.productData.subTypes;
                scope.locations = scope.productData.locations;
                scope.suppliers = scope.productData.suppliers;
                scope.showInactiveMessage = scope.productData.showInactiveMessage;
                scope.currency = scope.productData.currency;
                scope.currencySymbol = scope.productData.currencySymbol;
                scope.calculationSign = '';
                scope.combinationOption = '';
                if (!_.isUndefined(scope.inventoryData.combination)) {
                    _.forEach(scope.combinations, function (item) {
                        if (item.id == scope.inventoryData.combination) {
                            scope.combinationOption = item.combinationOption;
                        }
                    });
                }

                /**
                * Check if product options are updated
                *---------------------------------------------------------------- */
                scope.updateOption = function () {
                    __DataStore.fetch({
                        'apiURL': 'manage.inventory.write.calculate_options_quantity',
                        'productId': (scope.showProductList)
                            ? (!_.isUndefined(scope.inventoryData.product))
                                ? scope.inventoryData.product : null
                            : scope.productId,
                        'combinationId': (!_.isUndefined(scope.inventoryData.combination))
                            ? scope.inventoryData.combination : null,
                        'locationId': (!_.isUndefined(scope.inventoryData.location))
                            ? scope.inventoryData.location : null,
                        'supplierId': (!_.isUndefined(scope.inventoryData.supplier))
                            ? scope.inventoryData.supplier : null,
                        'type': (!_.isUndefined(scope.inventoryData.sub_type))
                            ? scope.inventoryData.sub_type : null,
                    }).success(function (responseData) {
                        appServices.processResponse(responseData, null, function () {
                            var requestData = responseData.data;
                            scope.formattedTotalAmount = requestData.formattedTotalAmount;
                            scope.availableQuantity = requestData.availableQuantity;
                            if (!_.isUndefined(scope.inventoryData.combination)) {
                                _.forEach(scope.combinations, function (item) {
                                    if (item.id == scope.inventoryData.combination) {
                                        scope.combinationOption = item.combinationOption;
                                    }
                                });
                            }
                        });
                    });
                }

                scope.changeCalculationSign = function (type) {

                    if (type == 1) {
                        scope.calculationSign = '+';
                        scope.disableSupplier = false;
                        scope.availableStockText = 'Current Stock';
                        scope.supplierRequired = true;
                    } else if (type == 2) {
                        scope.calculationSign = '-';
                        scope.inventoryData.supplier = null;
                        scope.disableSupplier = true;
                        scope.availableStockText = 'Current Stock';
                        scope.supplierRequired = false;
                    } else if (type == 3) {
                        scope.disableSupplier = false;
                        scope.availableStockText = 'Returnable Stock';
                        scope.calculationSign = '-';
                        scope.supplierRequired = true;
                    } else if (type == 5) {
                        scope.calculationSign = '+';
                        scope.availableStockText = 'Current Stock';
                        scope.disableSupplier = true;
                        scope.inventoryData.supplier = null;
                        scope.supplierRequired = false;
                    } else if (type == 7) {
                        scope.calculationSign = '-';
                        scope.inventoryData.supplier = null;
                        scope.disableSupplier = true;
                        scope.availableStockText = 'Returnable Stock';
                        scope.supplierRequired = false;
                    } else {
                        scope.calculationSign = '-';
                        scope.inventoryData.supplier = null;
                        scope.disableSupplier = true;
                        scope.availableStockText = 'Current Stock';
                        scope.supplierRequired = false;
                    }
                    scope.updateOption();
                }

                scope.isTypeExist = false;
                if (!_.isEmpty(scope.ngDialogData.type)) {
                    scope.isTypeExist = true;
                    scope.changeCalculationSign(scope.ngDialogData.type);
                }

                /**
                * Get Product Combinations
                *---------------------------------------------------------------- */
                scope.getProductCombinations = function (productId) {
                    if (!_.isUndefined(productId)) {
                        InventoryDataService
                            .getProductCombinationData(productId)
                            .then(function (requestData) {
                                scope.combinations = requestData.combinations;
                            });
                    }
                }

                /**
                * Submit form
                *
                * @return  void
                *---------------------------------------------------------------- */

                scope.submit = function (e) {

                    if (scope.inventry_form.$valid) {
                        var fewSeconds = 1;
                        var btn = $(e.currentTarget);
                        btn.prop('disabled', true);
                        setTimeout(function () {
                            btn.prop('disabled', false);
                        }, fewSeconds * 1000);
                    }

                    __Form.process({
                        'apiURL': 'manage.inventory.write.update',
                        'productId': (scope.showProductList)
                            ? (!_.isUndefined(scope.inventoryData.product))
                                ? scope.inventoryData.product : null
                            : scope.productId,
                    }, scope).success(function (responseData) {

                        appServices.processResponse(responseData, null, function () {
                            $scope.closeThisDialog({
                                'inventory_updated': true,
                                'stockDetails': responseData.data.stockDetails
                            });
                        });
                    });

                };

                /**
                  * Close dialog
                  *
                  * @return  void
                  *---------------------------------------------------------------- */
                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };
            }
        ])

        /**
          * Inventory Transaction Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('InventoryTransactionController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'InventoryTransactionData',
            function ($scope, __DataStore, __Form, $stateParams, InventoryTransactionData) {

                var scope = this;
                scope.ngDialogData = $scope.ngDialogData;
                scope.isSingleOptionSelected = false;
                scope.optionValues = scope.ngDialogData.optionValues;
                scope.productName = scope.ngDialogData.productName;
                scope.tranType = scope.ngDialogData.tranType;
                scope.transactionData = InventoryTransactionData.transactionData;
                scope.combinations = InventoryTransactionData.combinations;

                /**
                  * Close dialog
                  *
                  * @return  void
                  *---------------------------------------------------------------- */
                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };
            }
        ])
        ;

})(window, window.angular);;
/*!
*  Component  : Report
*  File       : ReportDataServices.js  
*  Engine     : ReportServices 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.ReportDataServices', [])
        .service('ReportDataService', [
            '$q',
            '__DataStore',
            'appServices',
            ReportDataService
        ])

    /*!
     This service use for to get the promise on data
    ----------------------------------------------------------------------------- */

    function ReportDataService($q, __DataStore, appServices) {


        /*
        Get Report support data
        -------------------------------------------------------------- */
        this.getSupportData = function () {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch('manage.report.read.support_data')
                .success(function (responseData) {

                    appServices.processResponse(responseData, null, function (reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData.data);
                    });
                });

            //return promise to caller          
            return defferedObject.promise;
        };

    };

})(window, window.angular);
;
/*!
*  Component  : Report
*  File       : Report.js  
*  Engine     : Report 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.ReportEngine', [])


        /**
         * Report List Controller
         *
         * inject object $scope
         * inject object __DataStore
         * inject object __Form
         * inject object $state
         * inject object appServices
         * inject object $rootScope
         * inject object ReportDataService
         *
         * @return  void
         *---------------------------------------------------------------- */

        .controller('ReportListController', [
            '$scope',
            '__DataStore',
            '__Form',
            'GetSupportData',
            function ($scope, __DataStore, __Form, GetSupportData) {
                var scope = this,
                    supportData = GetSupportData;
                scope.durations = supportData.durations;
                scope.stock_subtype = supportData.stock_trn_subtypes;
                scope.locations = supportData.locations;

                scope.locationsSelectConfig = __globals.getSelectizeOptions({
                    valueField: '_id',
                    labelField: 'title',
                    searchField: ['title'],
                    maxItems: 100,
                    plugins: ['remove_button']
                });

                scope = __Form.setup(scope, 'manage_report_list', 'reportData');

                scope.reportData.stock_subtype = "1"; // new
                scope.duration = "8"; // current year

                // set date
                scope.monthFirstDay = moment().startOf('month')
                    .format('YYYY-MM-DD');

                scope.monthLastDay = moment().endOf('month')
                    .format('YYYY-MM-DD');


                scope.lastMonthFirstDay = moment().subtract(1, 'months')
                    .startOf('month')
                    .format('YYYY-MM-DD');

                scope.lastMonthLastDay = moment().subtract(1, 'months')
                    .endOf('month')
                    .format('YYYY-MM-DD');

                scope.currentWeekFirstDay = moment().startOf('week')
                    .format('YYYY-MM-DD');

                scope.currentWeekLastDay = moment().endOf('week')
                    .format('YYYY-MM-DD');


                scope.lastWeekFirstDay = moment().weekday(-7)
                    .format('YYYY-MM-DD');

                scope.lastWeekLastDay = moment().weekday(-1)
                    .format('YYYY-MM-DD');

                scope.today = moment().format('YYYY-MM-DD');

                scope.yesterday = moment().subtract(1, 'day')
                    .format('YYYY-MM-DD');

                scope.lastYearFirstDay = moment().subtract(1, 'year').startOf('year').format('YYYY-MM-DD');

                scope.lastYearLastDay = moment().subtract(1, 'year').endOf('year').format('YYYY-MM-DD');

                scope.currentYearFirstDay = moment().startOf('year').format('YYYY-MM-DD');

                scope.currentYearLastDay = moment().endOf('year').format('YYYY-MM-DD');

                scope.last30Days = moment().subtract(30, 'day').format('YYYY-MM-DD');


                // date and time
                var today = moment().format('YYYY-MM-DD');

                scope.reportData.start = today;
                scope.reportData.end = today;

                scope.startDateConfig = {
                    time: false
                };

                scope.endDateConfig = {
                    minDate: moment().format('YYYY-MM-DD'),
                    time: false
                };

                $scope.$watch('reportListCtrl.reportData.start', function (currentValue, oldValue) {

                    var $element = angular.element('#end');

                    // Check if currentValue exist
                    if (_.isEmpty(currentValue)) {
                        $element.bootstrapMaterialDatePicker('setMinDate', '');
                    } else {
                        $element.bootstrapMaterialDatePicker('setMinDate', currentValue);
                    }

                });

                /**
                 * Call when start date updated
                 *
                 * @param startDate
                 *
                 * @return void
                 *---------------------------------------------------------------- */

                scope.startDateUpdated = function (startDate) {

                    scope.reportData.start = startDate;
                };

                /**
                 * Call when start date updated
                 *
                 * @param endDate
                 *
                 * @return void
                 *---------------------------------------------------------------- */
                scope.endDateUpdated = function (endDate) {

                    if (scope.reportData.start > scope.reportData.end) {
                        scope.reportData.end = endDate;
                    }

                    scope.reportData.end = endDate;
                };


                /**
                 * get date and time according to duration 
                 *
                 * @param duration
                 *
                 *---------------------------------------------------------------- */
                scope.durationChange = function (duration) {

                    if (duration == 1) { // current month

                        scope.reportData.start = scope.monthFirstDay;
                        scope.reportData.end = scope.monthLastDay;

                    } else if (duration == 2) { // last month

                        scope.reportData.start = scope.lastMonthFirstDay;
                        scope.reportData.end = scope.lastMonthLastDay;

                    } else if (duration == 3) { // current week

                        scope.reportData.start = scope.currentWeekFirstDay;
                        scope.reportData.end = scope.currentWeekLastDay;

                    } else if (duration == 4) { // last week

                        scope.reportData.start = scope.lastWeekFirstDay;
                        scope.reportData.end = scope.lastWeekLastDay;

                    } else if (duration == 5) { // today

                        scope.reportData.start = scope.today;
                        scope.reportData.end = scope.today;

                    } else if (duration == 6) { // yesterday

                        scope.reportData.start = scope.yesterday;
                        scope.reportData.end = scope.yesterday;

                    } else if (duration == 7) { // last year

                        scope.reportData.start = scope.lastYearFirstDay;
                        scope.reportData.end = scope.lastYearLastDay;

                    } else if (duration == 8) { // current year

                        scope.reportData.start = scope.currentYearFirstDay;
                        scope.reportData.end = scope.currentYearLastDay;

                    } else if (duration == 9) { // last 30 days

                        scope.reportData.start = scope.last30Days;
                        scope.reportData.end = scope.today;

                    }
                }

                scope.durationChange(scope.duration);



                var dtColumnsData = [{
                    "name": "product_id",
                    "orderable": true
                },
                {
                    "name": "title",
                    "orderable": true
                },
                {
                    "name": "location_name",
                    "orderable": true
                },
                {
                    "name": "formated_created_at",
                    "orderable": true,
                },
                {
                    "name": "quantity",
                    "orderable": true
                },
                {
                    "name": "formated_price",
                    "orderable": true
                },
                {
                    "name": "formated_total",
                },
                {
                    "name": "formatted_tax",
                    'template': "#taxAmountTemplate"
                },
                {
                    "name": "formated_amount",
                }
                ];

                /**
                 * Get general user test as a datatable source object  
                 *
                 * @return  void
                 *---------------------------------------------------------- */

                scope.getReports = function () {
                    if (scope.reportDataTable) {
                        scope.reportDataTable.destroy();
                    }

                    var $lwReportInput = $('#lwReportInput'),
                        filename = __ngSupport.getText($lwReportInput.data('filename'), {
                            '__filename__': scope.stock_subtype[scope.reportData.stock_subtype]
                        }),
                        topMessage = __ngSupport.getText($lwReportInput.data('top-message'), {
                            '__start__': scope.reportData.start,
                            '__end__': scope.reportData.end
                        });
                    //check if locations undefined or empty
                    if (_.isEmpty(scope.reportData.locations) || _.isUndefined(scope.reportData.locations)) {
                        scope.reportData.locations = null;
                    }

                    scope.reportDataTable = __DataStore.dataTable('#lwreportList', {
                        url: {
                            'apiURL': 'manage.report.read.list',
                            'start': scope.reportData.start,
                            'end': scope.reportData.end,
                            'subtype': scope.reportData.stock_subtype,
                            'locations': scope.reportData.locations
                        },
                        dtOptions: {
                            "searching": true,
                            "lengthMenu": [
                                [10, 25, 50, -1],
                                [10, 25, 50, "All"]
                            ],
                            "pageLength": 25,
                            'columnDefs': [{
                                class: 'text-right',
                                targets: [4, 5, 6, 8]
                            },
                            {
                                "targets": [6, 7],
                                "visible": (scope.reportData.stock_subtype == 2)
                            }
                            ],
                            dom: 'Bfrtip',
                            buttons: [
                                'pageLength',
                                'copy',
                                {
                                    extend: 'excel',
                                    footer: true,
                                    exportOptions: {
                                        columns: (scope.reportData.stock_subtype == 2) ? [0, 1, 2, 3, 4, 5, 6, 7, 8] : [0, 1, 2, 3, 4, 5, 8]
                                    },
                                    messageTop: topMessage,
                                    title: filename,
                                    customize: function (xlsx) {
                                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                        $('row:last c', sheet).attr('s', '52');
                                    }
                                },
                                {
                                    extend: 'pdf',
                                    footer: true,
                                    exportOptions: {
                                        columns: (scope.reportData.stock_subtype == 2) ? [0, 1, 2, 3, 4, 5, 6, 7, 8] : [0, 1, 2, 3, 4, 5, 8]
                                    },
                                    messageTop: topMessage,
                                    title: filename,
                                    customize: function (print) {
                                        $(print.styles.tableBodyEven).css('text-align', 'right;'),
                                            $(print.styles.tableBodyOdd).css('text-align', 'right;'),
                                            console.log(print.styles);
                                    }
                                },
                                {
                                    extend: 'print',
                                    footer: true,
                                    exportOptions: {
                                        columns: (scope.reportData.stock_subtype == 2) ? [0, 1, 2, 3, 4, 5, 6, 7, 8] : [0, 1, 2, 3, 4, 5, 8]
                                    },
                                    messageTop: topMessage,
                                    title: filename,
                                    autoPrint: true,
                                    customize: function (win) {

                                        $(win.document.body)
                                            .css('font-size', '10pt')
                                            .prepend(
                                                '<img src="' + __globals.configItem("logo_image_url") + '" style="position:absolute; top:0; left:0;" /><br><br><br>'
                                            );

                                        $(win.document.body).find('table')
                                            .addClass('compact')
                                            .css('font-size', 'inherit');

                                        $(win.document.body)
                                            .find('table.dataTable > tbody > tr > td:first-child')
                                            .addClass('lw-remove-plus-icon');

                                        $(win.document.body)
                                            .find('table.dataTable > tbody > tr > th:first-child')
                                            .addClass('lw-remove-plus-icon');


                                    }
                                }
                            ],
                            "footerCallback": function (row, data, start, end, display) {

                                var api = this.api(),
                                    data;

                                var totalAmount = [],
                                    totalPrice = [],
                                    currencyWiseTotal = {},
                                    currencySymbols = {};

                                _.forEach(data, function (item, index) {
                                    if (!currencyWiseTotal[item.currency_code]) {
                                        currencyWiseTotal[item.currency_code] = [];
                                        currencySymbols[item.currency_code] = item.currency_symbol;
                                    }
                                    currencyWiseTotal[item.currency_code].push(item.total_amount);
                                    totalAmount.push(item.total_amount);
                                    totalPrice.push(item.total_price);
                                });

                                var totalString = ' Total: <br>';
                                _.forEach(currencyWiseTotal, function (item, index) {
                                    totalString += ' ' + __globals.priceFormat(_.sum(item), currencySymbols[index], index) + '<br>';
                                });
                                // $( api.column( 5 ).footer() ).html('Total');
                                $(api.column(8).footer()).html(
                                    totalString
                                );
                            }
                        },
                        columnsData: dtColumnsData,
                        scope: $scope
                    });
                }

                scope.getReports();

                /*
                Reload current datatable
                ------------------------------------------------------------ */
                scope.reloadDT = function () {
                    __DataStore.reloadDT(scope.reportDataTable);
                };

                // when add new record 
                $scope.$on('report_added_or_updated', function (data) {

                    if (data) {
                        scope.reloadDT();
                    }

                });



            }
        ])
        // Report List Controller ends here





        ;

})(window, window.angular);
;
/*!
*  Component  : Billing
*  File       : BillingDataServices.js  
*  Engine     : BillingServices 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.BillingDataServices', [])
        .service('BillingDataService', [
            '$q',
            '__DataStore',
            'appServices',
            BillingDataService
        ])

    /*!
     This service use for to get the promise on data
    ----------------------------------------------------------------------------- */

    function BillingDataService($q, __DataStore, appServices) {

        /*
        Get Report support data
        -------------------------------------------------------------- */
        this.getAddSupportData = function () {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch('manage.billing.read.add_support_data')
                .success(function (responseData) {

                    appServices.processResponse(responseData, null, function (reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData.data);
                    });
                });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get Report support data
        -------------------------------------------------------------- */
        this.getEditSupportData = function (billUid) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.billing.read.edit_support_data',
                'billUid': billUid
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get Report support data
        -------------------------------------------------------------- */
        this.getDetailsSupportData = function (billUid) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.billing.read.details_support_data',
                'billUid': billUid
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };

        /*
        Get Report support data
        -------------------------------------------------------------- */
        this.getProductCombinations = function (searchTerm) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.billing.read.search_combination_for_bill',
                'searchTerm': searchTerm
            }, { fresh: true }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };

    };

})(window, window.angular);
;
/*!
*  Component  : Billing
*  File       : Billing.js  
*  Engine     : Billing 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.BillingEngine', [])

        /**
          * Billing Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('BillingController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            function ($scope, __DataStore, __Form, $stateParams) {

                var scope = this;

            }
        ])


        /**
        * Billing List Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object BillingDataService
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('BillingListController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'BillingDataService',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, BillingDataService) {
                var dtColumnsData = [
                    {
                        "name": "bill_number",
                        "orderable": true,
                        "template": "#billingTitleColumnTemplate"
                    },
                    {
                        "name": "txn_id",
                        "orderable": true,
                    },
                    {
                        "name": "customer_name",
                        "orderable": true,
                    },
                    {
                        "name": "total_amount",
                        "orderable": true,
                    },
                    {
                        "name": "formatted_status",
                        "orderable": true,
                    },
                    {
                        "name": "bill_date",
                        "orderable": true,
                    },
                    {
                        "name": null,
                        "template": "#billingActionColumnTemplate"
                    }
                ],
                    scope = this;

                /**
                * Get general user test as a datatable source object  
                *
                * @return  void
                *---------------------------------------------------------- */

                scope.billingDataTable = __DataStore.dataTable('#lwbillingList', {
                    url: 'manage.billing.read.list',
                    dtOptions: {
                        "searching": true,
                        "columnDefs": [
                            { className: "text-right", "targets": [3] }
                        ]
                    },
                    columnsData: dtColumnsData,
                    scope: $scope
                });

                /*
                Reload current datatable
                ------------------------------------------------------------ */
                scope.reloadDT = function () {
                    __DataStore.reloadDT(scope.billingDataTable);
                };

                // when add new record 
                $scope.$on('billing_added_or_updated', function (data) {

                    if (data) {
                        scope.reloadDT();
                    }

                });

                /**
                * bill delete 
                *
                * inject issueIdUid
                *
                * @return    void
                *---------------------------------------------------------------- */

                scope.delete = function (billId, title) {

                    var $billDeleteConfirm = $('#billDeleteConfirm');

                    __globals.showConfirmation({
                        html: __globals.getReplacedString($billDeleteConfirm,
                            '__billno__',
                            _.unescape(title)
                        ),
                        confirmButtonText: $billDeleteConfirm.attr('data-delete-button-text')
                    },
                        function () {

                            __DataStore.post({
                                'apiURL': 'manage.billing.write.delete',
                                'billId': billId
                            })
                                .success(function (responseData) {

                                    var message = responseData.data.message;

                                    appServices.processResponse(responseData, {

                                        error: function (data) {
                                            __globals.showConfirmation({
                                                title: $billDeleteConfirm.attr('data-error-text'),
                                                text: message,
                                                type: 'error'
                                            });

                                        }

                                    },
                                        function (data) {

                                            __globals.showConfirmation({
                                                title: $billDeleteConfirm.attr('data-success-text'),
                                                text: message,
                                                type: 'success'
                                            });
                                            scope.reloadDT();   // reload datatable
                                        });
                                });
                        });
                };

            }
        ])
        // Billing List Controller ends here

        /**
          * Billing Add Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('BillingAddController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'GetAddSupportData',
            'BillingDataService',
            '$compile',
            '__Utils',
            'appServices',
            'InventoryDataService',
            '$state',
            function ($scope, __DataStore, __Form, $stateParams, GetAddSupportData, BillingDataService, $compile, __Utils, appServices, InventoryDataService, $state) {

                var scope = this,
                    requestData = GetAddSupportData;

                scope.productData = [];
                scope.currencyCode = requestData.currencyCode;
                scope.currencySymbol = requestData.currencySymbol;
                scope = __Form.setup(scope, 'billing_form', 'billingData');

                scope.billingData.tax_amount = 0;
                scope.billingData.formatted_tax_amount = __globals.priceFormat(0, scope.currencySymbol, '');
                scope.billingData.discount_amount = 0;
                scope.billingData.formatted_discount_amount = __globals.priceFormat(0, scope.currencySymbol, '');
                scope.billingData.tax_type = '1';
                scope.billingData.discount_type = '1';

                scope.dateConfig = {
                    minDate: moment().format('YYYY-MM-DD'),
                    time: false
                };
                var today = moment().format('YYYY-MM-DD');
                scope.billingData.bill_date = today;
                scope.billingData.due_date = today;

                scope.customerSelectConfig = __globals.getSelectizeOptions();
                scope.combinationSelectConfig = __globals.getSelectizeOptions({
                    valueField: 'id',
                    labelField: 'name',
                    searchField: ['name', 'comboSKU', 'barcode', 'combinationTitle', "location_name"],
                    options: [],
                    create: false,
                    onChange: function (value) {
                        this.clearOptions();
                    },
                    onFocus: function (value) {
                        this.clearOptions();
                    },
                    loadThrottle: 500,
                    render: {
                        option: function (item, escape) {
                            return $compile(__Utils.template('#lwSelectizeOp', {
                                item: item
                            }))(scope);
                        }
                    },
                    load: function (searchTerm, callback, event) {

                        var $this = this;
                        var onFocusInput = ($(this)[0]['$input'][0]),
                            inputIndex = $(onFocusInput).data('key');

                        if (!searchTerm.length) return callback();

                        BillingDataService
                            .getProductCombinations(searchTerm)
                            .then(function (responseData) {
                                scope.productData = responseData.productData;

                                _.forEach(scope.productData, function (item) {
                                    if (item.barcode == searchTerm) {
                                        scope.showProductDetails(item.id, inputIndex);
                                        $this.blur();
                                        $this.clearOptions();
                                        $this.focus();
                                    }
                                });

                                if (_.isEmpty(scope.productData)) {
                                    $this.blur();
                                    $this.clearOptions();
                                    $this.focus();
                                }

                                callback(responseData.productData);
                            });
                    }
                });

                scope.userData = requestData.userData;
                scope.customerData = requestData.customerData;
                scope.billingData.customerDetails = null;
                scope.subTotal = 0;
                scope.formattedSubTotal = __globals.priceFormat(0, scope.currencySymbol, scope.currencyCode);
                scope.billingData.totalAmount = 0;
                scope.formattedTotalAmount = __globals.priceFormat(0, scope.currencySymbol, scope.currencyCode);
                scope.formattedTaxTotalAmount = __globals.priceFormat(0, scope.currencySymbol, scope.currencyCode);
                scope.formattedUnitPriceTotal = __globals.priceFormat(0, scope.currencySymbol, scope.currencyCode);
                scope.billingData.is_add_tax = false;
                scope.billingData.is_add_discount = false;

                scope.billingData.productCombinations = [];
                /*
                * Remove Row
                */
                scope.removeItem = function (index) {
                    _.remove(scope.billingData.productCombinations, function (item, key) {
                        return key == index;
                    });
                }

                /*
                * Add New Row
                */
                scope.addNewRow = function () {
                    scope.billingData.productCombinations.push({
                        'combination': [],
                        'taxes': [],
                        'unit_price': 0,
                        'formattedUnitPrice': __globals.priceFormat(0, scope.currencySymbol, ''),
                        'quantity': 0,
                        'price': 0,
                        'formattedPrice': __globals.priceFormat(0, scope.currencySymbol, ''),
                        'showDetails': false,
                        'location_id': null,
                        'location_name': '',
                        'tax_details': [],
                        'tax_presets__id': null
                    });
                    var comboIndex = scope.billingData.productCombinations.length - 1;

                    // _.defer(function() {
                    //     var input = _.get($('.lw-selectize-parent tr.lw-combination-row-'+comboIndex+' .selectize-input'), 0);
                    //     $(_.get($(input), 0)).triggerHandler('click', true);
                    // });
                }

                /*
                * If Enter Key press on Quantity input
                */
                scope.isEnterKeyPress = function (event, index) {
                    var keyCode = event.which || event.keyCode;
                    if (keyCode === 13) { // Enter Key
                        scope.addNewRow();
                        var inputIndex = index + 1;
                    }
                }

                scope.combinationExists = function (combinationId, locationId) {

                    var exists = false;
                    var combinationId = combinationId;

                    if (!_.isEmpty(scope.billingData.productCombinations)) {
                        _.forEach(scope.billingData.productCombinations, function (value, key) {
                            if ((value.combination.id == combinationId) && (locationId == value.location_id)) {
                                exists = true;
                                scope.billingData.productCombinations[key]['quantity']++;
                                scope.calculateTotalPrice();
                            }
                        });
                    }

                    return exists;
                }

                scope.choosedLocation = [];
                /*
                * Show Product Details
                */
                scope.showProductDetails = function (combinationId) {

                    var productCombinationData = [];
                    _.forEach(scope.productData, function (item) {
                        if (item.id == combinationId) {
                            productCombinationData = item;
                        }
                    });

                    if (!_.isEmpty(productCombinationData)) {

                        if (!_.isUndefined(productCombinationData.isQuantityExist) && !productCombinationData.isQuantityExist) {

                            /* when quantity exists */
                            scope.showAlertMessage(productCombinationData);

                        } else if (productCombinationData.chooseInventryByLocation) {
                            /* when product is available at different locations */
                            scope.chooseInventryByLocation(combinationId, productCombinationData);
                        } else {
                            /* when product is available only at one location */
                            scope.updateProductDetails(productCombinationData);
                        }
                    }
                }

                /* insert product combinations */
                scope.updateProductDetails = function (productCombinationData, locationInfo) {

                    var locationId = null;

                    if (!_.isUndefined(locationInfo)) {

                        locationId = locationInfo.location_id;

                        if (!scope.combinationExists(productCombinationData.id, locationId)) {

                            scope.addNewRow();
                            var index = (scope.billingData.productCombinations.length - 1);
                            scope.billingData.productCombinations[index]['showDetails'] = false;
                            scope.billingData.productCombinations[index]['combination'] = productCombinationData;
                            scope.billingData.productCombinations[index]['taxes'] = productCombinationData.taxes;
                            scope.billingData.productCombinations[index]['unit_price'] = productCombinationData.salePrice;
                            scope.billingData.productCombinations[index]['tax_presets__id'] = productCombinationData.tax_presets__id;
                            scope.billingData.productCombinations[index]['formattedUnitPrice'] = productCombinationData.formattedSalePrice;
                            scope.billingData.productCombinations[index]['showDetails'] = true;
                            scope.billingData.productCombinations[index]['quantity'] = 1;
                            scope.billingData.productCombinations[index]['location_name'] = productCombinationData.location_name;
                            scope.billingData.productCombinations[index]['location_id'] = locationInfo.location_id;
                            scope.billingData.productCombinations[index]['location_name'] = locationInfo.location;
                            scope.billingData.productCombinations[index]['combination']['quantity'] = parseInt(locationInfo.quantity);
                            scope.billingData.productCombinations[index]['combination']['lockQuantity'] = parseInt(locationInfo.lockQuantity);
                            scope.billingData.productCombinations[index]['combination']['availableQty'] = parseInt(locationInfo.availableQty);
                        }

                    } else {

                        locationId = productCombinationData.location_id;

                        if (!scope.combinationExists(productCombinationData.id, locationId)) {

                            scope.addNewRow();
                            var index = (scope.billingData.productCombinations.length - 1);
                            scope.billingData.productCombinations[index]['showDetails'] = false;
                            scope.billingData.productCombinations[index]['combination'] = productCombinationData;
                            scope.billingData.productCombinations[index]['taxes'] = productCombinationData.taxes;
                            scope.billingData.productCombinations[index]['unit_price'] = productCombinationData.salePrice;
                            scope.billingData.productCombinations[index]['tax_presets__id'] = productCombinationData.tax_presets__id;
                            scope.billingData.productCombinations[index]['formattedUnitPrice'] = productCombinationData.formattedSalePrice;
                            scope.billingData.productCombinations[index]['showDetails'] = true;
                            scope.billingData.productCombinations[index]['quantity'] = 1;
                            scope.billingData.productCombinations[index]['location_name'] = productCombinationData.location_name;
                            scope.billingData.productCombinations[index]['location_id'] = productCombinationData.location_id;
                        }
                    }

                    scope.calculateTotalPrice();

                }

                /*
                * Show Alert Message
                */
                scope.showAlertMessage = function (combinationData, index) {

                    var $billingMessagesInfo = $('#billingMessagesInfo');

                    __globals.showConfirmation({
                        text: combinationData.message,
                        showConfirmButton: combinationData.showUpdateInventoryButton,
                        confirmButtonText: $billingMessagesInfo.attr('data-confirm-button-text')
                    },
                        function () {
                            scope.updateInventory(combinationData.productId, combinationData.name, combinationData.id, null, '1', null, index);
                        });
                }

                /*
                * Calcuate tax
                */
                scope.getCalculatedTax = function (productPrice, taxOptions, index) {
                    var appliedTax = 0;
                    var appliedTaxTotal = 0;
                    var tax = [];
                    scope.billingData.productCombinations[index].tax_details = [];
                    if (!_.isEmpty(taxOptions)) {
                        _.map(taxOptions, function (tax) {

                            appliedTax = 0;
                            if (tax.type == 1) {

                                appliedTax = parseFloat(tax.tax_amount);
                                appliedTaxTotal += appliedTax;
                                scope.billingData.productCombinations[index]['formattedTax'] = scope.currencySymbol + appliedTax;
                            } else if (tax.type == 2) {

                                appliedTax = (productPrice * (parseFloat(tax.tax_amount) / 100));
                                appliedTax = parseFloat(appliedTax.toFixed(2));
                                scope.billingData.productCombinations[index]['formattedTax'] = parseFloat(tax.tax_amount) + "%";
                                appliedTaxTotal += appliedTax;
                            }

                            tax.tax_amount_on_product = appliedTax.toFixed(2);
                            scope.billingData.productCombinations[index].tax_details.push(tax);
                        });
                    }

                    scope.billingData.productCombinations[index]['calculatedTax'] = appliedTaxTotal;
                    scope.billingData.productCombinations[index]['formattedTaxAmount'] = scope.currencySymbol + appliedTaxTotal;
                    return appliedTaxTotal;
                }

                /*
                * Calcuate Total Price
                */
                scope.calculateTotalPrice = function () {
                    var productPrices = [];
                    var productTaxes = [];
                    var unitPriceTotal = [];
                    var totalproductPrice = 0;

                    _.forEach(scope.billingData.productCombinations, function (item, index) {
                        if (!_.isUndefined(item.combination.salePrice)) {
                            var productPrice = item.combination.salePrice * item.quantity;
                            var taxOptions = scope.billingData.productCombinations[index]['taxes'];
                            var calcTax = 0;

                            if (!_.isEmpty(taxOptions)) {
                                calcTax = scope.getCalculatedTax(productPrice, taxOptions, index);
                            }

                            totalproductPrice = productPrice + calcTax;
                            scope.billingData.productCombinations[index]['price'] = productPrice;

                            scope.billingData.productCombinations[index]['formattedPrice'] = __globals.priceFormat(productPrice.toFixed(2), scope.currencySymbol, '');
                            productPrices.push(productPrice);
                            productTaxes.push(scope.billingData.productCombinations[index]['calculatedTax']);
                            unitPriceTotal.push(productPrice);
                        }
                    });

                    // Calculate Subtotal
                    scope.subTotal = _.sum(productPrices);
                    scope.formattedSubTotal = __globals.priceFormat(scope.subTotal, scope.currencySymbol, scope.currencyCode);
                    scope.taxTotalAmount = _.sum(productTaxes);
                    scope.formattedTaxTotalAmount = __globals.priceFormat(scope.taxTotalAmount.toFixed(2), scope.currencySymbol, '');
                    scope.unitPriceTotal = _.sum(unitPriceTotal);
                    scope.formattedUnitPriceTotal = __globals.priceFormat(scope.unitPriceTotal.toFixed(2), scope.currencySymbol, '');

                    // Calculate Tax
                    var taxAmount = 0;
                    if (scope.billingData.is_add_tax) {
                        if (scope.subTotal != 0 && !_.isUndefined(scope.billingData.tax)) {
                            if (scope.billingData.tax_type == 1) {
                                taxAmount = scope.subTotal * scope.billingData.tax / 100;
                            } else if (scope.billingData.tax_type == 2) {
                                taxAmount = scope.billingData.tax;
                            }
                        }
                    }
                    scope.billingData.tax_amount = taxAmount;
                    scope.billingData.formatted_tax_amount = __globals.priceFormat(taxAmount, scope.currencySymbol, '');

                    // Calculate Discount
                    var discountAmount = 0;
                    if (scope.billingData.is_add_discount) {
                        if (scope.subTotal != 0 && !_.isUndefined(scope.billingData.discount)) {
                            if (scope.billingData.discount_type == 1) {
                                discountAmount = scope.subTotal * scope.billingData.discount / 100;
                            } else if (scope.billingData.discount_type == 2) {
                                discountAmount = scope.billingData.discount;
                            }
                        }
                    }
                    scope.billingData.discount_amount = discountAmount;
                    scope.billingData.formatted_discount_amount = __globals.priceFormat(discountAmount, scope.currencySymbol, '');

                    scope.billingData.totalAmount = ((scope.subTotal + scope.taxTotalAmount + taxAmount) - discountAmount);
                    scope.formattedSubTotal = __globals.priceFormat(scope.subTotal, scope.currencySymbol, '');
                    scope.formattedTotalAmount = __globals.priceFormat(scope.billingData.totalAmount.toFixed(2), scope.currencySymbol, '');
                }

                /*
                * Select Customer
                */
                scope.selectCustomer = function (customerId) {
                    if (!_.isEmpty(scope.customerData)) {
                        _.forEach(scope.customerData, function (item) {
                            if (item.id == customerId) {
                                scope.billingData.customerDetails = item;
                            }
                        });
                    }
                };

                //set the product inactive 
                scope.setProductInactive = function (productsIds) {

                    _.map(scope.billingData.productCombinations, function (item, index) {
                        if (_.includes(productsIds, parseInt(item.combination.productId))) {
                            scope.billingData.productCombinations[index].combination.product_status = 2;
                        }
                    });
                }

                /*
                Store Billing
                ------------------------------------------------------------ */
                scope.submit = function (type) {
                    scope.billingData.type = type;
                    __Form.process('manage.billing.write.store_bill', scope)
                        .success(function (responseData) {
                            if (!_.isEmpty(responseData.data.inactiveProducts)) {
                                scope.setProductInactive(responseData.data.inactiveProducts);
                            }
                            appServices.processResponse(responseData, null, function (reaction) {
                                if (reaction == 1) {
                                    $state.go('billing');
                                }
                            });

                        });
                }

                /*
                Update Inventory
                ------------------------------------------------------------ */
                scope.updateInventory = function (productId, productName, combinationId, locationId, type, supplierId, index) {

                    appServices.showDialog(
                        {
                            productName: productName,
                            productId: productId,
                            type: type,
                            combinationId: combinationId,
                            supplierId: supplierId,
                            showProductList: (_.isEmpty(productId)) ? true : false
                        },
                        {
                            templateUrl: "inventory.update-inventory-dialog",
                            controller: 'UpdateInventoryController as UpdateInventoryCtrl',
                            resolve: {
                                InventoryUpdateData: function () {
                                    return InventoryDataService
                                        .getInventoryUpdateData(productId, combinationId, locationId, supplierId);
                                }
                            }
                        },
                        function (promiseObj) {
                            if (_.has(promiseObj.value, 'inventory_updated')
                                && promiseObj.value.inventory_updated) {
                                var stockUpdateDetails = promiseObj.value.stockDetails;
                                _.forEach(scope.productData, function (item, key) {
                                    if (item.id == combinationId) {
                                        scope.productData[key]['isQuantityExist'] = true;
                                        scope.productData[key]['quantity'] = stockUpdateDetails.quantity;
                                        scope.productData[key]['location_id'] = stockUpdateDetails.location_id;
                                        scope.productData[key]['availableQty'] = stockUpdateDetails.quantity;
                                        scope.productData[key]['location_name'] = stockUpdateDetails.location_name;
                                        _.defer(function () {
                                            scope.showProductDetails(combinationId);
                                        });
                                    }
                                });
                            }
                        });
                }



                /*
                choose Inventry By Location
                ------------------------------------------------------------ */
                scope.chooseInventryByLocation = function (combinationId, productCombinationData) {

                    appServices.showDialog({
                        'combinationId': combinationId,
                        'productId': productCombinationData.productId
                    },
                        {
                            templateUrl: "billing.combination-location-dialog",
                            controller: 'CombinationLocationController as CombinationLocationCtrl',
                            resolve: {
                                CombinationsLocationsData: ['InventoryDataService', function (InventoryDataService) {
                                    return InventoryDataService
                                        .getCombinationsLocationwise(combinationId, productCombinationData.productId);
                                }]
                            }
                        },
                        function (promiseObj) {

                            if (_.has(promiseObj.value, 'location_selected') && promiseObj.value.location_selected) {
                                var locationInfo = promiseObj.value.location;
                                scope.updateProductDetails(productCombinationData, locationInfo);
                            }
                        });

                }
            }
        ])

        /**
          * Combination Location Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('CombinationLocationController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'CombinationsLocationsData',
            function ($scope, __DataStore, __Form, $stateParams, CombinationsLocationsData) {

                var scope = this;
                scope.productData = CombinationsLocationsData.productData;

                var ngDialogData = $scope.ngDialogData;
                scope.showLocationBtn = false;

                /**
                  * change location
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.changeLocation = function (selected_location) {

                    if (!_.isEmpty(scope.productData.stockByLocations)) {

                        _.map(scope.productData.stockByLocations, function (stock_location) {
                            if (stock_location.location_id == parseInt(selected_location)) {
                                scope.selected_location = stock_location.location_id;
                                scope.showLocationBtn = false;
                                if (stock_location.quantity > 0) {
                                    scope.showLocationBtn = true;
                                }
                            }
                        });
                    }
                };

                // select first option
                scope.changeLocation(scope.productData.stockByLocations[0].location_id);


                /**
                  * change location
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.chooseLocation = function (selected_location) {

                    if (!_.isUndefined(selected_location)) {
                        _.map(scope.productData.stockByLocations, function (loc) {
                            if (loc.location_id == selected_location) {
                                $scope.closeThisDialog({
                                    'location_selected': true,
                                    'location': loc,
                                    'combinationId': ngDialogData.combinationId,
                                    'productId': ngDialogData.productId
                                });
                            }
                        });
                    }
                };

                /**
                  * Close dialog
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog({ 'location_selected': false });
                };
            }
        ])

        /**
          * Billing Edit Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('BillingEditController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'GetEditSupportData',
            'BillingDataService',
            '$compile',
            '__Utils',
            'appServices',
            'InventoryDataService',
            '$state',
            function ($scope, __DataStore, __Form, $stateParams, GetEditSupportData, BillingDataService, $compile, __Utils, appServices, InventoryDataService, $state) {

                var scope = this,
                    requestData = GetEditSupportData;

                scope.productData = [];
                scope.currencyCode = requestData.currencyCode;
                scope.currencySymbol = requestData.currencySymbol;
                scope = __Form.setup(scope, 'billing_form', 'billingData');

                scope.billingData.tax_amount = 0;
                scope.billingData.formatted_tax_amount = __globals.priceFormat(0, scope.currencySymbol, '');
                scope.billingData.discount_amount = 0;
                scope.billingData.formatted_discount_amount = __globals.priceFormat(0, scope.currencySymbol, '');
                scope.billingData.tax_type = '1';
                scope.billingData.discount_type = '1';
                scope.dateConfig = {
                    minDate: moment().format('YYYY-MM-DD'),
                    time: false
                };
                var today = moment().format('YYYY-MM-DD');
                scope.billingData.bill_date = today;
                scope.billingData.due_date = today;

                scope.customerSelectConfig = __globals.getSelectizeOptions();
                scope.combinationSelectConfig = __globals.getSelectizeOptions({
                    valueField: 'id',
                    labelField: 'name',
                    searchField: ['name', 'comboSKU', 'barcode', 'combinationTitle', "location_name"],
                    options: [],
                    create: false,
                    loadThrottle: 500,
                    onChange: function (value) {
                        this.clearOptions();
                    },
                    onFocus: function (value) {
                        this.clearOptions();
                    },
                    render: {
                        option: function (item, escape) {

                            return $compile(__Utils.template('#lwSelectizeOp', {
                                item: item
                            }))(scope);
                        }
                    },
                    load: function (searchTerm, callback, event) {

                        var $this = this;
                        var onFocusInput = ($(this)[0]['$input'][0]),
                            inputIndex = $(onFocusInput).data('key');

                        if (!searchTerm.length) return callback();

                        BillingDataService
                            .getProductCombinations(searchTerm)
                            .then(function (responseData) {
                                scope.productData = responseData.productData;
                                _.forEach(scope.productData, function (item) {
                                    if (item.barcode == searchTerm) {
                                        scope.showProductDetails(item.id, inputIndex);
                                        $this.blur();
                                        $this.clearOptions();
                                        $this.focus();
                                    }
                                });

                                if (_.isEmpty(scope.productData)) {
                                    $this.blur();
                                    $this.clearOptions();
                                    $this.focus();
                                }
                                callback(responseData.productData);
                            });
                    }
                });

                scope.userData = requestData.userData;
                scope.customerData = requestData.customerData;
                scope.billingData.customerDetails = null;
                scope.subTotal = 0;
                scope.formattedSubTotal = __globals.priceFormat(0, scope.currencySymbol, scope.currencyCode);
                scope.billingData.totalAmount = 0;
                scope.formattedTotalAmount = __globals.priceFormat(0, scope.currencySymbol, scope.currencyCode);
                scope.billingData.is_add_tax = false;
                scope.billingData.is_add_discount = false;

                scope = __Form.updateModel(scope, requestData.updateData);

                // delete stock transactions 
                scope.deleteStockTransactions = function (transactionId) {

                    var $billTransactionDelete = $('#billTransactionDelete');

                    __globals.showConfirmation({
                        html: $billTransactionDelete.attr('data-message'),
                        confirmButtonText: $billTransactionDelete.attr('data-delete-button-text')
                    },
                        function () {

                            __DataStore.post({
                                'apiURL': 'manage.billing.write.delete_transaction',
                                'billId': $state.params.billUid,
                                'transactionId': transactionId

                            })
                                .success(function (responseData) {

                                    var message = responseData.data.message;

                                    appServices.processResponse(responseData, {

                                        error: function (data) {

                                            __globals.showConfirmation({
                                                title: $billTransactionDelete.attr('data-error-text'),
                                                text: message,
                                                type: 'error'
                                            });

                                        }

                                    },
                                        function (data) {

                                            __globals.showConfirmation({
                                                title: $billTransactionDelete.attr('data-success-text'),
                                                text: message,
                                                type: 'success'
                                            });

                                        });
                                });
                        });
                }

                /*
                * Remove Row
                */
                scope.removeItem = function (index, transactionId) {

                    if (transactionId) {

                        var $billTransactionDelete = $('#billTransactionDelete');

                        __globals.showConfirmation({
                            html: $billTransactionDelete.attr('data-message'),
                            confirmButtonText: $billTransactionDelete.attr('data-delete-button-text')
                        },
                            function () {

                                __DataStore.post({
                                    'apiURL': 'manage.billing.write.delete_transaction',
                                    'billId': $state.params.billUid,
                                    'transactionId': transactionId

                                })
                                    .success(function (responseData) {

                                        var message = responseData.data.message;

                                        appServices.processResponse(responseData, {

                                            error: function (data) {

                                                __globals.showConfirmation({
                                                    title: $billTransactionDelete.attr('data-error-text'),
                                                    text: message,
                                                    type: 'error'
                                                });

                                            }

                                        },
                                            function (data) {

                                                __globals.showConfirmation({
                                                    title: $billTransactionDelete.attr('data-success-text'),
                                                    text: message,
                                                    type: 'success'
                                                });

                                                _.remove(scope.billingData.productCombinations, function (item, key) {
                                                    return key == index;
                                                });

                                            });
                                    });
                            });

                    } else {
                        _.remove(scope.billingData.productCombinations, function (item, key) {
                            return key == index;
                        });
                    }

                }

                /*
                * Add New Row
                */
                scope.addNewRow = function () {
                    scope.billingData.combination = null;

                    scope.billingData.productCombinations.push({
                        'combination': [],
                        'taxes': [],
                        'unit_price': 0,
                        'formattedUnitPrice': __globals.priceFormat(0, scope.currencySymbol, ''),
                        'quantity': 0,
                        'price': 0,
                        'formattedPrice': __globals.priceFormat(0, scope.currencySymbol, ''),
                        'showDetails': false,
                        'isForUpdate': false,
                        'tax_presets__id': null
                    });
                    var comboIndex = scope.billingData.productCombinations.length - 1;

                    // _.defer(function() {
                    //     var input = _.get($('.lw-selectize-parent tr.lw-combination-row-'+comboIndex+' .selectize-input'), 0);
                    //     $(_.get($(input), 0)).triggerHandler('click', true);
                    // });
                }

                /*
                * If Enter Key press on Quantity input
                */
                scope.isEnterKeyPress = function (event, index) {
                    var keyCode = event.which || event.keyCode;
                    if (keyCode === 13) { // Enter Key
                        scope.addNewRow();
                        var inputIndex = index + 1;
                    }
                }

                scope.combinationExists = function (combinationId, locationId) {
                    var exists = false;
                    var combinationId = combinationId;

                    if (!_.isEmpty(scope.billingData.productCombinations)) {
                        _.forEach(scope.billingData.productCombinations, function (value, key) {
                            if ((value.combination.id == combinationId) && (locationId == value.location_id)) {
                                exists = true;
                                scope.billingData.productCombinations[key]['quantity']++;
                                scope.calculateTotalPrice();
                            }
                        });
                    }

                    return exists;
                }

                /*
                * Show Product Details
                */
                scope.showProductDetails = function (combinationId) {

                    var productCombinationData = [];
                    _.forEach(scope.productData, function (item) {
                        if (item.id == combinationId) {
                            productCombinationData = item;
                        }
                    });

                    if (!_.isEmpty(productCombinationData)) {
                        if (!_.isUndefined(productCombinationData.isQuantityExist) && !productCombinationData.isQuantityExist) {

                            /* when quantity exists */
                            scope.showAlertMessage(productCombinationData);

                        } if (productCombinationData.chooseInventryByLocation && productCombinationData.isQuantityExist) {
                            /* when product is available at different locations */
                            scope.chooseInventryByLocation(combinationId, productCombinationData);
                        } else if (productCombinationData.isQuantityExist) {
                            /* when product is available only at one location */
                            scope.updateProductDetails(productCombinationData);
                        }
                    }
                }

                /* insert product combinations */
                scope.updateProductDetails = function (productCombinationData, locationInfo) {

                    var locationId = null;

                    if (!_.isUndefined(locationInfo)) {

                        locationId = locationInfo.location_id;

                        if (!scope.combinationExists(productCombinationData.id, locationId)) {

                            scope.addNewRow();
                            var index = (scope.billingData.productCombinations.length - 1);
                            scope.billingData.productCombinations[index]['showDetails'] = false;
                            scope.billingData.productCombinations[index]['combination'] = productCombinationData;
                            scope.billingData.productCombinations[index]['taxes'] = productCombinationData.taxes;
                            scope.billingData.productCombinations[index]['unit_price'] = productCombinationData.salePrice;
                            scope.billingData.productCombinations[index]['tax_presets__id'] = productCombinationData.tax_presets__id;
                            scope.billingData.productCombinations[index]['formattedUnitPrice'] = productCombinationData.formattedSalePrice;
                            scope.billingData.productCombinations[index]['showDetails'] = true;
                            scope.billingData.productCombinations[index]['quantity'] = 1;
                            scope.billingData.productCombinations[index]['location_name'] = productCombinationData.location_name;
                            scope.billingData.productCombinations[index]['location_id'] = locationInfo.location_id;
                            scope.billingData.productCombinations[index]['location_name'] = locationInfo.location;
                            scope.billingData.productCombinations[index]['combination']['quantity'] = parseInt(locationInfo.quantity);
                            scope.billingData.productCombinations[index]['combination']['lockQuantity'] = parseInt(locationInfo.lockQuantity);
                            scope.billingData.productCombinations[index]['combination']['availableQty'] = parseInt(locationInfo.availableQty);
                        }

                    } else {

                        locationId = productCombinationData.location_id;

                        if (!scope.combinationExists(productCombinationData.id, locationId)) {

                            scope.addNewRow();
                            var index = (scope.billingData.productCombinations.length - 1);
                            scope.billingData.productCombinations[index]['showDetails'] = false;
                            scope.billingData.productCombinations[index]['combination'] = productCombinationData;
                            scope.billingData.productCombinations[index]['taxes'] = productCombinationData.taxes;
                            scope.billingData.productCombinations[index]['unit_price'] = productCombinationData.salePrice;
                            scope.billingData.productCombinations[index]['tax_presets__id'] = productCombinationData.tax_presets__id;
                            scope.billingData.productCombinations[index]['formattedUnitPrice'] = productCombinationData.formattedSalePrice;
                            scope.billingData.productCombinations[index]['showDetails'] = true;
                            scope.billingData.productCombinations[index]['quantity'] = 1;
                            scope.billingData.productCombinations[index]['location_name'] = productCombinationData.location_name;
                            scope.billingData.productCombinations[index]['location_id'] = productCombinationData.location_id;
                        }
                    }

                    scope.calculateTotalPrice();

                }

                /*
                * Show Alert Message
                */
                scope.showAlertMessage = function (combinationData) {

                    var $billingMessagesInfo = $('#billingMessagesInfo');

                    __globals.showConfirmation({
                        text: combinationData.message,
                        showConfirmButton: combinationData.showUpdateInventoryButton,
                        confirmButtonText: $billingMessagesInfo.attr('data-confirm-button-text')
                    },
                        function () {
                            scope.updateInventory(combinationData.productId, combinationData.name, combinationData.id, null, '1', null);
                        });
                }

                /*
                * Calcuate tax
                */
                scope.getCalculatedTax = function (productPrice, taxOptions, index) {
                    var appliedTax = 0;
                    var appliedTaxTotal = 0;
                    var tax = [];
                    scope.billingData.productCombinations[index].tax_details = [];
                    if (!_.isEmpty(taxOptions)) {
                        _.map(taxOptions, function (tax) {

                            appliedTax = 0;
                            if (tax.type == 1) {

                                appliedTax = parseFloat(tax.tax_amount);
                                appliedTaxTotal += appliedTax;
                                scope.billingData.productCombinations[index]['formattedTax'] = scope.currencySymbol + appliedTax;
                            } else if (tax.type == 2) {

                                appliedTax = (productPrice * (parseFloat(tax.tax_amount) / 100));
                                appliedTax = parseFloat(appliedTax);
                                scope.billingData.productCombinations[index]['formattedTax'] = parseFloat(tax.tax_amount).toFixed(2) + "%";
                                appliedTaxTotal += appliedTax;
                            }

                            tax.tax_amount_on_product = appliedTax.toFixed(2);
                            scope.billingData.productCombinations[index].tax_details.push(tax);
                        });
                    }

                    scope.billingData.productCombinations[index]['calculatedTax'] = appliedTaxTotal;
                    scope.billingData.productCombinations[index]['formattedTaxAmount'] = scope.currencySymbol + appliedTaxTotal;
                    return appliedTaxTotal;
                }

                /*
                * Calcuate Total Price
                */
                scope.calculateTotalPrice = function () {
                    var productPrices = [];
                    var productTaxes = [];
                    var unitPriceTotal = [];
                    var totalproductPrice = 0;
                    _.forEach(scope.billingData.productCombinations, function (item, index) {
                        if (!_.isUndefined(item.combination.salePrice)) {
                            var productPrice = item.combination.salePrice * item.quantity;
                            var taxOptions = scope.billingData.productCombinations[index]['taxes'];
                            var calcTax = 0;
                            if (!_.isEmpty(taxOptions)) {
                                calcTax = scope.getCalculatedTax(productPrice, taxOptions, index);
                            }

                            totalproductPrice = productPrice + calcTax;

                            scope.billingData.productCombinations[index]['price'] = productPrice;
                            scope.billingData.productCombinations[index]['formattedPrice'] = __globals.priceFormat(productPrice.toFixed(2), scope.currencySymbol, '');
                            productPrices.push(productPrice);
                            productTaxes.push(scope.billingData.productCombinations[index]['calculatedTax']);
                            unitPriceTotal.push(productPrice);
                        }
                    });

                    // Calculate Subtotal
                    scope.subTotal = _.sum(productPrices);
                    scope.formattedSubTotal = __globals.priceFormat(scope.subTotal, scope.currencySymbol, scope.currencyCode);
                    scope.taxTotalAmount = _.sum(productTaxes);
                    scope.formattedTaxTotalAmount = __globals.priceFormat(scope.taxTotalAmount.toFixed(2), scope.currencySymbol, '');
                    scope.unitPriceTotal = _.sum(unitPriceTotal);
                    scope.formattedUnitPriceTotal = __globals.priceFormat(scope.unitPriceTotal.toFixed(2), scope.currencySymbol, '');

                    // Calculate Tax
                    var taxAmount = 0;
                    if (scope.billingData.is_add_tax) {
                        if (scope.subTotal != 0 && !_.isUndefined(scope.billingData.tax)) {
                            if (scope.billingData.tax_type == 1) {
                                taxAmount = scope.subTotal * scope.billingData.tax / 100;
                            } else if (scope.billingData.tax_type == 2) {
                                taxAmount = scope.billingData.tax;
                            }
                        }
                    }
                    scope.billingData.tax_amount = taxAmount;
                    scope.billingData.formatted_tax_amount = __globals.priceFormat(taxAmount, scope.currencySymbol, '');

                    // Calculate Discount
                    var discountAmount = 0;
                    if (scope.billingData.is_add_discount) {
                        if (scope.subTotal != 0 && !_.isUndefined(scope.billingData.discount)) {
                            if (scope.billingData.discount_type == 1) {
                                discountAmount = scope.subTotal * scope.billingData.discount / 100;
                            } else if (scope.billingData.discount_type == 2) {
                                discountAmount = scope.billingData.discount;
                            }
                        }
                    }

                    scope.billingData.discount_amount = discountAmount;
                    scope.billingData.formatted_discount_amount = __globals.priceFormat(discountAmount, scope.currencySymbol, '');

                    scope.billingData.totalAmount = ((scope.subTotal + scope.taxTotalAmount + taxAmount) - discountAmount);
                    scope.formattedSubTotal = __globals.priceFormat(scope.subTotal, scope.currencySymbol, '');
                    scope.formattedTotalAmount = __globals.priceFormat(scope.billingData.totalAmount.toFixed(2), scope.currencySymbol, '');
                }

                scope.calculateTotalPrice();

                /*
                * Select Customer
                */
                scope.selectCustomer = function (customerId) {
                    if (!_.isEmpty(scope.customerData)) {
                        _.forEach(scope.customerData, function (item) {
                            if (item.id == customerId) {
                                scope.billingData.customerDetails = item;
                            }
                        });
                    }
                };
                scope.selectCustomer(requestData.updateData.customer);

                //set the product inactive 
                scope.setProductInactive = function (productsIds) {

                    _.map(scope.billingData.productCombinations, function (item, index) {
                        if (_.includes(productsIds, parseInt(item.combination.productId))) {
                            scope.billingData.productCombinations[index].combination.product_status = 2;
                        }
                    });
                }

                /*
                Store Billing
                ------------------------------------------------------------ */
                scope.update = function (type) {

                    scope.billingData.type = type;

                    __Form.process({
                        'apiURL': 'manage.billing.write.update_bill',
                        'billId': $stateParams.billUid
                    }, scope)
                        .success(function (responseData) {

                            if (!_.isEmpty(responseData.data.inactiveProducts)) {
                                scope.setProductInactive(responseData.data.inactiveProducts);
                            }

                            appServices.processResponse(responseData, null, function (reaction) {

                                if (reaction == 1) {
                                    $state.go('billing');
                                }
                            });

                        });
                }

                /*
                Update Inventory
                ------------------------------------------------------------ */
                scope.updateInventory = function (productId, productName, combinationId, locationId, type, supplierId) {

                    appServices.showDialog(
                        {
                            productName: productName,
                            productId: productId,
                            type: type,
                            combinationId: combinationId,
                            supplierId: supplierId,
                            showProductList: (_.isEmpty(productId)) ? true : false
                        },
                        {
                            templateUrl: "inventory.update-inventory-dialog",
                            controller: 'UpdateInventoryController as UpdateInventoryCtrl',
                            resolve: {
                                InventoryUpdateData: function () {
                                    return InventoryDataService
                                        .getInventoryUpdateData(productId, combinationId, locationId, supplierId);
                                }
                            }
                        },
                        function (promiseObj) {

                            if (_.has(promiseObj.value, 'inventory_updated')
                                && promiseObj.value.inventory_updated) {
                                var stockUpdateDetails = promiseObj.value.stockDetails;
                                _.forEach(scope.productData, function (item, key) {
                                    if (item.id == combinationId) {
                                        scope.productData[key]['isQuantityExist'] = true;
                                        scope.productData[key]['quantity'] = stockUpdateDetails.quantity;
                                        scope.productData[key]['location_id'] = stockUpdateDetails.location_id;
                                        scope.productData[key]['availableQty'] = stockUpdateDetails.quantity;
                                        scope.productData[key]['location_name'] = stockUpdateDetails.location_name;
                                        _.defer(function () {
                                            scope.showProductDetails(combinationId);
                                        });
                                    }
                                });
                            }
                        });
                }

                /*
                choose Inventry By Location
                ------------------------------------------------------------ */
                scope.chooseInventryByLocation = function (combinationId, productCombinationData) {

                    appServices.showDialog({
                        'combinationId': combinationId,
                        'productId': productCombinationData.productId
                    },
                        {
                            templateUrl: "billing.combination-location-dialog",
                            controller: 'CombinationLocationController as CombinationLocationCtrl',
                            resolve: {
                                CombinationsLocationsData: ['InventoryDataService', function (InventoryDataService) {
                                    return InventoryDataService
                                        .getCombinationsLocationwise(combinationId, productCombinationData.productId);
                                }]
                            }
                        },
                        function (promiseObj) {

                            if (_.has(promiseObj.value, 'location_selected') && promiseObj.value.location_selected) {
                                var locationInfo = promiseObj.value.location;
                                scope.updateProductDetails(productCombinationData, locationInfo);
                            }
                        });

                }
            }
        ])

        /**
          * Billing Details Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('BillingDetailsController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            'GetEditSupportData',
            'BillingDataService',
            '$compile',
            '__Utils',
            'appServices',
            'InventoryDataService',
            '$state',
            function ($scope, __DataStore, __Form, $stateParams, GetEditSupportData, BillingDataService, $compile, __Utils, appServices, InventoryDataService, $state) {

                var scope = this,
                    requestData = GetEditSupportData;

                scope.productData = [];
                scope.currencyCode = requestData.currencyCode;
                scope.currencySymbol = requestData.currencySymbol;
                scope = __Form.setup(scope, 'billing_form', 'billingData');

                scope.billingData.tax_amount = 0;
                scope.billingData.formatted_tax_amount = __globals.priceFormat(0, scope.currencySymbol, '');
                scope.billingData.discount_amount = 0;
                scope.billingData.formatted_discount_amount = __globals.priceFormat(0, scope.currencySymbol, '');
                scope.billingData.tax_type = '1';
                scope.billingData.discount_type = '1';
                scope.userData = requestData.userData;
                scope.customerData = requestData.customerData;
                scope.billingData.customerDetails = null;
                scope.subTotal = 0;
                scope.formattedSubTotal = __globals.priceFormat(0, scope.currencySymbol, scope.currencyCode);
                scope.billingData.totalAmount = 0;
                scope.formattedTotalAmount = __globals.priceFormat(0, scope.currencySymbol, scope.currencyCode);
                scope.billingData.is_add_tax = false;
                scope.billingData.is_add_discount = false;

                scope = __Form.updateModel(scope, requestData.updateData);

                scope.downloadPdfUrl = __Utils.apiURL({
                    'apiURL': 'manage.billing.read.download_pdf',
                    'billId': $stateParams.billUid
                });

                scope.printUrl = __Utils.apiURL({
                    'apiURL': 'manage.billing.read.print_bill',
                    'billId': $stateParams.billUid
                });

                /*
                * Calcuate tax
                */
                scope.getCalculatedTax = function (productPrice, taxOptions, index) {
                    var appliedTax = 0;
                    var appliedTaxTotal = 0;
                    var tax = [];
                    scope.billingData.productCombinations[index].tax_details = [];
                    if (!_.isEmpty(taxOptions)) {
                        _.map(taxOptions, function (tax) {
                            appliedTax = 0;
                            if (tax.type == 1) {

                                appliedTax = parseFloat(tax.tax_amount);
                                appliedTaxTotal += appliedTax;
                                scope.billingData.productCombinations[index]['formattedTax'] = scope.currencySymbol + appliedTax;
                            } else if (tax.type == 2) {

                                appliedTax = (productPrice * (parseFloat(tax.tax_amount) / 100));
                                appliedTax = parseFloat(appliedTax.toFixed(2));
                                scope.billingData.productCombinations[index]['formattedTax'] = parseFloat(tax.tax_amount) + "%";
                                appliedTaxTotal += appliedTax;
                            }

                            tax.tax_amount_on_product = appliedTax.toFixed(2);
                            scope.billingData.productCombinations[index].tax_details.push(tax);
                        });
                    }

                    scope.billingData.productCombinations[index]['calculatedTax'] = appliedTaxTotal;
                    scope.billingData.productCombinations[index]['formattedTaxAmount'] = scope.currencySymbol + appliedTaxTotal;
                    return appliedTaxTotal;
                }

                /*
                * Calcuate Total Price
                */
                scope.calculateTotalPrice = function () {
                    var productPrices = [];
                    var productTaxes = [];
                    var unitPriceTotal = [];
                    var totalproductPrice = 0;
                    _.forEach(scope.billingData.productCombinations, function (item, index) {
                        if (!_.isUndefined(item.combination.salePrice)) {
                            var productPrice = item.combination.salePrice * item.quantity;
                            var taxOptions = scope.billingData.productCombinations[index]['taxes'];
                            var calcTax = 0;
                            if (!_.isEmpty(taxOptions)) {
                                calcTax = scope.getCalculatedTax(productPrice, taxOptions, index);
                            }

                            totalproductPrice = productPrice + calcTax;

                            scope.billingData.productCombinations[index]['price'] = productPrice;
                            scope.billingData.productCombinations[index]['formattedPrice'] = __globals.priceFormat(productPrice.toFixed(2), scope.currencySymbol, '');
                            productPrices.push(productPrice);
                            productTaxes.push(scope.billingData.productCombinations[index]['calculatedTax']);
                            unitPriceTotal.push(productPrice);
                        }
                    });

                    // Calculate Subtotal
                    scope.subTotal = _.sum(productPrices);
                    scope.formattedSubTotal = __globals.priceFormat(scope.subTotal, scope.currencySymbol, scope.currencyCode);
                    scope.taxTotalAmount = _.sum(productTaxes);
                    scope.formattedTaxTotalAmount = __globals.priceFormat(scope.taxTotalAmount.toFixed(2), scope.currencySymbol, '');
                    scope.unitPriceTotal = _.sum(unitPriceTotal);
                    scope.formattedUnitPriceTotal = __globals.priceFormat(scope.unitPriceTotal.toFixed(2), scope.currencySymbol, '');

                    // Calculate Tax
                    var taxAmount = 0;
                    if (scope.subTotal != 0 && !_.isUndefined(scope.billingData.tax)) {
                        if (scope.billingData.tax_type == 1) {
                            taxAmount = scope.subTotal * scope.billingData.tax / 100;
                        } else if (scope.billingData.tax_type == 2) {
                            taxAmount = scope.billingData.tax;
                        }
                    }
                    scope.billingData.tax_amount = taxAmount;
                    scope.billingData.formatted_tax_amount = __globals.priceFormat(taxAmount, scope.currencySymbol, '');

                    // Calculate Discount
                    var discountAmount = 0;
                    if (scope.subTotal != 0 && !_.isUndefined(scope.billingData.discount)) {
                        if (scope.billingData.discount_type == 1) {
                            discountAmount = scope.subTotal * scope.billingData.discount / 100;
                        } else if (scope.billingData.discount_type == 2) {
                            discountAmount = scope.billingData.discount;
                        }
                    }

                    scope.billingData.discount_amount = discountAmount;
                    scope.billingData.formatted_discount_amount = __globals.priceFormat(discountAmount, scope.currencySymbol, '');

                    scope.billingData.totalAmount = ((scope.subTotal + scope.taxTotalAmount + taxAmount) - discountAmount);
                    scope.formattedSubTotal = __globals.priceFormat(scope.subTotal, scope.currencySymbol, '');
                    scope.formattedTotalAmount = __globals.priceFormat(scope.billingData.totalAmount.toFixed(2), scope.currencySymbol, '');
                }

                scope.calculateTotalPrice();

                /*
                * Select Customer
                */
                scope.selectCustomer = function (customerId) {
                    if (!_.isEmpty(scope.customerData)) {
                        _.forEach(scope.customerData, function (item) {
                            if (item.id == customerId) {
                                scope.billingData.customerDetails = item;
                            }
                        });
                    }
                };
                scope.selectCustomer(requestData.updateData.customer);
            }
        ])
        ;

})(window, window.angular);;
/*!
*  Component  : TaxPreset
*  File       : TaxPresetDataServices.js  
*  Engine     : TaxPresetServices 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.TaxPresetDataServices', [])
        .service('TaxPresetDataService', [
            '$q',
            '__DataStore',
            'appServices',
            TaxPresetDataService
        ])

    /*!
     This service use for to get the promise on data
    ----------------------------------------------------------------------------- */

    function TaxPresetDataService($q, __DataStore, appServices) {


        /*
        Get Add Support Data
        -------------------------------------------------------------- */
        this.getAddSupportData = function () {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch('manage.tax_preset.read.support_data')
                .success(function (responseData) {

                    appServices.processResponse(responseData, null, function (reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData.data);
                    });
                });

            //return promise to caller          
            return defferedObject.promise;
        };
        /*
        Get Edit Support Data
        -------------------------------------------------------------- */
        this.getEditSupportData = function (taxPresetIdOrUid) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.tax_preset.read.update.data',
                'taxPresetIdOrUid': taxPresetIdOrUid
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };


    };

})(window, window.angular);;
/*!
*  Component  : TaxPreset
*  File       : TaxPreset.js  
*  Engine     : TaxPreset 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.TaxPresetEngine', [])

        /**
          * Tax Preset Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('TaxPresetController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            function ($scope, __DataStore, __Form, $stateParams) {

                var scope = this;

            }
        ])



        /**
        * Taxpreset List Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object TaxPresetDataService
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('TaxpresetListController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'TaxPresetDataService',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, TaxPresetDataService) {
                var dtColumnsData = [
                    {
                        "name": "title",
                        "orderable": true,
                        'template': "#titleColumnTemplate"
                    },
                    {
                        "name": "short_description",
                    },
                    {
                        "name": "formatted_status",
                        "orderable": false,
                    },
                    {
                        "name": null,
                        "template": "#taxpresetActionColumnTemplate"
                    }
                ],
                    scope = this;

                /**
                * Get general user test as a datatable source object  
                *
                * @return  void
                *---------------------------------------------------------- */

                scope.taxPresetDataTable = __DataStore.dataTable('#lwtaxpresetList', {
                    url: 'manage.tax_preset.read.list',
                    dtOptions: {
                        "searching": true
                    },
                    columnsData: dtColumnsData,
                    scope: $scope
                });

                /*
                Reload current datatable
                ------------------------------------------------------------ */
                scope.reloadDT = function () {
                    __DataStore.reloadDT(scope.taxPresetDataTable);
                };

                // when add new record 
                $scope.$on('taxpreset_added_or_updated', function (data) {

                    if (data) {
                        scope.reloadDT();
                    }

                });

                /**
                * preset  delete 
                *
                * inject suppliersIdUid
                *
                * @return    void
                *---------------------------------------------------------------- */

                scope.delete = function (taxPresetIdOrUid, title) {

                    var $taxpresetDeleteConfirm = $('#taxpresetDeleteConfirm');

                    __globals.showConfirmation({
                        html: __globals.getReplacedString($taxpresetDeleteConfirm,
                            '__name__',
                            _.unescape(title)
                        ),
                        confirmButtonText: $taxpresetDeleteConfirm.attr('data-delete-button-text')
                    },
                        function () {

                            __DataStore.post({
                                'apiURL': 'manage.tax_preset.write.delete',
                                'taxPresetIdOrUid': taxPresetIdOrUid
                            }).success(function (responseData) {

                                var message = responseData.data.message;

                                appServices.processResponse(responseData, {
                                    error: function (data) {
                                        __globals.showConfirmation({
                                            title: $taxpresetDeleteConfirm.attr('data-error-text'),
                                            text: message,
                                            type: 'error'
                                        });
                                    }
                                },
                                    function (data) {
                                        __globals.showConfirmation({
                                            title: $taxpresetDeleteConfirm.attr('data-success-text'),
                                            text: message,
                                            type: 'success'
                                        });
                                        scope.reloadDT();   // reload datatable
                                    });
                            });
                        });
                };

                /*
                add dialog
                ------------------------------------------------------------ */
                scope.openAddDialog = function () {

                    appServices.showDialog(scope, {
                        templateUrl: __globals.getTemplateURL("tax-preset.add-dialog"),
                        controller: 'TaxpresetAddController as taxpresetAddCtrl',
                        resolve: {
                            taxPresetAddData: function () {
                                return TaxPresetDataService
                                    .getAddSupportData();
                            }
                        }
                    }, function (promiseObj) {

                        if (_.has(promiseObj.value, 'taxpreset_added_or_updated')
                            && promiseObj.value.taxpreset_added_or_updated) {

                            scope.reloadDT();
                        }
                    });
                };

                /*
                edit dialog
                ------------------------------------------------------------ */
                scope.openEditDialog = function (taxPresetIdOrUid) {

                    appServices.showDialog({
                        'taxPresetIdOrUid': taxPresetIdOrUid
                    }, {
                        templateUrl: __globals.getTemplateURL("tax-preset.edit-dialog"),
                        controller: 'TaxpresetEditController as taxpresetEditCtrl',
                        resolve: {
                            taxPresetEditData: function () {
                                return TaxPresetDataService
                                    .getEditSupportData(taxPresetIdOrUid);
                            }
                        }
                    }, function (promiseObj) {

                        if (_.has(promiseObj.value, 'taxpreset_added_or_updated') && promiseObj.value.taxpreset_added_or_updated) {

                            scope.reloadDT();
                        }


                    });
                };


            }
        ])
        // Taxpreset List Controller ends here


        /**
        * Taxpreset Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('TaxpresetAddController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope) {

                var scope = this;

                scope.showLoader = true;
                scope = __Form.setup(scope, 'taxpreset_form', 'taxpresetData', {
                    secured: true,
                    unsecuredFields: []
                });

                scope.taxpresetData.status = true;

                /**
                  * Submit form
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process('manage.tax_preset.write.create', scope)
                        .success(function (responseData) {

                            appServices.processResponse(responseData, null, function () {
                                $scope.closeThisDialog({ 'taxpreset_added_or_updated': true });
                            });

                        });
                };

                /**
                  * Close dialog
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };
            }
        ])
        // TaxpresetAddController ends here


        /**
        * Taxpreset Edit Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object taxPresetEditData
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('TaxpresetEditController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'taxPresetEditData',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, taxPresetEditData) {

                var scope = this;
                scope.showLoader = true;

                scope = __Form.setup(scope, 'taxpreset_form', 'taxpresetData', {
                    secured: true,
                    unsecuredFields: []
                });


                var requestData = taxPresetEditData;
                scope = __Form.updateModel(scope, requestData);
                scope.showLoader = false;

                var taxPresetIdOrUid = $scope.ngDialogData.taxPresetIdOrUid;


                /**
                * Submit form
                *
                * @return  void
                *---------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process({
                        'apiURL': 'manage.tax_preset.write.update',
                        'taxPresetIdOrUid': taxPresetIdOrUid
                    }, scope).success(function (responseData) {

                        appServices.processResponse(responseData, null, function () {
                            $scope.closeThisDialog({ 'taxpreset_added_or_updated': true });
                        });
                    });

                };

                /**
                * Close dialog
                *
                * @return  void
                *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };

            }

        ])
        // Taxpreset Edit Controller ends here


        ;

})(window, window.angular);;
/*!
*  Component  : Tax
*  File       : TaxDataServices.js  
*  Engine     : TaxServices 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.TaxDataServices', [])
        .service('TaxDataService', [
            '$q',
            '__DataStore',
            'appServices',
            TaxDataService
        ])

    /*!
     This service use for to get the promise on data
    ----------------------------------------------------------------------------- */

    function TaxDataService($q, __DataStore, appServices) {

        /*
        Get Add Support Data
        -------------------------------------------------------------- */
        this.getAddSupportData = function (taxPresetIdOrUid) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.tax.read.support_data',
                'taxPresetIdOrUid': taxPresetIdOrUid
            })
                .success(function (responseData) {

                    appServices.processResponse(responseData, null, function (reactionCode) {

                        //this method calls when the require        
                        //work has completed successfully        
                        //and results are returned to client        
                        defferedObject.resolve(responseData.data);
                    });
                });

            //return promise to caller          
            return defferedObject.promise;
        };
        /*
        Get Edit Support Data
        -------------------------------------------------------------- */
        this.getEditSupportData = function (taxIdOrUid, taxPresetIdOrUid) {

            //create a differed object
            var defferedObject = $q.defer();

            __DataStore.fetch({
                'apiURL': 'manage.tax.read.update.data',
                'taxIdOrUid': taxIdOrUid,
                'taxPresetIdOrUid': taxPresetIdOrUid
            }).success(function (responseData) {

                appServices.processResponse(responseData, null, function (reactionCode) {

                    //this method calls when the require        
                    //work has completed successfully        
                    //and results are returned to client        
                    defferedObject.resolve(responseData.data);
                });
            });

            //return promise to caller          
            return defferedObject.promise;
        };


    };

})(window, window.angular);;
/*!
*  Component  : Tax
*  File       : Tax.js  
*  Engine     : Tax 
----------------------------------------------------------------------------- */

(function (window, angular, undefined) {

    'use strict';

    angular
        .module('app.TaxEngine', [])

        /**
          * Tax Controller 
          *
          * inject object $scope
          * inject object __DataStore
          * inject object __Form
          * inject object $stateParams
          *
          * @return  void
          *---------------------------------------------------------------- */

        .controller('TaxController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$stateParams',
            function ($scope, __DataStore, __Form, $stateParams) {

                var scope = this;

            }
        ])



        /**
        * Tax List Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object TaxDataService
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('TaxListController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'TaxDataService',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, TaxDataService) {
                var dtColumnsData = [
                    {
                        "name": "title",
                        "orderable": true,
                    },
                    {
                        "name": "formatted_type",
                        "orderable": false,
                    },
                    {
                        "name": "formatted_status",
                        "orderable": false,
                    },
                    {
                        "name": "tax_amount",
                        "orderable": true,
                    },
                    {
                        "name": null,
                        "template": "#taxActionColumnTemplate"
                    }
                ],
                    scope = this;

                /**
                * Get general user test as a datatable source object  
                *
                * @return  void
                *---------------------------------------------------------- */

                scope.taxDataTable = __DataStore.dataTable('#lwTaxList', {
                    url: {
                        'apiURL': 'manage.tax.read.list',
                        'taxPresetIdOrUid': $state.params.taxPresetIdOrUid
                    },
                    dtOptions: {
                        "searching": true
                    },
                    columnsData: dtColumnsData,
                    scope: $scope
                });

                /*
                Reload current datatable
                ------------------------------------------------------------ */
                scope.reloadDT = function () {
                    __DataStore.reloadDT(scope.taxDataTable);
                };

                // when add new record 
                $scope.$on('tax_added_or_updated', function (data) {

                    if (data) {
                        scope.reloadDT();
                    }

                });

                /**
                 * Tax delete 
                 *
                 * inject TaxIdUid
                 *
                 * @return    void
                 *---------------------------------------------------------------- */
                scope.delete = function (taxIdOrUid, title) {

                    var $taxpresetDeleteConfirm = $('#taxpresetDeleteConfirm');

                    __globals.showConfirmation({
                        html: __globals.getReplacedString($taxpresetDeleteConfirm,
                            '__name__',
                            _.unescape(title)
                        ),
                        confirmButtonText: $taxpresetDeleteConfirm.attr('data-delete-button-text')
                    },
                        function () {

                            __DataStore.post({
                                'apiURL': 'manage.tax.write.delete',
                                'taxIdOrUid': taxIdOrUid,
                                'taxPresetIdOrUid': $state.params.taxPresetIdOrUid
                            }).success(function (responseData) {

                                var message = responseData.data.message;

                                appServices.processResponse(responseData, {
                                    error: function (data) {
                                        __globals.showConfirmation({
                                            title: $taxpresetDeleteConfirm.attr('data-error-text'),
                                            text: message,
                                            type: 'error'
                                        });
                                    }
                                },
                                    function (data) {
                                        __globals.showConfirmation({
                                            title: $taxpresetDeleteConfirm.attr('data-success-text'),
                                            text: message,
                                            type: 'success'
                                        });
                                        scope.reloadDT();   // reload datatable
                                    });
                            });
                        });
                };


                /*
                add dialog
                ------------------------------------------------------------ */
                scope.openAddDialog = function () {

                    appServices.showDialog(scope, {
                        templateUrl: __globals.getTemplateURL("tax.add-dialog"),
                        controller: 'TaxAddController as taxAddCtrl',
                        resolve: {
                            TaxAddData: ['TaxDataService', function (TaxDataService) {
                                return TaxDataService.getAddSupportData($state.params.taxPresetIdOrUid);
                            }]
                        }
                    }, function (promiseObj) {

                        if (_.has(promiseObj.value, 'tax_added_or_updated')
                            && promiseObj.value.tax_added_or_updated) {

                            scope.reloadDT();
                        }

                    });

                };




                /*
                edit dialog
                ------------------------------------------------------------ */
                scope.openEditDialog = function (taxIdOrUid) {

                    appServices.showDialog({
                        'taxIdOrUid': taxIdOrUid
                    }, {
                        templateUrl: __globals.getTemplateURL("tax.edit-dialog"),
                        controller: 'TaxEditController as taxEditCtrl',
                        resolve: {
                            TaxEditData: ['TaxDataService', function (TaxDataService) {
                                return TaxDataService
                                    .getEditSupportData(taxIdOrUid, $state.params.taxPresetIdOrUid);
                            }]
                        }
                    }, function (promiseObj) {

                        if (_.has(promiseObj.value, 'tax_added_or_updated') && promiseObj.value.tax_added_or_updated) {

                            scope.reloadDT();
                        }


                    });
                };


            }
        ])
        // Tax List Controller ends here


        /**
        * Tax Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('TaxAddController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'TaxAddData',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, TaxAddData) {

                var scope = this;

                scope.showLoader = true;
                scope = __Form.setup(scope, 'tax_form', 'taxData', {
                    secured: true,
                    unsecuredFields: []
                });

                scope.taxTypes = __globals.generateKeyValueItems(TaxAddData.tax_types);
                scope.taxTypeSelectize = __globals.getSelectizeOptions();
                scope.taxData.status = true;
                /**
                  * Submit form
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process({
                        'apiURL': 'manage.tax.write.create',
                        'taxPresetIdOrUid': $state.params.taxPresetIdOrUid
                    }, scope)
                        .success(function (responseData) {

                            appServices.processResponse(responseData, null, function () {
                                $scope.closeThisDialog({ 'tax_added_or_updated': true });
                            });

                        });
                };

                /**
                  * Close dialog
                  *
                  * @return  void
                  *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };
            }
        ])
        // TaxAddController ends here


        /**
        * Tax Edit Controller
        *
        * inject object $scope
        * inject object __DataStore
        * inject object __Form
        * inject object $state
        * inject object appServices
        * inject object $rootScope
        * inject object taxEditData
        *
        * @return  void
        *---------------------------------------------------------------- */

        .controller('TaxEditController', [
            '$scope',
            '__DataStore',
            '__Form',
            '$state',
            'appServices',
            '$rootScope',
            'TaxEditData',
            function ($scope, __DataStore, __Form, $state, appServices, $rootScope, TaxEditData) {

                var scope = this;
                scope.showLoader = true;

                scope = __Form.setup(scope, 'tax_form', 'taxData', {
                    secured: true,
                    unsecuredFields: []
                });

                var requestData = TaxEditData;
                scope = __Form.updateModel(scope, requestData.updateData);
                scope.showLoader = false;

                var taxIdOrUid = $scope.ngDialogData.taxIdOrUid;

                scope.taxTypes = __globals.generateKeyValueItems(requestData.tax_types);
                scope.taxTypeSelectize = __globals.getSelectizeOptions();

                /**
                * Submit form
                *
                * @return  void
                *---------------------------------------------------------------- */

                scope.submit = function () {

                    __Form.process({
                        'apiURL': 'manage.tax.write.update',
                        'taxIdOrUid': taxIdOrUid,
                        'taxPresetIdOrUid': $state.params.taxPresetIdOrUid
                    }, scope).success(function (responseData) {

                        appServices.processResponse(responseData, null, function () {
                            $scope.closeThisDialog({ 'tax_added_or_updated': true });
                        });
                    });

                };

                /**
                * Close dialog
                *
                * @return  void
                *---------------------------------------------------------------- */

                scope.closeDialog = function () {
                    $scope.closeThisDialog();
                };

            }

        ])
        // Tax Edit Controller ends here


        ;

})(window, window.angular);
//# sourceMappingURL=../source-maps/manage-app.src.js.map

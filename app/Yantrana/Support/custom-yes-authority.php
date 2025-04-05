<?php
/**
 *  YesAuthority Configurations
 *
 *  This configuration file is part of YesAuthority
 *
 *------------------------------------------------------------------------------------------------*/
return [
    /** authority configurations
     *--------------------------------------------------------------------------------------------*/
    'config' => [
        /**
         *   @required - if you want use name other than 'authority.checkpost'
         *   middleware_name - YesAuthority Middleware name
         */
        'middleware_name' => 'authority.checkpost',
        /**
         *   @required
         *   col_user_id - ID column name for users table
         */
        'col_user_id' => 'users__id',

        /**
         *   @required
         *   col_role - Your Role ID column name for users table
         */
        'col_role' => 'user_roles__id',

        /**
         *   @optional - if you want to use dynamic permissions
         *   col_user_permissions - Dynamic Permissions(json) column on users table
         *   This column should contain json encoded array containing 'allow' & 'deny' arrays
         */
        'col_user_permissions' => '__permissions',

        /**
         *   @required
         *   user_model - User Model
         */
        'user_model' => 'App\Yantrana\Components\User\Models\UserAuthorityModel',
        /**
         *   @optional
         *   role_model - Role Model
         */
        'role_model' => 'App\Yantrana\Components\User\Models\UserRole',
        /**
         *   @optional
         *   col_role_id - ID column name for role table
         */
        //'col_role_id'           => '_id',

        /**
         *   @optional
         *   col_role_permissions - Dynamic Permissions(json) column on role table,
         *   This column should contain json encoded array containing 'allow' & 'deny' arrays
         */
        //'col_role_permissions'  => '__permissions'

        'pseudo_access_ids' => [
            'admin', 'stock_incharge',
        ],

        'default_allowed_access_ids' => [],
    ],
    /**
     *  Authority rules
     *
     *  Rules item needs to have 2 arrays with keys allow & deny value of it will be array
     *  containing access ids as required.
     *  wildcard entries are accepted using *
     *  for each section level deny will be more powerful than allow
     *  also key length also matters more is length more
     *--------------------------------------------------------------------------------------------*/
    'rules' => [
        // base rules for all roles user
        'base' => [
            'allow' => [
                '*',
                'manage.dashboard.*',
            ],
            'deny' => [
                'installation.*',
                'admin',
            ],
        ],
        /**
         *  Role Based rules
         *  First level of defense
         *----------------------------------------------------------------------------------------*/
        'roles' => [
            /**
             *  Rules for the Roles for using id (key will be id)
             *------------------------------------------------------------------------------------*/
            // @example given for role id of 1
            // Admin Role
            1 => [
                'allow' => [
                    '*',
                    'admin',
                    'installation.*',
                ],
                'deny' => [
                    'stock_incharge',
                ],
            ],
            // Stock In-charge Role
            2 => [
                'allow' => [
                    'stock_incharge',
                    'manage_inventory',
                    'manage_assigned_location_inventory',
                ],
                'deny' => [
                    'admin',
                    'manage_confguration_setting',
                    'view_only_manage_users',
                    'add_user',
                    'edit_user',
                    'delete_and_restore_user',
                    'view_user_details',
                    'assign_location',
                    'view_only_manage_role',
                    'add_role',
                    'manage_role_permission',
                    'delete_role',
                    'activity_log',
                    'view_activity_log',
                    'view_product',
                    'create_product',
                    'update_product',
                    'delete_product',
                    'view_category',
                    'create_category',
                    'update_category',
                    'delete_category',
                    'view_supplier',
                    'create_supplier',
                    'update_supplier',
                    'delete_supplier',
                    'view_location',
                    'create_location',
                    'update_location',
                    'delete_location',
                    'assign_user',
                    'view_billing',
                    'add_billing',
                    'edit_billing',
                    'delete_billing',
                    'print_or_download_bill',
                    'view_customer',
                    'add_customer',
                    'edit_customer',
                    'delete_customer',
                    'view_tax_preset',
                    'add_tax_preset',
                    'edit_tax_preset',
                    'delete_tax_preset',
                    'view_tax',
                    'add_tax',
                    'edit_tax',
                    'delete_tax',
                    'view_reports',
                    'manage_inventory',
                ],
            ],
        ],

        /**
         *  User based rules
         *  2nd level of defense
         *  Will override the rules of above 1st level(roles) if matched
         *----------------------------------------------------------------------------------------*/
        'users' => [
            /**
             *  Rules for the Users for using id (key will be id)
             *------------------------------------------------------------------------------------*/
            // @example given for user id of 1
            1 => [ // this may be admin user id
                'allow' => [],
                'deny' => [],
            ],
        ],

        /**
         *  DB Role Based rules
         *  3rd level of defense
         *  Will override the rules of above 2nd level(user) if matched
         *  As it will be database based you don't need to do anything here
         *----------------------------------------------------------------------------------------*/

        /**
         *  DB User Based rules
         *  4th level of defense
         *  Will override the rules of above 3rd level(db roles) if matched
         *  As it will be database based you don't need to do anything here
         *----------------------------------------------------------------------------------------*/

        /**  Dynamic permissions based on conditions
         *  Will override the rules of above 4th level(db user) if matched
         *  5th level of defense
         * each condition will be array with following options available:
         *
         *  @key - string - name
         *      @value - string - it will be condition identifier (alpha-numeric-dash)
         *  @key - string - access_ids
         *      @value - array - of ids (alpha-numeric-dash)
         *  @key - string - uses
         *      @value - string - of of classNamespace@method
         *          OR
         *      @value - anonymous function -
         *  @note - both the function/method receive following 3 parameters so you can
         *          run your own magic of logic using it.
         *  $accessIdKey            - string - requested id key
         *  $isAccess               - bool - what is the access received from the above level/condition
         *  $currentRouteAccessId   - current route/accessIds being checked.
         *----------------------------------------------------------------------------------------*/
        'conditions' => [
            // Example conditions
            //  It should return boolean values, true for access allow & false for deny
            [
                'name' => 'demo_authority',
                'access_ids' => ['demo_authority', 'file_manager.*'],
                'uses' => function ($accessId, $isAccess, $currentRouteAccessId) {
                    if (isDemo()) {
                        if ((Auth::id() !== 1) and (Auth::user()->role == 1) and in_array($currentRouteAccessId, [
                            'user.change_password.process',
                            'user.change_email.process',
                            'user.profile.update.process',
                        ])) {
                            return false;
                        }

                        if ((Auth::id() !== 1)
                            and ((in_array($currentRouteAccessId, [
                                'manage.configuration.process',
                                'media.upload.delete',
                                'media.upload.delete_multiple',
                                'file_manager.upload',
                                'manage.product.write.delete',
                                'manage.category.write.delete',
                                'manage.location.write.delete',
                                'manage.suppliers.write.delete',
                            ]))
                                or (str_is('media.upload*.write*', $currentRouteAccessId) === true)
                                or (str_is('file_manager.file.*', $currentRouteAccessId) === true)
                                or (str_is('file_manager.folder.*', $currentRouteAccessId) === true))
                        ) {
                            return false;
                        }
                    }

                    return true;
                },
            ],
        ],
    ],

    /**
     *  Dynamic access zones
     *
     *  Zones can be created for various reasons, when using dynamic permission system
     *  its bad to store direct access ids into database in that case we can create dynamic access
     *  zones which is the group of access ids & these can be handled with one single key id.
     *----------------------------------------------------------------------------------------*/
    'dynamic_access_zones' => [
        'manage_inventory' => [
            'title' => 'Manage Inventory',
            'access_ids' => [],
        ],
        'manage_assigned_location_inventory' => [
            'title' => 'Manage Assigned Location Inventory',
            'access_ids' => [
                'manage.inventory.read.*',
                'manage.inventory.write.*',
            ],
            'dependencies' => [],
            'parent' => 'manage_inventory',
        ],
        // Manage Users
        'manage_users' => [
            'title' => 'Manage Users',
            'access_ids' => [],
        ],
        'view_only_manage_users' => [
            'title' => 'Read',
            'access_ids' => [
                'manage.user.read.datatable.list',
            ],
            'dependencies' => [],
            'parent' => 'manage_users',
        ],
        // Add User
        'add_user' => [
            'title' => 'Create',
            'access_ids' => [
                'manage.user.write.create',
                'manage.user.read.create.support_data',
            ],
            'dependencies' => [
                'view_only_manage_users',
            ],
            'parent' => 'manage_users',
        ],
        // Edit User
        'edit_user' => [
            'title' => 'Update',
            'access_ids' => [
                'manage.user.read.edit_suppport_data',
                'manage.user.write.update_process',
            ],
            'dependencies' => [
                'view_only_manage_users',
            ],
            'parent' => 'manage_users',
        ],
        // Delete User
        'delete_and_restore_user' => [
            'title' => 'Delete',
            'access_ids' => [
                'manage.user.write.delete',
                'manage.user.write.restore',
            ],
            'dependencies' => [
                'view_only_manage_users',
            ],
            'parent' => 'manage_users',
        ],
        'view_user_details' => [
            'title' => 'View Details',
            'access_ids' => [
                'manage.user.read.detail.data',
            ],
            'dependencies' => [
                'view_only_manage_users',
            ],
            'parent' => 'manage_users',
        ],
        'assign_location' => [
            'title' => 'Assign Location',
            'access_ids' => [
                'manage.location.read.assign_location',
                'manage.location.write.location_assign_process',
            ],
            'dependencies' => [
                'view_only_manage_users',
            ],
            'parent' => 'manage_users',
        ],

        // Roles
        'manage_roles' => [
            'title' => 'Manage Roles',
            'access_ids' => [],
        ],
        'view_only_manage_role' => [
            'title' => 'Read',
            'access_ids' => [
                'manage.user.role_permission.read.list',
            ],
            'dependencies' => [],
            'parent' => 'manage_roles',
        ],
        'add_role' => [
            'title' => 'Create',
            'access_ids' => [
                'manage.user.role_permission.read.add_support_data',
                'manage.user.role_permission.read.using_id',
                'manage.user.role_permission.write.role.create',
            ],
            'dependencies' => [
                'view_only_manage_role',
            ],
            'parent' => 'manage_roles',
        ],
        'manage_role_permission' => [
            'title' => 'Update',
            'access_ids' => [
                'manage.user.role_permission.read',
                'manage.user.role_permission.write.create',
            ],
            'dependencies' => [
                'view_only_manage_role',
            ],
            'parent' => 'manage_roles',
        ],
        'delete_role' => [
            'title' => 'Delete',
            'access_ids' => [
                'manage.user.role_permission.write.delete',
            ],
            'dependencies' => [
                'view_only_manage_role',
            ],
            'parent' => 'manage_roles',
        ],
        // Configuration & Settings
        'confguration_setting' => [
            'title' => 'General & Currency Settings',
            'access_ids' => [],
        ],
        'manage_confguration_setting' => [
            'title' => 'Settings',
            'access_ids' => [
                'manage.configuration.get.support.data',
                'manage.configuration.process',
                'media.upload.read_logo',
                'media.upload.read_favicon',
            ],
            'parent' => 'confguration_setting',
        ],
        // Activity Log
        'activity_log' => [
            'title' => 'Activity Log',
            'access_ids' => [],
        ],
        'view_activity_log' => [
            'title' => 'Read',
            'access_ids' => [
                'manage.activity_log.read.list',
                'manage.activity_log.action_type.read.data',
            ],
            'parent' => 'activity_log',
        ],
        // Product
        'manage_product' => [
            'title' => 'Manage Product',
            'access_ids' => [],
        ],
        'view_product' => [
            'title' => 'Read',
            'access_ids' => [
                'manage.product.read.list',
            ],
            'dependencies' => [],
            'parent' => 'manage_product',
        ],
        'create_product' => [
            'title' => 'Create',
            'access_ids' => [
                'manage.product.read.support_data',
                'manage.product.write.create',
            ],
            'dependencies' => [
                'view_product',
            ],
            'parent' => 'manage_product',
        ],
        'update_product' => [
            'title' => 'Update',
            'access_ids' => [
                'manage.product.read.update.data',
                'manage.product.write.update',
            ],
            'dependencies' => [
                'view_product',
            ],
            'parent' => 'manage_product',
        ],
        'delete_product' => [
            'title' => 'Delete',
            'access_ids' => [
                'manage.product.write.delete',
            ],
            'dependencies' => [
                'view_product',
            ],
            'parent' => 'manage_product',
        ],
        // Category
        'manage_category' => [
            'title' => 'Manage Category',
            'access_ids' => [],
        ],
        'view_category' => [
            'title' => 'Read',
            'access_ids' => [
                'manage.category.read.list',
            ],
            'dependencies' => [],
            'parent' => 'manage_category',
        ],
        'create_category' => [
            'title' => 'Create',
            'access_ids' => [
                'manage.category.write.create',
                'manage.category.write.create_from_product',
            ],
            'dependencies' => [
                'view_category',
            ],
            'parent' => 'manage_category',
        ],
        'update_category' => [
            'title' => 'Update',
            'access_ids' => [
                'manage.category.read.update_data',
                'manage.category.write.update',
            ],
            'dependencies' => [
                'view_category',
            ],
            'parent' => 'manage_category',
        ],
        'delete_category' => [
            'title' => 'Delete',
            'access_ids' => [
                'manage.category.write.delete',
            ],
            'dependencies' => [
                'view_category',
            ],
            'parent' => 'manage_category',
        ],
        // Suppliers
        'manage_supplier' => [
            'title' => 'Manage Supplier',
            'access_ids' => [],
        ],
        'view_supplier' => [
            'title' => 'Read',
            'access_ids' => [
                'manage.suppliers.read.list',
            ],
            'dependencies' => [],
            'parent' => 'manage_supplier',
        ],
        'create_supplier' => [
            'title' => 'Create',
            'access_ids' => [
                'manage.suppliers.write.create',
            ],
            'dependencies' => [
                'view_supplier',
            ],
            'parent' => 'manage_supplier',
        ],
        'update_supplier' => [
            'title' => 'Update',
            'access_ids' => [
                'manage.suppliers.read.update.data',
                'manage.suppliers.write.update',
            ],
            'dependencies' => [
                'view_supplier',
            ],
            'parent' => 'manage_supplier',
        ],
        'delete_supplier' => [
            'title' => 'Delete',
            'access_ids' => [
                'manage.suppliers.write.delete',
            ],
            'dependencies' => [
                'view_supplier',
            ],
            'parent' => 'manage_supplier',
        ],
        // Location
        'manage_location' => [
            'title' => 'Manage Location / Warehouse',
            'access_ids' => [],
        ],
        'view_location' => [
            'title' => 'Read',
            'access_ids' => [
                'manage.location.read.list',
            ],
            'dependencies' => [],
            'parent' => 'manage_location',
        ],
        'create_location' => [
            'title' => 'Create',
            'access_ids' => [
                'manage.location.write.create',
            ],
            'dependencies' => [
                'view_location',
            ],
            'parent' => 'manage_location',
        ],
        'update_location' => [
            'title' => 'Update',
            'access_ids' => [
                'manage.location.write.update',
            ],
            'dependencies' => [
                'view_location',
            ],
            'parent' => 'manage_location',
        ],
        'delete_location' => [
            'title' => 'Delete',
            'access_ids' => [
                'manage.location.write.delete',
            ],
            'dependencies' => [
                'view_location',
            ],
            'parent' => 'manage_location',
        ],
        'assign_user' => [
            'title' => 'Assign to User',
            'access_ids' => [
                'manage.location.read.assign_user',
                'manage.location.write.user_assign_process',
            ],
            'dependencies' => [
                'view_location',
            ],
            'parent' => 'manage_location',
        ],

        /*-------------Billing-------------------------*/

        'manage_billing' => [
            'title' => 'Manage Billing',
            'access_ids' => [],
        ],
        'view_billing' => [
            'title' => 'Read',
            'access_ids' => [
                'manage.billing.read.list',
            ],
            'dependencies' => [],
            'parent' => 'manage_billing',
        ],
        'add_billing' => [
            'title' => 'Add Bill',
            'access_ids' => [
                'manage.billing.read.add_support_data',
                'manage.billing.write.store_bill',
                'manage.billing.read.search_combination_for_bill',
                'manage.billing.read.combinations_locationwise',
            ],
            'dependencies' => [
                'view_billing',
            ],
            'parent' => 'manage_billing',
        ],

        'edit_billing' => [
            'title' => 'Update Bill',
            'access_ids' => [
                'manage.billing.read.edit_support_data',
                'manage.billing.write.update_bill',
                'manage.billing.read.search_combination_for_bill',
                'manage.billing.read.combinations_locationwise',
            ],
            'dependencies' => [
                'view_billing',
            ],
            'parent' => 'manage_billing',
        ],

        'delete_billing' => [
            'title' => 'Delete Bill',
            'access_ids' => [
                'manage.billing.write.delete',
            ],
            'dependencies' => [
                'view_billing',
            ],
            'parent' => 'manage_billing',
        ],
        'print_or_download_bill' => [
            'title' => 'View Details/Print/Download Bill',
            'access_ids' => [
                'manage.billing.read.print_bill',
                'manage.billing.read.download_pdf',
                'manage.billing.read.edit_support_data',
            ],
            'dependencies' => [
                'view_billing',
            ],
            'parent' => 'manage_billing',
        ],
        /*-------------/ Billing-------------------------*/

        /*-------------Customers-------------------------*/

        'manage_customer' => [
            'title' => 'Manage Customers',
            'access_ids' => [],
        ],
        'view_customer' => [
            'title' => 'Read',
            'access_ids' => [
                'manage.customer.read.list',
            ],
            'dependencies' => [],
            'parent' => 'manage_customer',
        ],
        'add_customer' => [
            'title' => 'Add',
            'access_ids' => [
                'manage.customer.read.support_data',
                'manage.customer.write.create',
            ],
            'dependencies' => [
                'view_customer',
            ],
            'parent' => 'manage_customer',
        ],

        'edit_customer' => [
            'title' => 'Update',
            'access_ids' => [
                'manage.customer.read.update.data',
                'manage.customer.write.update',
            ],
            'dependencies' => [
                'view_customer',
            ],
            'parent' => 'manage_customer',
        ],
        'delete_customer' => [
            'title' => 'Delete',
            'access_ids' => [
                'manage.customer.write.delete',
            ],
            'dependencies' => [
                'view_customer',
            ],
            'parent' => 'manage_customer',
        ],
        /*-------------/ Customers-------------------------*/

        /*-------------Tax Presets-------------------------*/

        'manage_tax_presets' => [
            'title' => 'Manage Tax Presets',
            'access_ids' => [],
        ],
        'view_tax_preset' => [
            'title' => 'Read',
            'access_ids' => [
                'manage.tax_preset.read.list',
            ],
            'dependencies' => [],
            'parent' => 'manage_tax_presets',
        ],
        'add_tax_preset' => [
            'title' => 'Add',
            'access_ids' => [
                'manage.tax_preset.read.support_data',
                'manage.tax_preset.write.create',
            ],
            'dependencies' => [
                'view_tax_preset',
            ],
            'parent' => 'manage_tax_presets',
        ],

        'edit_tax_preset' => [
            'title' => 'Update',
            'access_ids' => [
                'manage.tax_preset.read.update.data',
                'manage.tax_preset.write.update',
            ],
            'dependencies' => [
                'view_tax_preset',
            ],
            'parent' => 'manage_tax_presets',
        ],
        'delete_tax_preset' => [
            'title' => 'Delete',
            'access_ids' => [
                'manage.tax_preset.write.delete',
            ],
            'dependencies' => [
                'view_tax_preset',
            ],
            'parent' => 'manage_tax_presets',
        ],
        /*-------------/ Tax Presets-------------------------*/

        /*-------------Manage Tax-------------------------*/

        'manage_tax' => [
            'title' => 'Manage Tax',
            'access_ids' => [],
        ],
        'view_tax' => [
            'title' => 'Read',
            'access_ids' => [
                'manage.tax.read.list',
            ],
            'dependencies' => [],
            'parent' => 'manage_tax',
        ],
        'add_tax' => [
            'title' => 'Add',
            'access_ids' => [
                'manage.tax.read.support_data',
                'manage.tax.write.create',
            ],
            'dependencies' => [
                'view_tax',
            ],
            'parent' => 'manage_tax',
        ],

        'edit_tax' => [
            'title' => 'Update',
            'access_ids' => [
                'manage.tax.read.update.data',
                'manage.tax.write.update',
            ],
            'dependencies' => [
                'view_tax',
            ],
            'parent' => 'manage_tax',
        ],
        'delete_tax' => [
            'title' => 'Delete',
            'access_ids' => [
                'manage.tax.write.delete',
            ],
            'dependencies' => [
                'view_tax',
            ],
            'parent' => 'manage_tax',
        ],
        /*-------------/ Manage Tax-------------------------*/

        /*-------------Manage Reports-------------------------*/

        'manage_reports' => [
            'title' => 'Manage Reports',
            'access_ids' => [],
        ],
        'view_reports' => [
            'title' => 'Read',
            'access_ids' => [
                'manage.report.read.list',
                'manage.report.read.support_data',
            ],
            'dependencies' => [],
            'parent' => 'manage_reports',
        ],
        /*-------------/ Manage Reports-------------------------*/

    ],
    /*'entities' => [
        'project' => [
            'model' => 'App\Yantrana\Components\Project\Models\ProjectUserModel',
            'id_column' => 'projects__id',
            'permission_column' => '__permissions',
            'user_id_column' => 'users__id'
        ]
    ]*/
];

<!DOCTYPE html>
<html>
    <head>
        <title><?= e( getConfigurationSettings('name') ) ?> : <?= __tr('Manage') ?></title>
        @include('includes.head-content')

        <?= __yesset([
                'dist/css/vendorlibs-manage.css',
                'dist/css/application*.css'
            ], true) ?>
    </head>
    <body class="lw-has-disabled-block" >
        <!-- Disabled loading block -->
        <div class="lw-disabling-block">
            <div class="lw-processing-window lw-hidden">
                <div class="loader"><?=  __tr('Loading...')  ?></div>
                <div><?= __tr( 'Please wait while we are processing your request...' ) ?></div>
            </div>
        </div>
        <!--/ Disabled loading block -->
        <div class="lw-main-loader lw-show-till-loading">
            <div class="loader"><?=  __tr('Loading...')  ?></div>
            <div><?=  __tr('Please wait a while, System is getting ready for you!!')  ?></div>
        </div>
        <div ng-app="ManageApp" ng-controller="ManageController as manageCtrl" ng-csp ng-cloak ng-strict-di>
        <!-- Show when javascript desable in browser -->
        <noscript>
            <style>.nojs-msg { width: 50%; margin:20px auto}</style>
            <div class="custom-noscript">
                <div class="bs-callout bs-callout-danger nojs-msg">
                  <h4><?= __tr('Oh dear... we are sorry') ?></h4>
                  <em><strong><?= __tr('Javascript') ?></strong> <?= __tr('is disabled in your browser, To use this application please enable javascript &amp; reload page again.') ?></em>
                </div>
            </div>
        </noscript>
        <!-- / Show when javascript desable in browser -->

        @if(isDemo())
        <!-- <style>

            .lw-theme-color-container {
                position: fixed;
                top: 130px;
                left: -68px;
                display: block;
                z-index: 99999;
                background: #fff;
                border-color: #696969;
                transition: .2s ease-in-out;
                background-color: rgba(0, 0, 0, 0.45);
                padding: 10px 8px;
                border-bottom-right-radius: 4px;
                border-top-right-radius: 4px;
            }

            .lw-theme-container-active {
                position: fixed;
                left: 0px;
            }

            .lw-switch {
                position: absolute;
                /* top: -1px; */
                right: -40px;
                background: #fff;
                border: 1px solid;
                border-color: #ababab;
                height: 40px;
                width: 40px;
                color:#ffffff;
                border: none;
                background-color: rgba(0, 0, 0, 0.35);
                padding-left: 14px;
                padding-top: 6px;
                font-size: 1.2em;
                border-bottom-right-radius: 4px;
                border-top-right-radius: 4px;
            }
            .lw-switch:hover > i.fa-cog {
                animation: fa-spin 2s infinite linear;
            }

            a.lw-switch, a.lw-switch:hover {
                text-decoration: none!important;
                color:#ffffff;
            }

            .lw-theme-color-item-block {
                width: 48px;
                display: block;
                overflow: hidden;
                padding: 2px 2px;
            }

            .lw-theme-table-color {
                height: 20px;
                width: 20px;
                float: left;
            }
            .lw-theme-reset-btn {
                font-size: 0.87em;
                padding: 3px;
                border: none;
            }
        </style> -->
        <!-- <div class="lw-theme-color-container shadow">
            <a href title="Change Color Theme" class="lw-switch" ng-click="manageCtrl.showHideThemeContainer()">
                <i class="fa fa-cog"></i>
            </a>
            <span ng-repeat="(index, themeColor) in manageCtrl.themeColors">
            <a class="lw-theme-color-item-block" ng-if="index != 'default'" href="" ng-click="manageCtrl.setThemeColor(index)">
                        <span class="lw-theme-table-color" style="background-color:#[[ themeColor.background ]]"></span>
                        <span class="lw-theme-table-color" style="background-color:#[[ themeColor.text ]]"></span>
                    </a>
               <a ng-if="index == 'default'" type="button" class="btn btn-light btn-sm lw-theme-reset-btn" ng-click="manageCtrl.setThemeColor(index)"><?= __tr('Reset') ?></a>
            </span>
        </div> -->
        @endif

        <div class="lw-main-wrapper">
        	@include('includes.collapsible-sidebar')
            <div class="lw-main-page-container" ng-class="{'lw-no-sidebar': !manageCtrl.isLoggedIn()}">

            	<nav class="navbar navbar-expand-lg navbar-light">
               		<button type="button" class="btn btn-light bg-white border lw-sidebar-collapse ml-1" ng-show="manageCtrl.isLoggedIn()">
                        <i class="fa fa-align-left" aria-hidden="true"></i>
                    </button>

                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                       	<i class="fa fa-bars" aria-hidden="true"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto lw-top-menu">

                        	<li ui-sref-active="active" class="nav-item" ng-show="!manageCtrl.isLoggedIn()">
	                            <a class="nav-link lw-top-menu-link" title="<?= __tr('Login') ?>" ui-sref="login"><?= __tr('Login') ?></a>
	                        </li>
	                        <li ui-sref-active="active" class="nav-item" ng-show="manageCtrl.isLoggedIn()">
	                            <a class="nav-link lw-top-menu-link" title="<?=  __tr('Dashboard')  ?>" ui-sref="dashboard"><i class="fab fa-tachometer" aria-hidden="true"></i> <?= __tr('Dashboard') ?></a>
	                        </li>
                            <li ui-sref-active="active" class="nav-item" ng-show="manageCtrl.isLoggedIn() && canAccess('manage.billing.write.store_bill') && canAccess('manage.billing.read.list')">
	                            <a class="nav-link lw-top-menu-link" title="<?=  __tr('Agregar nueva salida')  ?>" ui-sref="billing_add"><i class="fa fa-plus" aria-hidden="true"></i> <?= __tr('Egreso de productos') ?></a>
	                        </li>
	                        <li ui-sref-active="active" class="nav-item" ng-show="manageCtrl.isLoggedIn()">
	                         	<a class="nav-link lw-top-menu-link" ui-sref-active="active" ng-show="canAccess('manage.inventory.read.list')" title="<?=  __tr('Inventory')  ?>" ui-sref="inventory"><i class="fa fa-list-alt" aria-hidden="true"></i> <?= __tr('Inventario') ?></a>
	                        </li>

                            <li class="nav-item dropdown" ng-show="manageCtrl.isLoggedIn()">
	                            <a class="nav-link dropdown-toggle lw-top-menu-link" href="" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	                                <i class="fa fa-user" aria-hidden="true"></i>
	                                <span ng-bind="manageCtrl.auth_info.profile.full_name" ng-if="!manageCtrl.userUpdateData"></span>
	                                <span ng-bind="manageCtrl.userUpdateData" ng-if="manageCtrl.userUpdateData"></span>
	                            </a>
	                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
	                                <a class="dropdown-item" ui-sref-active="active" ui-sref="profile" title="<?=  __tr('Perfil')  ?>"><?=  __tr('Perfil')  ?></a>
	                                <a class="dropdown-item" ui-sref-active="active" ui-sref="changePassword" title="<?= __tr('Cambiar contraseña') ?>"><?= __tr('Cambiar contraseña') ?></a>
	                                <a class="dropdown-item" ui-sref-active="active" ng-if="manageCtrl.restrict_user_email_update == false && manageCtrl.auth_info.designation != 1" ui-sref="changeEmail" title="<?= __tr('Actualizar correo') ?>"><?= __tr('Actualizar correo') ?></a>
	                                <a class="dropdown-item" ui-sref-active="active" ng-if="manageCtrl.auth_info.designation == 1" ui-sref="changeEmail" title="<?= __tr('Actualizar correo') ?>"><?= __tr('Actualizar correo') ?></a>
	                                <a class="dropdown-item" href ng-click="manageCtrl.logoutUser()" title="<?= __tr('Cerrar Sesión') ?>"><?= __tr('Cerrar Sesión') ?> <i class="fa fa-sign-out"></i></a>
	                            </div>
	                        </li> 
                        </ul>
                    </div>
	            </nav>

		        <!-- main container -->
		         <div class="ui-view-container hide-till-load lw-main-page-content">
		             <div class="lw-component-content" ui-view autoscroll="false"></div>
		             <!--/ child page app-->
		        </div>
		        <!-- / main container -->
            </div>
        </div>


        @push("vendorScripts")
            <?= __yesset([
                    'dist/js/vendorlibs-manage.js'
                ], true) ?>
        @endpush

        @push("appScripts")
            <?= __yesset('dist/js/manage-app.*.js', true) ?>
        @endpush

		<!-- settings update reload button template -->
		<script type="text/ng-template"
				id="lw-settings-update-reload-button-template.html">
		        <input type="hidden" id="lwReloadBtnText" data-message="<i class='fa fa-refresh' aria-hidden='true'></i> <?= __tr("Reload") ?>">
		</script>
		<!-- /settings update reload button template -->
        
        <footer class="lw-footer hide-till-load container-fluid">
        <span class="text-muted">
                <span class="pull-left">
                    <?= getConfigurationSettings('name'). ' - ' ?> &copy; <?= date("Y") ?>
                    <?= getConfigurationSettings('footer_text') ?>
                </span>
                @if(getConfigurationSettings('enable_credit_info') == true)
                    <span class="pull-right">
                        <span> Desarrollado por <strong></strong><a href="https://sararobotics.org/" target="_blank" >SaraRobotics</a></span>
                    </span>
                @endif
            </span>
        </footer>

        @include('includes.javascript-content')
        @include('includes.form-template')
        </div>
        @include('includes.foot-content')
    </body>
</html>

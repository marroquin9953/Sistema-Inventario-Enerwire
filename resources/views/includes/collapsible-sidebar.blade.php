 
<!-- Sidebar  -->
<nav class="lw-sidebar" ng-if="manageCtrl.isLoggedIn()">
    <div class="lw-sidebar-header">
        <h3>
    		<div id="lwchangeHeaderColor" >
               <a class="lw-item-link" ng-if="manageCtrl.isLoggedIn()" ui-sref="dashboard"><img src="<?=  getConfigurationSettings('logo_image_url')  ?>" alt="<?=  getConfigurationSettings('name')  ?>" ></a>
               <a class="lw-item-link" ng-if="!manageCtrl.isLoggedIn()" ui-sref="login"><img  class="logo-image" src="<?=  getConfigurationSettings('logo_image_url')  ?>" alt="<?=  getConfigurationSettings('name')  ?>" ></a>
            </div>
        </h3>
        <strong>
        	<div id="lwchangeHeaderColor" >
               <a class="lw-item-link" ng-if="manageCtrl.isLoggedIn()" ui-sref="dashboard"><img class="lw-small-logo" src="<?=  getConfigurationSettings('small_logo_image_url')  ?>" alt="<?=  getConfigurationSettings('name')  ?>" ></a>
               <a class="lw-item-link" ng-if="!manageCtrl.isLoggedIn()" ui-sref="login"><img  class="logo-image" src="<?=  getConfigurationSettings('small_logo_image_url')  ?>" alt="<?=  getConfigurationSettings('name')  ?>" ></a>
            </div>
        </strong>
    </div>

    <ul class="lw-sidebar-components" ng-if="manageCtrl.isLoggedIn()">
        <li class="active">
            <a class="nav-link lw-sidebar-hover-link" title="<?=  __tr('Dashboard')  ?>" ui-sref="dashboard"><i class="fas fa-tachometer" aria-hidden="true"></i> <span class="lw-sidebar-item-label"><?= __tr('Dashboard') ?></span> </a>
        </li>
        <li class="active">
            <a href=""  data-target="#catalogSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle" ng-show="canAccess('manage.category.read.list') || canAccess('manage.product.read.list') || canAccess('manage.tax_preset.read.list') || canAccess('manage.inventory.read.list') || canAccess('manage.report.read.list') || canAccess('manage.billing.read.list') || canAccess('manage.location.read.list')">
               	<i class="fa fa-book" aria-hidden="true"></i> <span class="lw-sidebar-item-label"><?= __tr('Catalogo') ?> </span>
            </a>
            <ul class="collapse list-unstyled show" id="catalogSubmenu">
                <li>
                    <a class="dropdown-item" ui-sref-active="active" ng-show="canAccess('manage.category.read.list')" ui-sref="categories"><i class="fa fa-th-large" aria-hidden="true"></i> <span class="lw-sidebar-item-label"><?= __tr('Catergorias') ?></span></a>
                </li>
                <li>
                    <a class="dropdown-item" ui-sref-active="active" ng-show="canAccess('manage.product.read.list')" ui-sref="product" title="<?=  __tr('Products')  ?>"><i class="fab fa-product-hunt" aria-hidden="true"></i> <span class="lw-sidebar-item-label"><?= __tr('Productos') ?></span></a>
                </li>
                <li>
                    <a class="dropdown-item" ui-sref-active="active" ng-show="canAccess('manage.tax_preset.read.list')" ui-sref="tax_preset" title="<?=  __tr('Tax Presets')  ?>"><i class="fa fa-percent" aria-hidden="true"></i><span class="lw-sidebar-item-label"> <?= __tr('Preconfiguraciones de impuestos') ?></span></a>
                </li>
                <li>
                    <a class="dropdown-item" ui-sref-active="active" ng-show="canAccess('manage.inventory.read.list')" title="<?=  __tr('Inventory')  ?>" ui-sref="inventory"><i class="fa fa-list-alt" aria-hidden="true"></i><span class="lw-sidebar-item-label"> <?= __tr('Inventario') ?></span></a>
                </li>
                <li>
                    <a class="dropdown-item" ui-sref-active="active" ng-show="canAccess('manage.report.read.list')" ui-sref="report" title="<?=  __tr('Reports')  ?>"><i class="fa fa-list" aria-hidden="true"></i> <span class="lw-sidebar-item-label"><?= __tr('Informes') ?></span></a>
                </li>
                <li>
                    <a class="dropdown-item" ui-sref-active="active" ng-show="canAccess('manage.billing.read.list')" ui-sref="billing" title="<?=  __tr('Billings')  ?>"><i class="fa fa-calculator" aria-hidden="true"></i> <span class="lw-sidebar-item-label"><?= __tr('Egreso de productos') ?></span></a>
                </li>
                <li>
                    <a class="dropdown-item" ui-sref-active="active" ng-show="canAccess('manage.location.read.list')" ui-sref="location" title="<?=  __tr('Locations / Warehouses')  ?>"><i class="fa fa-map-marker" aria-hidden="true"></i><span class="lw-sidebar-item-label"> <?= __tr('Ubicaciones / Almacenes') ?></span></a>
                </li>

            </ul>
        </li>

        <li class="active">
            <a href="" data-target="#peopleSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle" ng-show="canAccess('manage.suppliers.read.list') || canAccess('manage.customer.read.list') || canAccess('manage.user.read.datatable.list') || canAccess('manage.user.role_permission.read.list')">
                <i class="fa fa-users" aria-hidden="true"></i> <span class="lw-sidebar-item-label"><?= __tr('Personal') ?></span>
            </a>

            <ul class="collapse list-unstyled" id="peopleSubmenu">
                <li>
                    <a class="dropdown-item" ui-sref-active="active" ng-show="canAccess('manage.suppliers.read.list')" ui-sref="suppliers" title="<?=  __tr('Proveedores')  ?>"><i class="fa fa-truck" aria-hidden="true"></i> <span class="lw-sidebar-item-label"><?= __tr('Proveedores') ?></span></a>
                </li>
                <li>
                    <a class="dropdown-item" ui-sref-active="active" ng-show="canAccess('manage.customer.read.list')" ui-sref="customer" title="<?=  __tr('Clientes')  ?>"><i class="fa-solid fas fa-user" aria-hidden="true"></i><span class="lw-sidebar-item-label ml-3"><?= __tr('Clientes') ?></span></a>
                </li>
                <li>
                    <a class="dropdown-item" ui-sref-active="active" ng-show="canAccess('manage.user.read.datatable.list')" ui-sref="users" title="<?=  __tr('Usuarios')  ?>"><i class="fa fa-users" aria-hidden="true"></i> <span class="lw-sidebar-item-label"><?= __tr('Usuarios') ?></span></a>
                </li>
                <li>
                    <a class="dropdown-item" ui-sref-active="active" ng-show="canAccess('manage.user.role_permission.read.list')" ui-sref="role_permission" title="<?=  __tr('Roles de usuario')  ?>"><i class="fa fa-users"></i> <span class="lw-sidebar-item-label"><?=  __tr('Roles de usuario')  ?></span></a>
                </li>
            </ul>
        </li>
        <li class="active">
            <a href=""  data-target="#settingsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle" ng-show="canAccess('manage.configuration.process') || canAccess('manage.activity_log.read.list')">
                <i class="fa fa-cogs" aria-hidden="true"></i> <span class="lw-sidebar-item-label"> <?= __tr('Configuraciones') ?></span>
            </a>

            <ul class="collapse list-unstyled" id="settingsSubmenu">
                <li>
                    <a ui-sref="configuration_general" class="dropdown-item" ui-sref-active="active" ng-show="canAccess('manage.configuration.process')"  title="<?=  __tr('Configuración general')  ?>"><i class="fa fa-cog" aria-hidden="true"></i> <span class="lw-sidebar-item-label"> <?=  __tr('Configuración general')  ?></span></a>
                </li>
                <li>
                    <a ui-sref="configuration_currency" class="dropdown-item" ui-sref-active="active" ng-show="canAccess('manage.configuration.process')"  title="<?=  __tr('Currency Settings')  ?>"><i class="fa-solid fas fa-dollar-sign"></i><span class="lw-sidebar-item-label ml-3"> <?=  __tr('Configuración de moneda')  ?></span></a>
                </li>
                <li>
                    <a class="dropdown-item" ui-sref-active="active" ng-show="canAccess('manage.activity_log.read.list')" ui-sref="activity_log" title="<?=  __tr('Activity Log')  ?>"><i class="fa fa-history" aria-hidden="true"></i> <span class="lw-sidebar-item-label"> <?=  __tr('Registro de actividad')  ?></span></a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
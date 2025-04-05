<?php
    /*
    *  Component  : Dashboard
    *  View       : Admin Dashboard  
    *  Engine     : DashboardEngine  
    *  File       : admin-dashboard.blade.php  
    *  Controller : AdminDashboardController  as AdminDashboardCtrl
    ----------------------------------------------------------------------------- */ 
?>

<div>
    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-section-heading">
            <div class="lw-heading">
				<i class="fa-solid fa-gauge-high"></i><?= __tr('Dashboard') ?>
			</div>
        </h3>
    </div>
    <!-- loader -->
    <div class="container mb-3" ng-show="canAccess('manage.inventory.write.update')">
        <div class="row justify-content-md-center">
            <div class="col col-lg-10">
                <!-- <lw-form-selectize-field field-for="search_term" class="lw-selectize">
                    <selectize config='AdminDashboardCtrl.productSelectConfig' 
                        class="lw-search-product form-control lw-form-field" 
                        name="search_term" 
                        ng-model="AdminDashboardCtrl.search_term" 
                        options='AdminDashboardCtrl.products'
                        ng-change="AdminDashboardCtrl.updateInventory(AdminDashboardCtrl.search_term, null, null)"
                        placeholder="<?= __tr('Search Products, Barcodes, Product ID / SKU - for Inventory') ?>" lw-detect-barcode>
                    </selectize>
                </lw-form-selectize-field> -->

                <form id="search-typehead-form" name="form-country_v1" method="GET">
					<div class="typeahead__container">
					    <div class="typeahead__field">
					        <div class="typeahead__query">
					            <input class="lw-product-search-input" id="lw-product-search-input" name="searchProduct[query]" type="search" placeholder="<?= __tr('search products') ?>" autocomplete="off" ng-model="search.searchtext">
					        </div>
					        <div class="typeahead__button">
				                <button type="submit">
				                    <i class="typeahead__search-icon"></i>
				                </button>
				            </div>
					    </div>
					</div>
				</form>
            </div>
        </div>

    </div>

    <div class="card mb-3" ng-show="canAccess('manage.inventory.read.list') || canAccess('manage.category.read.list') || canAccess('manage.product.read.list') || canAccess('manage.suppliers.read.list') || canAccess('manage.location.read.list') || canAccess('manage.activity_log.read.list') || canAccess('manage.user.read.datatable.list') || canAccess('manage.user.role_permission.read.list')">
        <div class="card-header">
            <?= __tr('Quick Access') ?>
        </div>
        <div class="row card-body lw-dashboard-icon-links" lw-filter-list="search">
            
            <a class="col-xs-10 col-sm-5 col-md-3 col-lg-2 lw-conf-item" ng-show="canAccess('manage.inventory.read.list')" ui-sref="inventory" title="<?=  __tr('Inventory')  ?>"><i class="fa fa-list-alt fa-3x"></i><span><?=  __tr('Inventory')  ?></span></a>

            <a class="col-xs-10 col-sm-5 col-md-3 col-lg-2 lw-conf-item" ng-show="canAccess('manage.category.read.list')" ui-sref="categories" title="<?=  __tr('Categories')  ?>"><i class="fa fa-th-large fa-3x"></i><span><?=  __tr('Categories')  ?></span></a>

            <a class="col-xs-10 col-sm-5 col-md-3 col-lg-2 lw-conf-item" ng-show="canAccess('manage.product.read.list')" ui-sref="product" title="<?=  __tr('Products')  ?>"><i class="fab fa-product-hunt fa-3x"></i><span><?=  __tr('Products')  ?></span></a>

            <a class="col-xs-10 col-sm-5 col-md-3 col-lg-2 lw-conf-item" ng-show="canAccess('manage.suppliers.read.list')" ui-sref="suppliers" title="<?=  __tr('Suppliers')  ?>"><i class="fa fa-truck fa-3x"></i><span><?=  __tr('Suppliers')  ?></span></a>
            
            <a class="col-xs-10 col-sm-5 col-md-3 col-lg-2 lw-conf-item" ng-show="canAccess('manage.customer.read.list')" ui-sref="customer" title="<?=  __tr('Customers')  ?>"><i class="fa fa-truck fa-3x"></i><span><?=  __tr('Customers')  ?></span></a>

            <a class="col-xs-10 col-sm-5 col-md-3 col-lg-2 lw-conf-item" ng-show="canAccess('manage.location.read.list')" ui-sref="location" title="<?=  __tr('Location / Warehouse')  ?>"><i class="fa fa-map-marker fa-3x"></i><span><?=  __tr('Location / Warehouse')  ?></span></a>

            <a class="col-xs-10 col-sm-5 col-md-3 col-lg-2 lw-conf-item" ng-show="canAccess('manage.activity_log.read.list')" ui-sref="activity_log" title="<?=  __tr('Activity Log')  ?>"><i class="fa fa-history fa-3x"></i><span><?=  __tr('Activity Log')  ?></span></a>

            <a class="col-xs-10 col-sm-5 col-md-3 col-lg-2 lw-conf-item" ng-show="canAccess('manage.user.read.datatable.list')" ui-sref="users" title="<?=  __tr('Users')  ?>"><i class="fa fa-users fa-3x"></i><span><?=  __tr('Users')  ?></span></a>

            <a class="col-xs-10 col-sm-5 col-md-3 col-lg-2 lw-conf-item" ng-show="canAccess('manage.user.role_permission.read.list')" ui-sref="role_permission" title="<?=  __tr('User Roles')  ?>"><i class="fa fa-users fa-3x"></i><span><?=  __tr('User Roles')  ?></span></a>

            <a class="col-xs-10 col-sm-5 col-md-3 col-lg-2 lw-conf-item" ng-show="canAccess('manage.tax_preset.read.list')" ui-sref="tax_preset" title="<?=  __tr('Tax Presets')  ?>"><i class="fa fa-percent fa-3x"></i><span><?=  __tr('Tax Presets')  ?></span></a>

            <a class="col-xs-10 col-sm-5 col-md-3 col-lg-2 lw-conf-item" ng-show="canAccess('manage.billing.read.list')" ui-sref="billing" title="<?=  __tr('Billings')  ?>"><i class="fa fa-calculator fa-3x"></i><span><?=  __tr('Billings')  ?></span></a>
            <a class="col-xs-10 col-sm-5 col-md-3 col-lg-2 lw-conf-item" ng-show="canAccess('manage.report.read.list')" ui-sref="report" title="<?=  __tr('Reports')  ?>"><i class="fa fa-list fa-3x"></i><span><?=  __tr('Reports')  ?></span></a>

        </div>
    </div>

    <ul class="list-group">
        <li class="list-group-item list-group-item-light">
            <span ng-if="AdminDashboardCtrl.isAdmin">
                <?= __tr('Locations') ?>
            </span>

            <span ng-if="!AdminDashboardCtrl.isAdmin">
                <?= __tr('My Locations') ?>
            </span>
        </li>
        <li class="list-group-item">
            <span ng-if="AdminDashboardCtrl.myLocations.length > 0" ng-repeat="location in AdminDashboardCtrl.myLocations">
                [[ location.name ]]<span ng-if="!$last">,</span><span ng-if="$last">.</span>
            </span>
            <span ng-if="AdminDashboardCtrl.myLocations.length == 0">
                <?= __tr('No locations found.') ?>
            </span>
        </li>
    </ul>    
</div>

<script type="text/_template" id="lwSelectizeOp">

    <div>
        <%= __tData.item.name %>
    </div>

</script>

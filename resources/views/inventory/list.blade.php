<?php 
/*
*  Component  : Inventory
*  View       : InventoryListController
*  Engine     : InventoryEngine  
*  File       : list.blade.php  
*  Controller : InventoryListController as InventoryListCtrl
----------------------------------------------------------------------------- */
?>

<div>
    <div class="lw-section-heading-block">

        <!--  main heading  -->
        <h3 class="lw-section-heading">
            <div class="lw-heading">
                <?= __tr( 'Manage Inventory' ) ?>
            </div>
        </h3>
        <!--  /main heading  -->
    </div>
    <!--/ heading and add button -->

    <div class="form-row">
        <div class="col">
            <lw-form-field field-for="search_term" label="<?= __tr('Search') ?>">
                <input type="search" class="lw-form-field form-control" ng-model="InventoryListCtrl.search_term"
                    ng-model-options="{ debounce: 500 }"
                    ng-change="InventoryListCtrl.searchInvetory(InventoryListCtrl.search_term)" name="search_term" />
            </lw-form-field>
        </div>

        <div class="col">
            <!-- Category -->
            <lw-form-selectize-field field-for="category" label="<?= __tr( 'Category' ) ?>" class="lw-selectize">
                <selectize config='InventoryListCtrl.categorySelectConfig' class=" form-control lw-form-field"
                    name="category" ng-model="InventoryListCtrl.category" ng-required="true"
                    options='InventoryListCtrl.categories' placeholder="<?= __tr('Select Category') ?>"></selectize>
            </lw-form-selectize-field>
            <!-- /Category -->
        </div>

        <div class="col">
            <div class="btn-group lw-form-inline-btn">
                <button type="button" class="btn btn-primary" ng-click="InventoryListCtrl.getInventories()">
                    <?= __tr('Show') ?>
                </button>
                <button type="button" class="btn btn-default" ng-click="InventoryListCtrl.clearFilter()">
                    <?= __tr('Reset') ?>
                </button>
            </div>
        </div>
    </div>
    <hr>

    <div>
        <!-- button -->
        <div class="lw-section-right-content  float-right">
            <button type="button" ng-if="canAccess('manage.product.write.create')"
                title="<?= __tr('New Product Inventory') ?>" class="lw-btn btn btn-sm btn-primary float-right ml-2"
                ng-click="InventoryListCtrl.updateInventory(null, null, null, null, null, null)">
                <i class="fa fa-plus"></i>
                <?= __tr('New Product Inventory') ?>
            </button>
        </div>
        <!--/ button -->
    </div>

    <div class="text-right mb-2">
        <small class="">
            <span>
                <?= __tr('Sort By') ?> <i class="fa fa-sort"></i> :
            </span>
            <a href ng-click="InventoryListCtrl.sortBy('products.name', InventoryListCtrl.sortOrder)">
                <?= __tr('Product Name') ?>
            </a> |
            <a href ng-click="InventoryListCtrl.sortBy('categories.name', InventoryListCtrl.sortOrder)">
                <?= __tr('Category Name') ?>
            </a>
        </small>
    </div>


    <div>
        <div class="card mb-4" ng-repeat="inventory in InventoryListCtrl.invetoryData">
            <div class="card-header">
                [[ inventory.productTitle ]]
                &nbsp;<i ng-if="inventory.productStatus == 2" class="fa fa-eye-slash text-danger"
                    aria-hidden="true"></i>
                <small> <em>from</em>
                    <span ng-if="!canAccess('manage.category.read.list')">
                        [[ inventory.categoryName ]]
                    </span>
                    <a ui-sref="categories" ng-if="canAccess('manage.category.read.list')">[[ inventory.categoryName
                        ]]</a>
                </small>
                &nbsp;<i ng-if="inventory.categoryStatus == 2" class="fa fa-eye-slash text-danger"
                    aria-hidden="true"></i>
                <span class="float-right">
                    <a href
                        ng-click="InventoryListCtrl.getInventoryTransaction(inventory.productId, inventory.productTitle, value.combination_id, 2, null)">Transactions</a>
                    |
                    <span>
                        <?= __tr('Total Stock at All Locations') ?>: <strong
                            ng-bind="inventory.totalStockInAllLocation"></strong>
                    </span>
                </span>
            </div>
            <div class="">
                <table class="table" ng-repeat="(location, combination) in inventory.combinationData">
                    <thead>
                        <tr>
                            <th colspan="6">
                                <?= __tr('Location') ?>: [[ location ]]

                                &nbsp;<i ng-if="combination[0].location_status == 2" class="fa fa-eye-slash text-danger"
                                    aria-hidden="true"></i>

                                <span class="float-right">
                                    <span>
                                        <?= __tr('Total Stock') ?>: <strong>[[ combination.totalStock ]]</strong>
                                    </span>
                                </span>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <?= __tr('SKU') ?>
                            </th>
                            <th width="45%">
                                <?= __tr('Producto') ?>
                            </th>
                            <th class="text-right">
                                <?= __tr('Precio de compra') ?>
                            </th>
                            <th class="text-right">
                                <?= __tr('Precio de venta') ?>
                            </th>
                            <th class="text-right">
                                <?= __tr('Disponible en stock') ?>
                            </th>
                            <th>
                                <?= __tr('Action') ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-if="InventoryListCtrl.isArray(value)" ng-repeat="value in combination">
                            <td>
                                [[ value.product_id ]]
                            </td>
                            <td>
                                [[ value.combination_title ]]
                                <small ng-repeat="option in value.combinations">
                                    - <strong>[[ option.label_name ]]</strong>: [[ option.value_name ]]
                                </small>
                            </td>
                            <td class="text-right">
                                [[ value.formattedPrice ]]
                            </td>
                            <td class="text-right">
                                [[ value.formattedSalePrice ]]
                            </td>
                            <td class="text-right">
                                <strong>
                                    <a href
                                        ng-click="InventoryListCtrl.getInventoryTransaction(inventory.productId, inventory.productTitle, value.combination_id, 1, value.location_id)">
                                        [[ value.totalStock ]]
                                    </a>
                                </strong>
                            </td>
                            <td>
                                <div class="btn-group dropup"
                                    ng-if="value.location_status == 1 && inventory.productStatus == 1 && inventory.categoryStatus == 1 && value.status != 3">
                                    <button type="button" class="btn btn-primary"
                                        ng-click="InventoryListCtrl.updateInventory(inventory.productId, inventory.productTitle, value.combination_id, value.location_id, null, null)">
                                        <?= __tr('Update') ?>
                                    </button>
                                    <button type="button" class="btn dropdown-toggle dropdown-toggle-split"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="sr-only"></span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <div class="dropdown-header">
                                            <?= __tr('Stock In') ?>
                                        </div>
                                        <a class="dropdown-item" href
                                            ng-click="InventoryListCtrl.updateInventory(inventory.productId, inventory.productTitle, value.combination_id, value.location_id, '1', value.supplier_id)">
                                            <?= __tr('Purchase') ?>
                                        </a>
                                        <a class="dropdown-item" href
                                            ng-click="InventoryListCtrl.updateInventory(inventory.productId, inventory.productTitle, value.combination_id, value.location_id, '5', value.supplier_id)">
                                            <?= __tr('Move In') ?>
                                        </a>
                                        <a class="dropdown-item" href
                                            ng-click="InventoryListCtrl.updateInventory(inventory.productId, inventory.productTitle, value.combination_id, value.location_id, '7', value.supplier_id)">
                                            <?= __tr('Sale Return') ?>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <div class="dropdown-header">
                                            <?= __tr('Stock Out') ?>
                                        </div>
                                        <a class="dropdown-item" href
                                            ng-click="InventoryListCtrl.updateInventory(inventory.productId, inventory.productTitle, value.combination_id, value.location_id, '3', value.supplier_id)">
                                            <?= __tr('Purchase Return') ?>
                                        </a>
                                        <a class="dropdown-item" href
                                            ng-click="InventoryListCtrl.updateInventory(inventory.productId, inventory.productTitle, value.combination_id, value.location_id, '4', value.supplier_id)">
                                            <?= __tr('Wastage') ?>
                                        </a>
                                        <a class="dropdown-item" href
                                            ng-click="InventoryListCtrl.updateInventory(inventory.productId, inventory.productTitle, value.combination_id, value.location_id, '6', value.supplier_id)">
                                            <?= __tr('Move Out') ?>
                                        </a>
                                    </div>
                                </div>
                                <div
                                    ng-if="value.location_status != 1 || inventory.productStatus != 1 || inventory.categoryStatus != 1 || value.status == 3">
                                    <i class="fa fa-exclamation-triangle"
                                        title="<?= __tr('Location, Category, Product may not available.') ?>"></i>
                                </div>
                                <button
                                    ng-if="value.location_status == 1 && inventory.productStatus == 1 && inventory.categoryStatus == 1 && value.status != 3"
                                    type="button" class="btn btn-success btn-sm"
                                    ng-click="InventoryListCtrl.updateInventory(inventory.productId, inventory.productTitle, value.combination_id, value.location_id, '1', value.supplier_id)">
                                    <?= __tr('Purchase') ?>
                                </button>

                                <button type="button" class="btn btn-default btn-sm"
                                    ng-click="InventoryListCtrl.getInventoryTransaction(inventory.productId, inventory.productTitle, value.combination_id, 1, value.location_id)">
                                    <?= __tr('Transactions') ?>
                                </button>
                            </td>
                        </tr><!-- end ngRepeat: option in inventory.options -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card mb-4" ng-if="InventoryListCtrl.invetoryData.length == 0">
            <div class="card-body">
                <?= __tr('No Inventories Found.') ?>
            </div>
        </div>
    </div>


    <!-- pagination -->
    <div class="pull-right lw-pagination-container float-right" ng-show="InventoryListCtrl.pageContentLoaded && InventoryListCtrl.paginationLinks">
    </div>
    <!-- /pagination -->
</div>
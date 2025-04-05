<?php 
/*
*  Component  : Dashboard
*  View       : InventoryDetailController
*  Engine     : DashboardEngine  
*  File       : inventory-details-dialog.blade.php  
*  Controller : InventoryDetailController as inventoryDetailCtrl
----------------------------------------------------------------------------- */
?>
<div>
    <!-- Modal Heading -->
    <div class="modal-header">
        <h3 class="modal-title">
            <?= __tr('Inventory of __productName__', [
                '__productName__' => '[[ inventoryDetailCtrl.inventoryData.productName ]]'
            ]) ?>
        </h3>
    </div>
    <!-- /Modal Heading -->

     <!-- Modal Body -->
    <div class="modal-body">
        <div class="card">
            <div class="loader" ng-if="!inventoryDetailCtrl.initialContentLoaded"></div>
            <div class="card-body" ng-if="inventoryDetailCtrl.initialContentLoaded">
                <h5 class="card-title">
                    <?= __tr('Quantity In Stock') ?> : [[ inventoryDetailCtrl.inventoryData.quantity ]] 
                    <button type="button" class="btn btn-default btn-sm mt-2" ng-click="inventoryDetailCtrl.updateInventory(inventoryDetailCtrl.inventoryData._uid, inventoryDetailCtrl.inventoryData.productName, null, null, null, null)"><i class="fa fa-pencil-square-o"></i> <?= __tr('Update Inventory') ?></button>
                </h5>
                <div class="card-text">
                    <h6><?= __tr('Category') ?> : [[ inventoryDetailCtrl.inventoryData.categoryName ]]</h6>
                    <h6><?= __tr('Base Price') ?> : [[ inventoryDetailCtrl.inventoryData.formattedPrice ]]</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th><?= __tr('Options') ?></th>
                                <th><?= __tr('Available Quantity') ?></th>
                                <th><?= __tr('Location') ?></th>
                                <th><?= __tr('Supplier') ?></th>
                                <th><?= __tr('Action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="option in inventoryDetailCtrl.inventoryData.options">
                                <td>
                                    <span ng-repeat="value in option.optionValues">
                                        <strong>[[ value.label ]] : </strong> [[ value.value ]]
                                    </span>
                                </td>
                                <td>
                                    [[ option.quantity ]] 
                                    <button type="button" class="btn btn-default btn-sm" ng-click="inventoryDetailCtrl.updateInventory(inventoryDetailCtrl.inventoryData._uid, inventoryDetailCtrl.inventoryData.productName, option.comboKey, option.locationId, null, option.supplierId)"><i class="fa fa-pencil-square-o"></i> <?= __tr('Update') ?></button>
                                </td>
                                <td>[[ option.locationName ]]</td>
                                <td>[[ option.supplierName ]]</td>
                                <td>
                                    <button type="button" class="btn btn-default btn-sm" ng-click="inventoryDetailCtrl.updateInventory(inventoryDetailCtrl.inventoryData._uid, inventoryDetailCtrl.inventoryData.productName, option.comboKey, option.locationId, '1', option.supplierId)"><?= __tr('New Purchase') ?></button>

                                    <button type="button" class="btn btn-default btn-sm" ng-click="inventoryDetailCtrl.updateInventory(inventoryDetailCtrl.inventoryData._uid, inventoryDetailCtrl.inventoryData.productName, option.comboKey, option.locationId, '2', option.supplierId)"><?= __tr('Record Sale') ?></button>

                                    <button type="button" class="btn btn-default btn-sm" ng-click="inventoryDetailCtrl.updateInventory(inventoryDetailCtrl.inventoryData._uid, inventoryDetailCtrl.inventoryData.productName, option.comboKey, option.locationId, null, option.supplierId)"><?= __tr('Other Update') ?></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
     <!-- /Modal Body -->

    <!-- Modal footer -->
    <div class="modal-footer">
        <button type="button" title="<?= __tr('Close') ?>" class="btn btn-default" ng-click="inventoryDetailCtrl.closeDialog()"><?= __tr('Close') ?></button>
    </div>
    <!-- /Modal footer -->
</div>
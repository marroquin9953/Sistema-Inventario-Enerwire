<?php 
/*
*  Component  : Inventory
*  View       : InventoryTransactionController
*  Engine     : InventoryEngine  
*  File       : transaction-dialog.blade.php  
*  Controller : InventoryTransactionController as InventoryTransactionCtrl
----------------------------------------------------------------------------- */
?>
<div>
    <!-- Modal Heading -->
    <div class="modal-header">
        <h3 class="modal-title">
            <?= __tr('Transactions') ?> : [[ InventoryTransactionCtrl.productName ]]
        </h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Modal Body -->
    <div class="modal-body">
        <div class="table-responsive" ng-if="InventoryTransactionCtrl.tranType == 1">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="6">
                            <span ng-repeat="combination in InventoryTransactionCtrl.combinations">
                                <strong>[[ combination.label_name ]] : </strong> <small>[[ combination.value_name ]]</small>
                            </span> 
                        </th>
                    </tr>
                    <tr>
                        <th width="18%"><?= __tr('Type') ?></th>
                        <th><?= __tr('Transaction On') ?></th>
                        <th><?= __tr('Supplier') ?></th>
                        <th><?= __tr('Location') ?></th>
                        <th><?= __tr('Quantity') ?></th>
                        <th><?= __tr('Balance Stock') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-if="InventoryTransactionCtrl.transactionData.length > 0" ng-repeat="transaction in InventoryTransactionCtrl.transactionData">
                        <td ng-bind="transaction.formatted_type"></td>
                        <td ng-bind="transaction.forammted_created_at"></td>
                        <td ng-bind="transaction.supplier"></td>
                        <td ng-bind="transaction.location"></td>
                        <td align="right">
                            <span class="mr-1">
                                <span class="text-success" ng-if="transaction.type == 2">+ [[ transaction.quantity ]]</span>
                                <span class="text-danger" ng-if="transaction.type == 1">- [[ transaction.quantity ]]</span>
                            </span>
                        </td>
                        <td align="right" ng-bind="transaction.balance_stock"></td>
                    </tr>
                    <tr ng-if="InventoryTransactionCtrl.transactionData.length == 0">
                        <td colspan="6">
                            <?= __tr('No transactins found here.') ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-responsive" ng-if="InventoryTransactionCtrl.tranType == 2">
            <table class="table table-bordered" ng-repeat="transaction in InventoryTransactionCtrl.transactionData">
                <thead>
                    <tr>
                        <th colspan="6">
                            <span ng-repeat="combination in transaction.combinations">
                                <strong>[[ combination.label_name ]] : </strong> <small>[[ combination.value_name ]]</small>
                            </span> 
                        </th>
                    </tr>
                    <tr>
                        <th width="18%"><?= __tr('Type') ?></th>
                        <th><?= __tr('Transaction On') ?></th>
                        <th><?= __tr('Supplier') ?></th>
                        <th><?= __tr('Location') ?></th>
                        <th><?= __tr('Quantity') ?></th>
                        <th><?= __tr('Balance Stock') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="stock in transaction.stockTransactions">
                        <td ng-bind="stock.formatted_type"></td>
                        <td ng-bind="stock.forammted_created_at"></td>
                        <td ng-bind="stock.supplier"></td>
                        <td ng-bind="stock.location"></td>
                        <td align="right">
                            <span class="mr-1">
                                <span class="text-success" ng-if="stock.type == 2">+ [[ stock.quantity ]]</span>
                                <span class="text-danger" ng-if="stock.type == 1">- [[ stock.quantity ]]</span>
                            </span>
                        </td>
                        <td align="right" ng-bind="stock.balance_stock"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /Modal Body -->

    <!-- Modal footer -->
    <div class="modal-footer">
        <button type="button" title="<?= __tr('Close') ?>" class="btn btn-default" ng-click="InventoryTransactionCtrl.closeDialog()"><?= __tr('Close') ?></button>
    </div>
    <!-- /Modal footer -->
</div>
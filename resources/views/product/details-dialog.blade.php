<?php   
/*
*  Component  : Product
*  View       : Product Controller
*  Engine     : ProductEngine  
*  File       : product.list.blade.php  
*  Controller : ProductDetailsCotroller 
----------------------------------------------------------------------------- */ 
?>

<div>
    <!-- Modal Heading -->
    <div class="modal-header">
        <h3 class="modal-title">
            <?= __tr('Product Details') ?>  : [[ ProductDetailsCtrl.details.name ]]
        </h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Modal Body -->
    <div class="modal-body">
        
        <table class="table table-striped">
            <tbody>
                <tr>
                  <th scope="row">Status</th>
                  <td ng-bind="ProductDetailsCtrl.details.status"></td>
                </tr>
                <tr ng-if="ProductDetailsCtrl.details.category_name">
                  <th scope="row">Category</th>
                  <td ng-bind="ProductDetailsCtrl.details.category_name"></td>
                </tr>
                <tr>
                  <th scope="row">Created At</th>
                  <td ng-bind="ProductDetailsCtrl.details.created_at"></td>
                </tr>
                <tr>
                  <th scope="row">Updated At</th>
                  <td ng-bind="ProductDetailsCtrl.details.updated_at"></td>
                </tr>
                <tr ng-if="ProductDetailsCtrl.details.short_description">
                  <th scope="row">Description</th>
                  <td ng-bind="ProductDetailsCtrl.details.short_description"></td>
                </tr>
          </tbody>
        </table>


        <h6 class="lw-text-divider">
            <span><?= __tr('Product Combinations') ?></span>
        </h6>

        <!-- options -->
        <div class="card mb-3" ng-repeat="(labelKey, labelOption) in ProductDetailsCtrl.details.optionLabels">
            <div class="card-header">
                <strong>#00[[ labelKey + 1 ]]</strong>
            </div>
            <div class="card-body">

                <div>
                    <strong>Title : </strong> [[ labelOption.title ]]
                </div>
                <div>
                    <strong>Product ID / SKU : </strong> [[ labelOption.product_id ]]
                </div>
                <div>
                    <strong>Purchase Price : </strong> [[ labelOption.price ]]
                </div>
                <div>
                    <strong>Sell Price : </strong> [[ labelOption.sale_price ]]
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" ng-repeat="(valueKey, optionValue) in labelOption.values">
                        <div class="form-row">
                            <div class="col">
                              Label :  [[ optionValue.label_name ]]
                            </div>
                            <div class="col">

                                Value : [[ optionValue.value_name ]]
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- /options -->
    </div>
    <!-- /Modal Body -->

    <!-- Modal footer -->
    <div class="modal-footer">
        <button type="button" title="<?= __tr('Close') ?>" class="btn btn-gray" ng-click="ProductDetailsCtrl.closeDialog()"><?= __tr('Close') ?></button>
    </div>
    <!-- /Modal footer -->
</div>
<?php 
/*
*  Component  : Inventory
*  View       : UpdateInventoryController
*  Engine     : InventoryEngine  
*  File       : update-inventory-dialog.blade.php  
*  Controller : UpdateInventoryController as UpdateInventoryCtrl
----------------------------------------------------------------------------- */
?>
<div>
    <!-- Modal Heading -->
    <div class="modal-header">
        <h3 class="modal-title">
            <span ng-if="!UpdateInventoryCtrl.showProductList">
                <?= __tr('Update Inventory') ?> : <strong>[[ UpdateInventoryCtrl.product_name ]]</strong>
            </span>
            <span ng-if="UpdateInventoryCtrl.showProductList">
                <?= __tr('Add Product Inventory') ?>
            </span>
        </h3>
    </div>
    <!-- /Modal Heading -->

    <!--  form action -->
    <form class="lw-form lw-ng-form" 
        name="UpdateInventoryCtrl.[[ UpdateInventoryCtrl.ngFormName ]]"
        novalidate>

        <!-- Modal Body -->
        <div class="modal-body">
            <div class="alert alert-warning" ng-if="UpdateInventoryCtrl.showInactiveMessage">
                Product or category of this combination is inactive.
            </div>

            <div class="form-row">
                <div class="col" ng-if="UpdateInventoryCtrl.showProductList">
                    <!-- Product -->
                    <lw-form-selectize-field field-for="product" label="<?= __tr( 'Product' ) ?>" class="lw-selectize">
                        <selectize config='UpdateInventoryCtrl.combinationSelectConfig' class=" form-control lw-form-field" name="product" ng-model="UpdateInventoryCtrl.inventoryData.product" ng-required="true" ng-change="UpdateInventoryCtrl.getProductCombinations(UpdateInventoryCtrl.inventoryData.product)" options='UpdateInventoryCtrl.products' placeholder="<?= __tr('Select Product') ?>"></selectize>
                    </lw-form-selectize-field>
                    <!-- /Product --> 
                </div>
                <div class="col">
                    <lw-form-field field-for="combination" label="Product Combinations">
                       <select class="form-control"
                            ng-model="UpdateInventoryCtrl.inventoryData.combination" 
                            ng-options="combination.id as combination.name for combination in UpdateInventoryCtrl.combinations"
                            ng-change="UpdateInventoryCtrl.updateOption()"
                            name="combination"  
                            ng-disabled="!UpdateInventoryCtrl.showProductList" 
                            ng-required="true"
                            >
                        </select>  
                    </lw-form-field>
                </div>
            </div>
            <!-- ng-disabled="UpdateInventoryCtrl.combinationExist" -->
            <div class="card" ng-if="UpdateInventoryCtrl.combinationOption != ''">
                <div class="card-header">Combinations</div>
                <div class="card-body">
                    [[ UpdateInventoryCtrl.combinationOption ]]
                </div>
            </div>            

            <div class="form-row">
                <div class="col">
                    <lw-form-field field-for="sub_type" label="<?= __tr('Select Type') ?>">
                       <select class="form-control" 
                            ng-model="UpdateInventoryCtrl.inventoryData.sub_type" 
                            ng-options="subTypeKey as subType for (subTypeKey, subType) in UpdateInventoryCtrl.subTypes" 
                            ng-change="UpdateInventoryCtrl.changeCalculationSign(UpdateInventoryCtrl.inventoryData.sub_type)"
                            ng-disabled="UpdateInventoryCtrl.isTypeExist"
                            name="sub_type"   
                            ng-required="true"
                            >
                        </select>  
                    </lw-form-field>                    
                </div>

                <div class="col">
                    <!-- Location -->
                    <lw-form-selectize-field field-for="location" label="<?= __tr( 'Select Your Location' ) ?>" class="lw-selectize">
                        <selectize config='UpdateInventoryCtrl.locationSelectConfig' class="form-control lw-form-field" name="location" ng-model="UpdateInventoryCtrl.inventoryData.location" ng-required="true" ng-change="UpdateInventoryCtrl.updateOption()" options='UpdateInventoryCtrl.locations' placeholder="<?= __tr( 'Select Location' ) ?>"></selectize>
                    </lw-form-selectize-field>
                    <!-- ng-disabled="UpdateInventoryCtrl.isLocationExist" -->
                    <!-- /Location -->
                </div>
            </div>
            
            <div class="form-row">
                <!-- Sub Type -->
                <div class="col">
                    <!-- Supplier -->
                    <lw-form-selectize-field field-for="supplier" label="<?= __tr( 'Supplier' ) ?>" class="lw-selectize">
                        <selectize config='UpdateInventoryCtrl.supplierSelectConfig' class="form-control lw-form-field" name="supplier" ng-model="UpdateInventoryCtrl.inventoryData.supplier" ng-change="UpdateInventoryCtrl.updateOption()" options='UpdateInventoryCtrl.suppliers' ng-disabled="UpdateInventoryCtrl.disableSupplier" ng-required="UpdateInventoryCtrl.supplierRequired" placeholder="<?= __tr('Select Supplier') ?>"></selectize>
                    </lw-form-selectize-field>
                    <!-- /Supplier -->
                </div>
                <!-- /Sub Type -->

                <!--  Quantity  -->
                <div class="col">
                    <lw-form-field field-for="quantity" label="<?=  __tr( 'Quantity' )  ?>"> 
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">[[ UpdateInventoryCtrl.availableStockText ]] : [[ UpdateInventoryCtrl.availableQuantity ]]</span>
                            <span class="input-group-text">[[ UpdateInventoryCtrl.calculationSign ]]</span>
                        </div>
                        <input type="number" 
                            class="form-control lw-form-field" 
                            name="quantity"
                            ng-required="true"
                            min="1"
                            ng-model="UpdateInventoryCtrl.inventoryData.quantity">
                    </div>
                </lw-form-field>
                </div>
                <!--  /Quantity  -->
            </div>
        </div>
        <!-- /Modal Body -->
    </form>

    <!-- Modal footer -->
    <div class="modal-footer">
    
        <button type="button" class="btn btn-primary" title="<?= __tr('Update') ?>" ng-click="UpdateInventoryCtrl.submit($event)" ><?= __tr('Update') ?></button>

        <button type="button" title="<?= __tr('Cancel') ?>" class="btn btn-default" ng-click="UpdateInventoryCtrl.closeDialog()"><?= __tr('Cancel') ?></button>
    </div>
    <!-- /Modal footer -->
</div>
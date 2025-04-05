<?php  
/*
*  Component  : Product
*  View       : Product Controller
*  Engine     : ProductEngine 
*  File       : edit.blade.php  
*  Controller : ProductEditController as ProductEditCtrl
----------------------------------------------------------------------------- */
?> 
<div>
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <div class="lw-heading">
                <?= __tr('Edit Product') ?>
            </div>
        </h3>
        <!-- /main heading -->
    </div>  

    <!-- Delete Product button -->
    <input type="hidden" id="barcodeDeleteConfirmation" 
        data-message="<?= __tr( 'You want to delete <strong> __name__ </strong> barcode.') ?>" 
        data-delete-button-text="<?= __tr('Yes, delete it') ?>" 
        data-success-text="<?= __tr( 'Deleted!') ?>">
    <!-- Delete Product button -->

    <!-- Add product dialog form -->
    <form class="ui form lw-form lw-ng-form" name="productEditCtrl.[[productEditCtrl.ngFormName]]" ng-submit="productEditCtrl.submit()" novalidate>
        
        <div class="form-row">
            <div class="col">
                <!-- Name -->
                <lw-form-field field-for="name" label="<?= __tr('Name') ?>">
                    <input type="text" 
                    class="lw-form-field form-control" 
                    ng-model="productEditCtrl.productData.name" name="name"   
                    ng-required="true"          
                    ng-maxlength="255"             
                />
                </lw-form-field>
                <!-- /Name -->
            </div>
            <div class="col">
                <!-- Category -->
                <lw-form-selectize-field field-for="category_id" label="<?= __tr( 'Category' ) ?>" class="lw-selectize">
                    <selectize config='productEditCtrl.categorySelectConfig' 
                        class=" form-control lw-form-field" 
                        name="category_id" 
                        ng-model="productEditCtrl.productData.category_id" 
                        ng-required="true" 
                        options='productEditCtrl.categories' 
                        placeholder="<?= __tr( 'Select Category' ) ?>">
                    </selectize>
                </lw-form-selectize-field>
                <!-- /Category -->
            </div>
        </div>

        <!-- Tax Type -->
        <lw-form-selectize-field field-for="tax_preset" label="<?= __tr( 'Tax Preset' ) ?>" class="lw-selectize">
            <selectize config='productEditCtrl.presetSelectize' class=" form-control lw-form-field" name="tax_preset" ng-model="productEditCtrl.productData.tax_preset" options='productEditCtrl.taxPresets' placeholder="<?= __tr( 'Tax Preset' ) ?>" ></selectize>
        </lw-form-selectize-field>
        <!-- /Tax Type -->

        <!-- Short_Description -->
        <lw-form-field field-for="short_description" label="<?= __tr('Short Description') ?>">
            <textarea 
                ng-model="productEditCtrl.productData.short_description"
                cols="10" 
                rows="3" 
                class="lw-form-field form-control"
                name="short_description"         
            ng-maxlength="255"             
                ></textarea>
        </lw-form-field>
        <!-- /Short_Description -->

        <!-- Status -->
        <lw-form-checkbox-field field-for="status" label="<?= __tr( 'Active' ) ?>" advance="true" lw-toggle-label="true" v-label="<?= __tr( 'Active' ) ?>" off-label="<?= __tr( 'Inactive' ) ?>">
            <input type="checkbox" 
                class="lw-form-field js-switch"
                name="status"
                ng-model="productEditCtrl.productData.status"
                ui-switch="" />
        </lw-form-checkbox-field>
        <!-- /Status -->

        <h6 class="lw-text-divider">
            <span><?= __tr('Product Combinations') ?></span>
        </h6>

        <div ng-init="productLabelCtrl = productEditCtrl">
            @include('product.label-value-options')            
        </div>

        <!-- action -->
        <div class="lw-actions">
           
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?></button>
            
            <!-- Back  -->
             <a ui-sref="product" title="<?= __tr('Back') ?>" class="lw-btn btn btn-secondary mt-1"><?= __tr('Back') ?></a>
            <!-- /Back  -->
        </div>
        <!-- /action -->
    </form>
</div>
<?php	
/*
*  Component  : Product
*  View       : Product Controller
*  Engine     : ProductEngine  
*  File       : product.list.blade.php  
*  Controller : ProductAddController 
----------------------------------------------------------------------------- */ 
?>
<div class="">
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <div class="lw-heading">
                <?= __tr('Add New Product') ?>
            </div>
        </h3>
        <!-- /main heading -->
    </div>

    <!-- Add product dialog form -->
    <form class="ui form lw-form lw-ng-form" name="productAddCtrl.[[productAddCtrl.ngFormName]]" ng-submit="productAddCtrl.submit()" novalidate >
        
        <!-- Name -->
        <lw-form-field field-for="name" label="<?= __tr('Name') ?>">
            <input type="text" 
				class="lw-form-field form-control"
				ng-model="productAddCtrl.productData.name" name="name"   
                ng-required="true"          
                ng-maxlength="255"             
                />
        </lw-form-field>
        <!-- /Name -->

        <!-- Category -->
        <lw-form-selectize-field field-for="category_id" label="<?= __tr( 'Category' ) ?>" class="lw-selectize">
            <div class="input-group">
                <selectize config='productAddCtrl.categorySelectConfig' 
                    class="form-control lw-form-field lw-addon-selectize" 
                    name="category_id" ng-model="productAddCtrl.productData.category_id" 
                    ng-required="true" 
                    options='productAddCtrl.categories' 
                    placeholder="<?= __tr( 'Select Category' ) ?>">
                </selectize>
                <div class="input-group-append">
                    <label class="input-group-text lw-addon-btn-selectize">
                        <?= __tr('OR') ?>
                    </label>
                </div>
                <input type="text" ng-model="productAddCtrl.category_name" class="form-control" placeholder="<?= __tr('Add New Category') ?>">
                <div class="input-group-append">
                    <button type="button" class="btn btn-primary lw-addon-btn-selectize" type="button" title="<?= __tr('Add') ?>" ng-click="productAddCtrl.addNewCategory(productAddCtrl.category_name)"><?= __tr('Add') ?></button>
                </div>
            </div>
        </lw-form-selectize-field>
        <!-- /Category -->

        <!-- Tax Type -->
        <lw-form-selectize-field field-for="tax_preset" label="<?= __tr( 'Tax Preset' ) ?>" class="lw-selectize">
            <selectize config='productAddCtrl.presetSelectize' class=" form-control lw-form-field" name="tax_preset" ng-model="productAddCtrl.productData.tax_preset" options='productAddCtrl.taxPresets' placeholder="<?= __tr( 'Tax Preset' ) ?>" ></selectize>
        </lw-form-selectize-field>
        <!-- /Tax Type -->

        <!-- Short_Description -->
        <lw-form-field field-for="short_description" label="<?= __tr('Short Description') ?>">
            <textarea 
                ng-model="productAddCtrl.productData.short_description"
                cols="10" 
                rows="3" 
                class="lw-form-field form-control" 
                name="short_description"
                ng-maxlength="255"
                ></textarea>
        </lw-form-field>
        <!-- /Short_Description -->

        <h6 class="lw-text-divider">
            <span><?= __tr('Product Combinations') ?></span>
        </h6>

        <div ng-init="productLabelCtrl = productAddCtrl">
            @include('product.label-value-options')            
        </div>

        <!-- action -->
        <div class="lw-actions mt-2">

            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Add') ?>">
            <?= __tr('Add') ?></button>
            
            <!-- Back  -->
            <a ui-sref="product" title="<?= __tr('Back') ?>" class="lw-btn btn btn-secondary mt-1"><?= __tr('Back') ?></a>
            <!-- /Back  -->
        </div>
        <!-- /action -->
    </form>
</div>
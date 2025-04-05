<?php 
/*
*  Component  : TaxPreset
*  View       : Taxpreset Controller
*  Engine     : TaxPresetEngine  
*  File       : add-dialog.blade.php  
*  Controller : TaxpresetAddController
----------------------------------------------------------------------------- */
?>
<div>
    
    <!-- Modal Heading -->
    <div class="modal-header">
        <h3><?= __tr('Add Tax Preset') ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Add taxpreset dialog form -->
    <form class="ui form lw-form lw-ng-form" name="taxpresetAddCtrl.[[taxpresetAddCtrl.ngFormName]]" ng-submit="taxpresetAddCtrl.submit()" novalidate >

        <!-- Modal Body -->
        <div class="modal-body">

            <!-- Title -->
            <lw-form-field field-for="title" label="<?= __tr('Title') ?>">
                <input type="text" 
                    class="lw-form-field form-control" 
                    ng-model="taxpresetAddCtrl.taxpresetData.title" name="title"   
	                ng-required="true"       
	                ng-minlength="3"       
	                ng-maxlength="150" />
            </lw-form-field>
            <!-- /Title -->
        
            <!-- Description -->
            <lw-form-field field-for="description" label="<?= __tr('Description') ?>">
                <textarea 
                    ng-model="taxpresetAddCtrl.taxpresetData.description"
                    cols="10" 
                    rows="3" 
                    class="lw-form-field form-control"
                    name="description" ng-maxlength="250" ></textarea>
            </lw-form-field>
            <!-- /Description -->

            <!-- Status -->
            <lw-form-checkbox-field field-for="status" label="<?= __tr( 'Active' ) ?>" advance="true" lw-toggle-label="true" v-label="<?= __tr( 'Active' ) ?>" off-label="<?= __tr( 'Inactive' ) ?>">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="status"
                    ng-model="taxpresetAddCtrl.taxpresetData.status"
                    ui-switch="" />
            </lw-form-checkbox-field>
            <!-- /Status -->

        </div>
        <!-- /Modal Body -->

        <!-- Modal footer -->
        <div class="modal-footer">
        
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Add') ?>"><?= __tr('Add') ?></button>

            <button type="button" title="<?= __tr('Cancel') ?>" class="lw-btn btn btn-default" ng-click="taxpresetAddCtrl.closeDialog()"><?= __tr('Cancel') ?></button>
        </div>
        <!-- /Modal footer -->
    </form>
</div>
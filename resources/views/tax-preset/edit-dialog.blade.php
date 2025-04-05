<?php 
/*
*  Component  : TaxPreset
*  View       : Taxpreset Controller
*  Engine     : TaxPresetEngine
*  File       : edit-dialog.blade.php  
*  Controller : TaxpresetEditController
----------------------------------------------------------------------------- */
?> 
<div>

    <!-- Loading (remove the following to stop the loading)-->
    <div class="overlay" ng-show="taxpresetEditCtrl.showLoader">
       <div class="loader"></div>
    </div>
    <!-- end loading -->

    <!-- Modal Heading -->
    <div class="modal-header">
        <h3><?= __tr('Edit Tax Preset') ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Add taxpreset dialog form -->
    <form class="ui form lw-form lw-ng-form" name="taxpresetEditCtrl.[[taxpresetEditCtrl.ngFormName]]" ng-submit="taxpresetEditCtrl.submit()" novalidate >

        <!-- Modal Body -->
        <div class="modal-body">

            <!-- Title -->
            <lw-form-field field-for="title" label="<?= __tr('Title') ?>">
                <input type="text" 
                    class="lw-form-field form-control" 
                    ng-model="taxpresetEditCtrl.taxpresetData.title" name="title"   
                ng-required="true"       
                ng-minlength="3"       
                ng-maxlength="150"/>
            </lw-form-field>
            <!-- /Title -->

            <!-- Description -->
            <lw-form-field field-for="description" label="<?= __tr('Description') ?>">
                <textarea
                    ng-model="taxpresetEditCtrl.taxpresetData.description"
                    cols="10" 
                    rows="3" 
                    class="lw-form-field form-control"
                    name="description" ng-maxlength="255"></textarea>
            </lw-form-field>
            <!-- /Description -->

            <!-- Status -->
            <lw-form-checkbox-field field-for="status" label="<?= __tr( 'Active' ) ?>" advance="true" lw-toggle-label="true" v-label="<?= __tr( 'Active' ) ?>" off-label="<?= __tr( 'Inactive' ) ?>">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="status"
                    ng-model="taxpresetEditCtrl.taxpresetData.status"
                    ui-switch="" />
            </lw-form-checkbox-field>
            <!-- /Status -->
            
        </div>
        <!-- /Modal Body -->
        
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?></button>
            
            <button type="button" title="<?= __tr('Cancel') ?>" class="lw-btn btn btn-default" ng-click="taxpresetEditCtrl.closeDialog()"><?= __tr('Cancel') ?></button>
        </div>
        <!-- /Modal footer -->
    </form>
</div>
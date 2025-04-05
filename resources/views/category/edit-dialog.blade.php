<?php 
/*
*  Component  : Catgory
*  View       : Category Controller
*  Engine     : CategoryEngine  
*  File       : edit-dialog.blade.php  
*  Controller : CategoryEditController as CategoryEditCtrl
----------------------------------------------------------------------------- */
?>
<div>

    <!-- Modal Heading -->
    <div class="modal-header">
        <h3 class="modal-title"><?= __tr('Edit Category') ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Add category dialog form -->
    <form class="ui form lw-form lw-ng-form" name="CategoryEditCtrl.[[CategoryEditCtrl.ngFormName]]" ng-submit="CategoryEditCtrl.submit()" novalidate>

        <!-- Modal Body -->
        <div class="modal-body">

            <!-- Name -->
            <lw-form-field field-for="name" label="<?= __tr('Name') ?>">
                <input type="text" 
                    class="lw-form-field form-control" 
                    ng-model="CategoryEditCtrl.categoryData.name" 
                    name="name"
                    ng-maxlength="45"
                    ng-required="true"                   
                    />
            </lw-form-field>
            <!-- /Name -->

            <!-- Status -->
            <lw-form-checkbox-field field-for="status" label="<?= __tr( 'Active' ) ?>" advance="true" lw-toggle-label="true" v-label="<?= __tr( 'Active' ) ?>" off-label="<?= __tr( 'Inactive' ) ?>">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="status"
                    ng-model="CategoryEditCtrl.categoryData.status"
                    ui-switch="" />
            </lw-form-checkbox-field>
            <!-- /Status -->
        </div>
        <!-- /Modal Body -->

        <!-- Modal footer -->
        <div class="modal-footer">
        
            <button type="submit" class="btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?></button>

            <button type="button" title="<?= __tr('Cancel') ?>" class="btn btn-default" ng-click="CategoryEditCtrl.closeDialog()"><?= __tr('Cancel') ?></button>
        </div>
        <!-- /Modal footer -->
    </form>
</div>
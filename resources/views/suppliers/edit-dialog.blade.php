<?php 
/*
*  Component  : Suppliers
*  View       : Suppliers Controller
*  Engine     : SuppliersEngine
*  File       : edit-dialog.blade.php  
*  Controller : SuppliersEditController
----------------------------------------------------------------------------- */
?> 
<div>

    <!-- Loading (remove the following to stop the loading)-->
    <div class="overlay" ng-show="suppliersEditCtrl.showLoader">
       <div class="loader"></div>
    </div>
    <!-- end loading -->

    <!-- Modal Heading -->
    <div class="modal-header">
        <h3><?= __tr('Edit Supplier') ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Add suppliers dialog form -->
    <form class="ui form lw-form lw-ng-form" name="suppliersEditCtrl.[[suppliersEditCtrl.ngFormName]]" ng-submit="suppliersEditCtrl.submit()" novalidate >

        <!-- Modal Body -->
        <div class="modal-body">

            <!-- Name -->
            <lw-form-field field-for="name" label="<?= __tr('Name') ?>">
                <input type="text" 
                    class="lw-form-field form-control" 
                    ng-model="suppliersEditCtrl.suppliersData.name" name="name"   
                    ng-required="true"          
                    ng-maxlength="150"             
                    />
            </lw-form-field>
            <!-- /Name -->
       
            <!-- Short_Description -->
            <lw-form-field field-for="short description" label="<?= __tr('Description') ?>">
                <textarea
                    ng-model="suppliersEditCtrl.suppliersData.short_description"
                    cols="10" 
                    rows="3" 
                    class="lw-form-field form-control"
                    name="short_description"         
                ng-maxlength="255"             
                    ></textarea>
            </lw-form-field>
            <!-- /Short_Description -->
        </div>
        <!-- /Modal Body -->
        
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?></button>
            
            <button type="button" title="<?= __tr('Cancel') ?>" class="lw-btn btn btn-default" ng-click="suppliersEditCtrl.closeDialog()"><?= __tr('Cancel') ?></button>
        </div>
        <!-- /Modal footer -->
    </form>
</div>
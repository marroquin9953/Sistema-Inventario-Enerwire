<?php 
/*
*  Component  : Suppliers
*  View       : Suppliers Controller
*  Engine     : SuppliersEngine  
*  File       : add-dialog.blade.php  
*  Controller : SuppliersAddController
----------------------------------------------------------------------------- */
?>
<div>
    
    <!-- Modal Heading -->
    <div class="modal-header">
        <h3><?= __tr('Add New Supplier') ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Add suppliers dialog form -->
    <form class="ui form lw-form lw-ng-form" name="suppliersAddCtrl.[[suppliersAddCtrl.ngFormName]]" ng-submit="suppliersAddCtrl.submit()" novalidate >

        <!-- Modal Body -->
        <div class="modal-body">

            <!-- Name -->
            <lw-form-field field-for="name" label="<?= __tr('Name') ?>">
                <input type="text" 
                    class="lw-form-field form-control" 
                    ng-model="suppliersAddCtrl.suppliersData.name" name="name"   
                    ng-required="true"          
                    ng-maxlength="150"             
                    />
            </lw-form-field>
            <!-- /Name -->
        
            <!-- Short Description -->
            <lw-form-field field-for="short description" label="<?= __tr('Description') ?>">
                <textarea 
                    ng-model="suppliersAddCtrl.suppliersData.short_description"
                    cols="10" 
                    rows="3" 
                    class="lw-form-field form-control"
                    name="short_description"         
                    ng-maxlength="255"             
                    ></textarea>
            </lw-form-field>
            <!-- /Short Description -->

        </div>
        <!-- /Modal Body -->

        <!-- Modal footer -->
        <div class="modal-footer">
        
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Add') ?>"><?= __tr('Add') ?></button>

            <button type="button" title="<?= __tr('Cancel') ?>" class="lw-btn btn btn-default" ng-click="suppliersAddCtrl.closeDialog()"><?= __tr('Cancel') ?></button>
        </div>
        <!-- /Modal footer -->
    </form>
</div>
<?php 
/*
*  Component  : Customer
*  View       : Customer Controller
*  Engine     : CustomerEngine
*  File       : edit-dialog.blade.php  
*  Controller : CustomerEditController
----------------------------------------------------------------------------- */
?> 
<div>

    <!-- Loading (remove the following to stop the loading)-->
    <div class="overlay" ng-show="customerEditCtrl.showLoader">
       <div class="loader"></div>
    </div>
    <!-- end loading -->

    <!-- Modal Heading -->
    <div class="modal-header">
        <h3><?= __tr('Edit Customer') ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Add customer dialog form -->
    <form class="ui form lw-form lw-ng-form" name="customerEditCtrl.[[customerEditCtrl.ngFormName]]" ng-submit="customerEditCtrl.submit()" novalidate >

        <!-- Modal Body -->
        <div class="modal-body">

            <!-- Name -->
            <lw-form-field field-for="name" label="<?= __tr('Name') ?>">
                <input type="text" 
                    class="lw-form-field form-control" 
                    ng-model="customerEditCtrl.customerData.name" name="name"   
                    ng-required="true"       
                    ng-maxlength="150"                
                    />
            </lw-form-field>
            <!-- /Name -->

            <!-- Country -->
            <lw-form-selectize-field field-for="country" label="<?= __tr( 'Country' ) ?>" class="lw-selectize">
                <selectize config='customerEditCtrl.countrySelectConfig' class=" form-control lw-form-field" name="country" ng-model="customerEditCtrl.customerData.country" ng-required="true" options='customerEditCtrl.countries' placeholder="<?= __tr( 'Select Country' ) ?>" ></selectize>
            </lw-form-selectize-field>
            <!-- /Country -->
       
            <!-- Description -->
            <lw-form-field field-for="description" label="<?= __tr('Description') ?>">
                <textarea
                    ng-model="customerEditCtrl.customerData.description"
                    cols="10" 
                    rows="3" 
                    class="lw-form-field form-control"
                    name="description"             
                    ></textarea>
            </lw-form-field>
            <!-- /Description -->
        </div>
        <!-- /Modal Body -->
        
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?></button>
            
            <button type="button" title="<?= __tr('Cancel') ?>" class="lw-btn btn btn-default" ng-click="customerEditCtrl.closeDialog()"><?= __tr('Cancel') ?></button>
        </div>
        <!-- /Modal footer -->
    </form>
</div>
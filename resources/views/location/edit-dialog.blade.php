<?php 
/*
*  Component  : Location
*  View       : Location Controller
*  Engine     : LocationEngine
*  File       : edit-dialog.blade.php  
*  Controller : LocationEditController
----------------------------------------------------------------------------- */
?> 
<div>

    <!-- Loading (remove the following to stop the loading)-->
    <div class="overlay" ng-show="locationEditCtrl.showLoader">
       <div class="loader"></div>
    </div>
    <!-- end loading -->

    <!-- Modal Heading -->
    <div class="modal-header">
        <h3><?= __tr('Edit Location / Warehouse') ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Add location dialog form -->
    <form class="ui form lw-form lw-ng-form" name="locationEditCtrl.[[locationEditCtrl.ngFormName]]" ng-submit="locationEditCtrl.submit()" novalidate >

        <!-- Modal Body -->
        <div class="modal-body">

            <div class="form-row">
                <div class="col">
                    <!-- Name -->
                    <lw-form-field field-for="name" label="<?= __tr('Name') ?>">
                        <input type="text" 
                            class="lw-form-field form-control" 
                            ng-model="locationEditCtrl.locationData.name" name="name"   
                            ng-required="true"          
                            ng-maxlength="85"             
                            />
                    </lw-form-field>
                    <!-- /Name -->
                </div>
                <div class="col">
                    <!-- Location_Id -->
                    <lw-form-field field-for="location_id" label="<?= __tr('Location Id') ?>">
                        <input type="text" 
                            class="lw-form-field form-control" 
                            ng-model="locationEditCtrl.locationData.location_id" name="location_id"   
                            ng-required="true"          
                            ng-maxlength="45"             
                            />
                    </lw-form-field>
                    <!-- /Location_Id -->
                </div>
            </div>
       
            <!-- Short_Description -->
            <lw-form-field field-for="short_description" label="<?= __tr('Short Description') ?>">
                <textarea
                    ng-model="locationEditCtrl.locationData.short_description"
                    cols="10" 
                    rows="3" 
                    class="lw-form-field form-control"
                    name="short_description"         
                    ng-maxlength="252"             
                    ></textarea>
            </lw-form-field>
            <!-- /Short_Description -->

            <!-- Status -->
            <lw-form-checkbox-field field-for="status" label="<?= __tr( 'Active' ) ?>" advance="true" lw-toggle-label="true" v-label="<?= __tr( 'Active' ) ?>" off-label="<?= __tr( 'Inactive' ) ?>">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="status"
                    ng-model="locationEditCtrl.locationData.status"
                    ui-switch="" />
            </lw-form-checkbox-field>
            <!-- /Status -->
        </div>
        <!-- /Modal Body -->
        
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?></button>
            
            <button type="button" title="<?= __tr('Cancel') ?>" class="lw-btn btn btn-default" ng-click="locationEditCtrl.closeDialog()"><?= __tr('Cancel') ?></button>
        </div>
        <!-- /Modal footer -->
    </form>
</div>
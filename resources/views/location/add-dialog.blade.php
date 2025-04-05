<?php 
/*
*  Component  : Location
*  View       : Location Controller
*  Engine     : LocationEngine  
*  File       : add-dialog.blade.php  
*  Controller : LocationAddController
----------------------------------------------------------------------------- */
?>
<div>
    
    <!-- Modal Heading -->
    <div class="modal-header">
        <h3><?= __tr('Add New Location / Warehouse') ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Add location dialog form -->
    <form class="ui form lw-form lw-ng-form" name="locationAddCtrl.[[locationAddCtrl.ngFormName]]" ng-submit="locationAddCtrl.submit()" novalidate >

        <!-- Modal Body -->
        <div class="modal-body">

            <div class="form-row">
                <div class="col">
                    <!-- Name -->
                    <lw-form-field field-for="name" label="<?= __tr('Name') ?>">
                        <input type="text" 
                            class="lw-form-field form-control" 
                            ng-model="locationAddCtrl.locationData.name" name="name"   
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
                            ng-model="locationAddCtrl.locationData.location_id" name="location_id"   
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
                    ng-model="locationAddCtrl.locationData.short_description"
                    cols="10" 
                    rows="3" 
                    class="lw-form-field form-control"
                    name="short_description"         
                    ng-maxlength="252"             
                ></textarea>
            </lw-form-field>
            <!-- /Short_Description -->

        </div>
        <!-- /Modal Body -->

        <!-- Modal footer -->
        <div class="modal-footer">
        
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Add') ?>"><?= __tr('Add') ?></button>

            <button type="button" title="<?= __tr('Cancel') ?>" class="lw-btn btn btn-default" ng-click="locationAddCtrl.closeDialog()"><?= __tr('Cancel') ?></button>
        </div>
        <!-- /Modal footer -->
    </form>
</div>
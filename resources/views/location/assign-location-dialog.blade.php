<?php 
/*
*  Component  : Location
*  View       : Location Controller
*  Engine     : LocationEngine  
*  File       : assign-location.blade.php  
*  Controller : AssignLocationController as AssignLocationCtrl
----------------------------------------------------------------------------- */
?>
<div>
    
    <!-- Modal Heading -->
    <div class="modal-header">
        <h3>
            <?= __tr('Assign Location / Warehouse to __name__', [
                '__name__' => '[[ AssignLocationCtrl.userName ]]'
            ]) ?>
        </h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Add location dialog form -->
    <form class="ui form lw-form lw-ng-form" name="AssignLocationCtrl.[[AssignLocationCtrl.ngFormName]]" ng-submit="AssignLocationCtrl.submit()" novalidate >
        
        <!-- Modal Body -->
        <div class="modal-body">
            <!-- Location -->
            <lw-form-selectize-field field-for="locations" label="<?= __tr( 'Locations' ) ?>" class="lw-selectize">
                <selectize config='AssignLocationCtrl.locationSelectConfig' class=" form-control lw-form-field" name="locations" ng-model="AssignLocationCtrl.assignData.locations" ng-required="true" options='AssignLocationCtrl.locationData' placeholder="<?= __tr( 'Select Location' ) ?>" ></selectize>
            </lw-form-selectize-field>
            <!-- /Location --> 
        </div>
        <!-- /Modal Body -->

        <!-- Modal footer -->
        <div class="modal-footer">        
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Assign') ?>"><?= __tr('Assign') ?></button>

            <button type="button" title="<?= __tr('Cancel') ?>" class="lw-btn btn btn-default" ng-click="AssignLocationCtrl.closeDialog()"><?= __tr('Cancel') ?></button>
        </div>
        <!-- /Modal footer -->
    </form>
</div>
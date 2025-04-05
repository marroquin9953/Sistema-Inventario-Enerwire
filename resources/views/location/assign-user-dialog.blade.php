<?php 
/*
*  Component  : Location
*  View       : Location Controller
*  Engine     : LocationEngine  
*  File       : assign-user-dialog.blade.php  
*  Controller : AssignUserDialogController as AssignUserDialogCtrl
----------------------------------------------------------------------------- */
?>
<div>
    
    <!-- Modal Heading -->
    <div class="modal-header">
        <h3>
            <?= __tr('Assign User to __location__ Location / Warehouse', [
                '__location__' => '[[ AssignUserDialogCtrl.locationName ]]'
            ]) ?>
        </h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Add location dialog form -->
    <form class="ui form lw-form lw-ng-form" name="AssignUserDialogCtrl.[[AssignUserDialogCtrl.ngFormName]]" ng-submit="AssignUserDialogCtrl.submit()" novalidate >
        
        <!-- Modal Body -->
        <div class="modal-body">
            <!-- Users -->
            <lw-form-selectize-field field-for="users" label="<?= __tr( 'Users' ) ?>" class="lw-selectize">
                <selectize config='AssignUserDialogCtrl.userSelectConfig' class=" form-control lw-form-field" name="users" ng-model="AssignUserDialogCtrl.assignData.users" ng-required="true" options='AssignUserDialogCtrl.userData' placeholder="<?= __tr( 'Select Users' ) ?>" ></selectize>
            </lw-form-selectize-field>
            <!-- /Users --> 
        </div>
        <!-- /Modal Body -->

        <!-- Modal footer -->
        <div class="modal-footer">        
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Assign') ?>"><?= __tr('Assign') ?></button>

            <button type="button" title="<?= __tr('Cancel') ?>" class="lw-btn btn btn-default" ng-click="AssignUserDialogCtrl.closeDialog()"><?= __tr('Cancel') ?></button>
        </div>
        <!-- /Modal footer -->
    </form>
</div>
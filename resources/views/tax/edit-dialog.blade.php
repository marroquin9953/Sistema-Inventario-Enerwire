<?php 
/*
*  Component  : Tax
*  View       : Tax Controller
*  Engine     : TaxEngine
*  File       : edit-dialog.blade.php  
*  Controller : TaxEditController
----------------------------------------------------------------------------- */
?> 
<div>

    <!-- Loading (remove the following to stop the loading)-->
    <div class="overlay" ng-show="taxEditCtrl.showLoader">
       <div class="loader"></div>
    </div>
    <!-- end loading -->

    <!-- Modal Heading -->
    <div class="modal-header">
        <h3><?= __tr('Tax Edit') ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Add Tax dialog form -->
    <form class="ui form lw-form lw-ng-form" name="taxEditCtrl.[[taxEditCtrl.ngFormName]]" ng-submit="taxEditCtrl.submit()" novalidate >

        <!-- Modal Body -->
        <div class="modal-body">

            <!-- Title -->
            <lw-form-field field-for="title" label="<?= __tr('Title') ?>">
                <input type="text" 
                    class="lw-form-field form-control" 
                    ng-model="taxEditCtrl.taxData.title" name="title"   
                ng-required="true"/>
            </lw-form-field>
            <!-- /Title -->
        	
        	<div class="form-row">
        		<div class="col-lg-6">
		            <!-- Tax Type -->
		            <lw-form-selectize-field field-for="type" label="<?= __tr( 'Type' ) ?>" class="lw-selectize">
		                <selectize config='taxEditCtrl.taxTypeSelectize' class=" form-control lw-form-field" name="type" ng-model="taxEditCtrl.taxData.type" ng-required="true" options='taxEditCtrl.taxTypes' placeholder="<?= __tr( 'Type' ) ?>" ></selectize>
		            </lw-form-selectize-field>
		            <!-- /Tax Type -->       
        		</div>
        		
        		<div class="col-lg-6">
		            <!-- Amount -->
		            <lw-form-field field-for="amount" label="<?= __tr('Amount/Rate') ?>">
		                <input type="number" 
		                    class="lw-form-field form-control" 
		                    ng-model="taxEditCtrl.taxData.amount" name="amount"/>
		            </lw-form-field>
		            <!-- /Amount -->
        		</div>
        	</div>

        	<!-- Status -->
            <lw-form-checkbox-field field-for="status" label="<?= __tr( 'Active' ) ?>" advance="true" lw-toggle-label="true" v-label="<?= __tr( 'Active' ) ?>" off-label="<?= __tr( 'Inactive' ) ?>">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="status"
                    ng-model="taxEditCtrl.taxData.status"
                    ui-switch="" />
            </lw-form-checkbox-field>
            <!-- /Status -->

        </div>
        <!-- /Modal Body -->
        
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?></button>
            
            <button type="button" title="<?= __tr('Cancel') ?>" class="lw-btn btn btn-default" ng-click="taxEditCtrl.closeDialog()"><?= __tr('Cancel') ?></button>
        </div>
        <!-- /Modal footer -->
    </form>
</div>
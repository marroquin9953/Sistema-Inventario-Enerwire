<?php 
/*
*  Component  : Tax
*  View       : Tax Controller
*  Engine     : TaxEngine  
*  File       : add-dialog.blade.php  
*  Controller : TaxAddController
----------------------------------------------------------------------------- */
?>
<div>
    
    <!-- Modal Heading -->
    <div class="modal-header">
        <h3><?= __tr('Add Tax') ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Add Tax dialog form -->
    <form class="ui form lw-form lw-ng-form" name="taxAddCtrl.[[taxAddCtrl.ngFormName]]" ng-submit="taxAddCtrl.submit()" novalidate >

        <!-- Modal Body -->
        <div class="modal-body">

            <!-- Title -->
            <lw-form-field field-for="title" label="<?= __tr('Title') ?>">
                <input type="text" 
                    class="lw-form-field form-control" 
                    ng-model="taxAddCtrl.taxData.title" name="title"   
                ng-required="true"                   
                    />
            </lw-form-field>
            <!-- /Title -->
        	
        	<div class="form-row">
        		<div class="col-lg-6">
        			<!-- Tax Type -->
		            <lw-form-selectize-field field-for="type" label="<?= __tr( 'Type' ) ?>" class="lw-selectize">
		                <selectize config='taxAddCtrl.taxTypeSelectize' class=" form-control lw-form-field" name="type" ng-model="taxAddCtrl.taxData.type" ng-required="true" options='taxAddCtrl.taxTypes' placeholder="<?= __tr( 'Type' ) ?>" ></selectize>
		            </lw-form-selectize-field>
		            <!-- /Tax Type -->
        		</div>
        		
        		<div class="col-lg-6">
        			<!-- Amount -->
		            <lw-form-field field-for="amount" label="<?= __tr('Amount/Rate') ?>">
		            	<input type="number" 
			            class="lw-form-field form-control" 
			            ng-model="taxAddCtrl.taxData.amount"
			            name="amount"
			            ng-required="true"
			            min="0"/>
		            </lw-form-field>
		            <!-- /Amount -->
        		</div>
        	</div>

        	<!-- Status -->
            <lw-form-checkbox-field field-for="status" label="<?= __tr( 'Active' ) ?>" advance="true" lw-toggle-label="true" v-label="<?= __tr( 'Active' ) ?>" off-label="<?= __tr( 'Inactive' ) ?>">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="status"
                    ng-model="taxAddCtrl.taxData.status"
                    ui-switch="" />
            </lw-form-checkbox-field>
            <!-- /Status -->
        
        </div>
        <!-- /Modal Body -->

        <!-- Modal footer -->
        <div class="modal-footer">
        
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Add') ?>"><?= __tr('Add') ?></button>

            <button type="button" title="<?= __tr('Cancel') ?>" class="lw-btn btn btn-default" ng-click="taxAddCtrl.closeDialog()"><?= __tr('Cancel') ?></button>
        </div>
        <!-- /Modal footer -->
    </form>
</div>
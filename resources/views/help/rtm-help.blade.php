<?php
/*
*  Component  : Help
*  View       : Help
*  Engine     : MasterEngine
*  File       : users-help.blade.php  
*  Controller : ManageController
----------------------------------------------------------------------------- */ 
?>
	<!-- Modal Heading -->
    <div class="modal-header">
        <h3 class="modal-title"><?=  __tr( 'Help : RTM' )  ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Modal Body -->
    <div class="modal-body">
		<p>
    		<b> <?=  __tr( 'RTM' )  ?></b>
    		<?=  __tr( 'report includes the traceability matrix requirements with the Test Cases, a single report, so that no functionality should miss while doing Software testing')?> <br>
    		<?=  __tr( 'You can select Test Cycle or Test Suite or both to Create RTM')?> <br>
    	</p>
    	<hr>
    	<p>
    		<h4><?=  __tr( 'Generate Excel File' )  ?></h4>
			<p><?=  __tr( 'To Generate Excel File, Click on Generate excel file button.')?></p>
    	</p>
    </div>
    <!-- /Modal Body -->
    
	<!-- Close Dialog button -->
	<div class="modal-footer">
		<button type="button" class="btn btn-default" ng-click="helpCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
	</div>
	<!-- /Close Dialog button-->
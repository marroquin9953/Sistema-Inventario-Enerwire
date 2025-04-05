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
        <h3 class="modal-title"><?=  __tr( 'Help : Test Execution' )  ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Modal Body -->
    <div class="modal-body">
		<p>
    		<b> <?=  __tr( 'Test Execution' )  ?></b>
    		<?=  __tr( 'facilitates, execution of test cases.' )  ?><br>
    	</p>
    	<hr>
    	<p>
    		<h4><?=  __tr( 'Execute Test Case' )  ?></h4>
			<p><?=  __tr( 'To execute test case, follow the steps below :')?></p>
			<ul type="circle">
				<li><?=  __tr( 'Select Test Cycle to execute' )  ?></li>
				<li><?=  __tr( 'Select Test Case from selected Test Cycle to execute' )  ?></li>
				<li><?=  __tr( 'Click on Start or Resume moderation button, then a dialog will appear' )  ?></li>
				<li><?=  __tr( 'Select result of Test Case' )  ?></li>
				<li><?=  __tr( 'Add actual result' )  ?></li>
				<li><?=  __tr( 'Add remarks' )  ?></li>
				<li><?=  __tr( 'Click on Moderate button to execute or moderate Test Case' )  ?> </li>
				<li><?=  __tr( 'If Test Case moderation result is failed then issue for this Test Case automatically generated but checkbox as Create issue for this Test Case should be checked' )  ?> </li>
			</ul>
    	</p>
    </div>
    <!-- /Modal Body -->
    
	<!-- Close Dialog button -->
	<div class="modal-footer">
		<button type="button" class="btn btn-default" ng-click="helpCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
	</div>
	<!-- /Close Dialog button-->
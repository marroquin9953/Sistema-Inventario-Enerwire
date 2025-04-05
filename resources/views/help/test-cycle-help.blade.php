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
        <h3 class="modal-title"><?=  __tr( 'Help : Test Cycles' )  ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Modal Body -->
    <div class="modal-body">
		<p>
    		<b> <?=  __tr( 'Test Cycles' )  ?></b>
    		<?=  __tr( 'facilitates, adding new Test Cycle, assigning roles, delete Test Cycle, edit Test Cycle, duplicate, and assign test cases to Test Cycle. ' )  ?>
    		<br>
    		<?=  __tr( 'Also, on clicking the Test Cycle title complete Test Cycle details can be obtained. And you will be able to manage complete Test Cycle related activities.' )  ?>
    	</p>
    	<hr>
    	<p>
    		<h4><?=  __tr( 'Add New Test Cycle' )  ?></h4>
			<p><?=  __tr( 'To add new Test Cycle, follow the steps below :')?></p>
			<ul type="circle">
				<li><?=  __tr( 'Click on + New Test Cycle button, then a dialog will appear' )  ?></li>
				<li><?=  __tr( 'Add title of role' )  ?></li>
				<li><?=  __tr( 'Add Description' )  ?></li>
				<li><?=  __tr( 'Now Click on Add button to add new Test Cycle' )  ?></li>
			</ul>
    	</p>
    	<hr>

    	<h4><?=  __tr( 'Edit or Delete a Test Cycle' )  ?></h4>

		<ul type="circle">
			<li><?=  __tr( 'In the action column edit and delete button options are available ' )  ?></li>
			<li><?=  __tr( 'Click the Edit button to Edit the details of the Test Cycle. Click Update or Cancel' )  ?></li>

			<li><?=  __tr( 'Click the Delete button to delete the Test Cycle permanently from the system' )  ?></li>

		</ul>

		<hr>
		
    	<p>
    		<h4><?=  __tr( 'Mark as Archive' )  ?></h4>
			<p><?=  __tr( 'Once the test cases belonging to test cycle are executed, then test cycle can be marked as archive. To make them as archive, Click on Mark as Archive button')?></p>
    	</p>
    	<hr>
    	<p>
    		<h4><?=  __tr( 'Duplicating Test Cycle' )  ?></h4>
			<p><?=  __tr( 'To duplicate test cycle, follow the steps below :')?></p>
			<ul type="circle">
				<li><?=  __tr( 'Click on Duplicate button, then a dialog will appear' )  ?></li>
				<li><?=  __tr( 'Edit title of test cycle' )  ?></li>
				<li><?=  __tr( 'Click on Duplicate button to duplicate test cycle' )  ?> </li>
			</ul>
    	</p>
		<hr>
		<p>
    		<h4><?=  __tr( 'Assigning test cases to test cycle' )  ?></h4>
			<p><?=  __tr( 'To assigning test cases to test cycle, Click on Assign Test Cases button.')?></p>
			<ul type="circle">
				<li><?=  __tr( 'Now, Click on + Assign Test Case button, then a dialog will appear' )  ?></li>
				<li><?=  __tr( 'Select the test cases you want to assign to the test cycle' )  ?></li>
				<li><?=  __tr( 'Click on Add button to assign test cases' )  ?> </li>
				<li><?=  __tr( 'Also assigned test cases can be deleted, by clicking Delete button' )  ?> </li>
			</ul>
    	</p>
    </div>
    <!-- /Modal Body -->
    
	<!-- Close Dialog button -->
	<div class="modal-footer">
		<button type="button" class="btn btn-default" ng-click="helpCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
	</div>
	<!-- /Close Dialog button-->
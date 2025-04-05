<?php
/*
*  Component  : Help
*  View       : Help
*  Engine     : MasterEngine
*  File       : service-request-help.blade.php  
*  Controller : ManageController
----------------------------------------------------------------------------- */ 
?>
	<!-- Modal Heading -->
    <div class="modal-header">
        <h3 class="modal-title"><?=  __tr( 'Help : Manage Test Suite' )  ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Modal Body -->
    <div class="modal-body">
    	<p><b> <?=  __tr( 'Test Suite Help' )  ?> </b> <?=  __tr( 'facilitates,  to manage test suites, by creating new test suite, helps in keep track of all project test suites, adding new test suites or updating existing test suites. ' )  ?><br>
    		<?=  __tr( 'Also, on clicking the Test Suite title complete Test Suite details can be obtained. And you will be able to manage complete test suite related activities.')?><br>
    	</p>
    	<hr> 

    	<h4><?=  __tr( 'Adding a Test Suite' )  ?></h4>
    	<p><?=  __tr( 'Follow the steps below to add a Test Suite :' )  ?></p>

    	<ul type="circle">
			<li><?=  __tr( 'Click on the +Test Suite button' )  ?></li>
			<li><?=  __tr( 'The Add New Test Suite dialog will appear' )  ?></li>
			<li><?=  __tr( 'Add the Title of the Test Suite' )  ?></li>
			<li><?=  __tr( 'Select the Requirements of the Test Suite' )  ?></li>
			<li><?=  __tr( 'Add a description for the Test Suite' )  ?></li>
		</ul>

		<hr>

		<h4><?=  __tr( 'Edit or Delete a Test Suite' )  ?></h4>

		<ul type="circle">
			<li><?=  __tr( 'In the action column edit and delete button options are available ' )  ?></li>
			<li><?=  __tr( 'Click the Edit button to Edit the details of the Test Suite. Click Update or Cancel' )  ?></li>

			<li><?=  __tr( 'Click the Delete button to delete the Test Suite permanently from the system' )  ?></li>

		</ul>

		</ul>
    </div>
    <!-- /Modal Body -->
    
	<!-- Close Dialog button -->
	<div class="modal-footer">
		<button type="button" class="btn btn-default" ng-click="helpCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
	</div>
	<!-- /Close Dialog button-->
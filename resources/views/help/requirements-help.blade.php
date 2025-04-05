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
        <h3 class="modal-title"><?=  __tr( 'Help : Manage Requirements' )  ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Modal Body -->
    <div class="modal-body">
    	<p><b> <?=  __tr( 'Manage Requirements' )  ?> </b> <?=  __tr( 'facilitates, helps to keep track of all the project requirements, adding new requirements or updating existing requirements.' )  ?>
    	<br>

    	<?=  __tr( 'Also, on clicking the Requirement title complete Requirement details can be obtained and you will be able to manage complete requirement related activities.' )  ?>
    	</p>
    	
    	<hr> 

    	<h4><?=  __tr( 'Adding a Requirement' )  ?></h4>
    	<p><?=  __tr( 'Follow the steps below to add a New Requirement :' )  ?></p>

    	<ul type="circle">
			<li><?=  __tr( 'Click on the +New Requirement button' )  ?></li>
			<li><?=  __tr( 'The Add New Requirement dialog will appear' )  ?></li>
			<li><?=  __tr( 'Add the Title of the Requirement' )  ?></li>
			<li><?=  __tr( 'Add a description for the Requirement' )  ?></li>
			<li><?=  __tr( 'Click on Attach new button to add a attachment for the Requirement' )  ?></li>
			<li><?=  __tr( 'Click on Attach Existing Uploaded button to select a existing attachment of the Requirement' )  ?></li>
		</ul>

		<hr>

		<h4><?=  __tr( 'Edit or Delete a Requirement' )  ?></h4>

		<ul type="circle">
			<li><?=  __tr( 'In the action column edit and delete button options are available ' )  ?></li>
			<li><?=  __tr( 'Click the Edit button to Edit the details of the Requirement. Click Update or Cancel' )  ?></li>

			<li><?=  __tr( 'Click the Delete button to delete the Requirement permanently from the system' )  ?></li>

		</ul>
    </div>
    <!-- /Modal Body -->
    
	<!-- Close Dialog button -->
	<div class="modal-footer">
		<button type="button" class="btn btn-default" ng-click="helpCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
	</div>
	<!-- /Close Dialog button-->
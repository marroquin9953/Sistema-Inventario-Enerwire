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
        <h3 class="modal-title"><?=  __tr( 'Help : Manage Issue' )  ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Modal Body -->
    <div class="modal-body">
		<p>
    		<b> <?=  __tr( 'Manage Issues' )  ?></b>
    		<?=  __tr( 'facilitates,  to manage issues, by creating new issue, helps to keep track of all the project issues, adding new issue or updating existing one as well as deleted issue. ' )  ?><br>
    		<?=  __tr( 'Also, on clicking the Issue title complete Issue details can be obtained. And you will be able to manage complete issue related activities.' )  ?>
    	</p>

    	<hr> 

    	<h4><?=  __tr( 'Adding a Issue' )  ?></h4>
    	<p><?=  __tr( 'Follow the steps below to add a New Issue :' )  ?></p>

    	<ul type="circle">
			<li><?=  __tr( 'Click on the +New Issue button' )  ?></li>
			<li><?=  __tr( 'The Add New Issue dialog will appear' )  ?></li>
			<li><?=  __tr( 'Add the Title of the Issue' )  ?></li>
			<li><?=  __tr( 'Select the severity of issue, ex. Minor, Major, Critical' )  ?></li>
			<li><?=  __tr( 'Select the priority of issue, ex. Normal, Low, High' )  ?></li>
			<li><?=  __tr( 'Add a description for the Issue' )  ?></li>
			<li><?=  __tr( 'Click on Attach new button to add a attachment of the Issue' )  ?></li>
			<li><?=  __tr( 'Click on Attach Existing Uploaded button to select a existing attachment of the Issue' )  ?></li>
		</ul>

		<hr>

		<h4><?=  __tr( 'Edit or Delete a Issue' )  ?></h4>

		<ul type="circle">
			<li><?=  __tr( 'In the action column edit and delete button options are available ' )  ?></li>
			<li><?=  __tr( 'Click the Edit button to Edit the details of the Issue. Click Update or Cancel' )  ?></li>

			<li><?=  __tr( 'Click the Delete button to delete the Issue permanently from the system' )  ?></li>

		</ul>

		<hr>

		<h4><?=  __tr( 'Generate Excel File' )  ?></h4>
		<ul type="circle">
			<li><?=  __tr( 'Click on the Generate Excel File button, to generate Issues excel file' )  ?></li>

		</ul>
    </div>
    <!-- /Modal Body -->
    
	<!-- Close Dialog button -->
	<div class="modal-footer">
		<button type="button" class="btn btn-default" ng-click="helpCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
	</div>
	<!-- /Close Dialog button-->
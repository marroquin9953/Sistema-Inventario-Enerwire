<?php
/*
*  Component  : Help
*  View       : Help
*  Engine     : MasterEngine
*  File       : projects-help.blade.php  
*  Controller : ManageController
----------------------------------------------------------------------------- */ 
?>
	<!-- Modal Heading -->
    <div class="modal-header">
        <h3 class="modal-title"><?=  __tr( 'Help : Manage Projects' )  ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Modal Body -->
    <div class="modal-body">
    	<p>
			<b><?=  __tr( 'Manage Projects' )  ?></b> <?=  __tr( 'facilitates, viewing all active and inactive as well as deleted projects, adding new projects or updating existing one.' )  ?>
			<br>	
			<?=  __tr( 'Also, on clicking the Project title complete Project details can be obtained. And you will be able to manage complete project related activities.' )  ?>
		</p>

		<hr> 

    	<h4><?=  __tr( 'Adding a Project' )  ?></h4>
    	<p><?=  __tr( 'Follow the steps below to add a New Project :' )  ?></p>

    	<ul type="circle">
			<li><?=  __tr( 'Click on the +New Project button' )  ?></li>
			<li><?=  __tr( 'The Add New Project dialog will appear' )  ?></li>
			<li><?=  __tr( 'Add the Title of the Project' )  ?></li>
			<li><?=  __tr( 'Select and Click the Start & End date input field to open the date and time dialog box, then select start and end date of project' )  ?></li>
			<li><?=  __tr( 'Add a Start date' )  ?></li>
			<li><?=  __tr( 'Add the End date. On this date your project will be expire' )  ?></li>
			<li><?=  __tr( 'Add a description for the Project' )  ?></li>
		</ul>

		<hr>

		<h4><?=  __tr( 'Edit or Delete a Project' )  ?></h4>

		<ul type="circle">
			<li><?=  __tr( 'In the action column edit and delete button options are available ' )  ?></li>
			<li><?=  __tr( 'Click the Edit button to Edit the details of the Project or to extend the expiry date or to inactive the Project. Click Update or Cancel' )  ?></li>

				<ul type="disc">
					<li><?=  __tr( 'In edit dialog select active or inacitve Project switchery to change the status of Project' )  ?></li>
					<li><?=  __tr( 'If Project inactive then, Project move to inactive tab, else active tab' )  ?></li>
				</ul>

			<li><?=  __tr( 'Click on Delete button to move the the Project in the delete tab' )  ?></li>

				<ul type="disc">
					<li><?=  __tr( 'From deleted tab you can select Project restore button, then Project will restore in active tab' )  ?></li>
					<li><?=  __tr( 'But, if we select delete button then, open confirmation dialog to delete the Project permanently from the system' )  ?></li>
					<li><?=  __tr( 'For permanent deleting the Project you must have enter the password and click on delete button to delete, else cancel' )  ?></li>
				</ul>

			</ul>
    </div>
    <!-- /Modal Body -->
    
	<!-- Close Dialog button -->
	<div class="modal-footer">
		<button type="button" class="btn btn-default" ng-click="helpCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
	</div>
	<!-- /Close Dialog button-->
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
        <h3 class="modal-title"><?=  __tr( 'Help : Manage Notes' )  ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Modal Body -->
    <div class="modal-body">
		<p>
    		<b> <?=  __tr( 'Manage Notes' )  ?></b>
    		<?=  __tr( 'facilitates,  to manage notes, by creating new note, helps to keep track of all the project notes, adding new notes or updating existing one as well as delete notes. '  )  ?><br>
    		<?=  __tr( 'Also, on clicking the Note title complete Notes details can be obtained. And you will be able to manage complete note related activities.')?> <br>
    	</p>

    	<hr> 

    	<h4><?=  __tr( 'Adding a Note' )  ?></h4>
    	<p><?=  __tr( 'Follow the steps below to add a New Note :' )  ?></p>

    	<ul type="circle">
			<li><?=  __tr( 'Click on the +New Note button' )  ?></li>
			<li><?=  __tr( 'The Add New Note dialog will appear' )  ?></li>
			<li><?=  __tr( 'Add the Title of the Note' )  ?></li>
			<li><?=  __tr( 'Add a description for the Note' )  ?></li>
		</ul>

		<hr>

		<h4><?=  __tr( 'Edit or Delete a Note' )  ?></h4>

		<ul type="circle">
			<li><?=  __tr( 'In the action column edit and delete button options are available ' )  ?></li>
			<li><?=  __tr( 'Click the Edit button to Edit the details of the Note. Click Update or Cancel' )  ?></li>

			<li><?=  __tr( 'Click the Delete button to delete the Note permanently from the system' )  ?></li>

		</ul>

		</ul>
    </div>
    <!-- /Modal Body -->
    
	<!-- Close Dialog button -->
	<div class="modal-footer">
		<button type="button" class="btn btn-default" ng-click="helpCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
	</div>
	<!-- /Close Dialog button-->
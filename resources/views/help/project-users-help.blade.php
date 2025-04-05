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
        <h3 class="modal-title"><?=  __tr( 'Help : Manage Project Users' )  ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Modal Body -->
    <div class="modal-body">
		<p>
    		<b> <?=  __tr( 'Manage Project Users' )  ?></b>
    		<?=  __tr( 'facilitates,  to manage project users, by adding existing users in the project, assigning  permissions or modifying existing permission and removing user from project. ' )  ?><br>
    	</p>
    	<hr>
    	<p>
    		<h4><?=  __tr( 'Adding users in the project' )  ?></h4>
			<p><?=  __tr( 'To add new user, follow the steps below :')?></p>
			<ul type="circle">
				<li><?=  __tr( 'Click on + New User button, then a dialog will appear' )  ?></li>
				<li><?=  __tr( 'Select user from the list' )  ?></li>
				<li><?=  __tr( 'Add role title of user' )  ?></li>
				<li><?=  __tr( 'Now Click on Add button to add user' )  ?></li>
			</ul>
    	</p>
		<hr>
    	<p>
    		<h4><?=  __tr( 'Assigning permissions or modifying existing permission' )  ?></h4>
			<p><?=  __tr( 'To assigning permissions or modifying existing permission, follow the steps below :')?></p>
			<ul type="circle">
				<li><?=  __tr( 'Click on Permission button, then a dialog will appear' )  ?></li>
				<li><?=  __tr( 'You can assign or modify permissions by selecting the permissions' )  ?></li>
				<li><?=  __tr( 'Now Click on Update button to update user permissions' )  ?></li>
			</ul>
    	</p>
    	<hr>
    	<p>
    		<h4><?=  __tr( 'Remove User' )  ?></h4>
			<p><?=  __tr( 'To remove user, follow the steps below :')?></p>
			<ul type="circle">
				<li><?=  __tr( 'Click on Remove button, then a dialog will appear' )  ?></li>
				<li><?=  __tr( 'Click on Yes, Remove It button to remove user from active tab' )  ?> </li>
				<li><?=  __tr( 'Click on Restore button to restore removed user again in active tab and click on delete button to delete user permanently from the project' )  ?> </li>
			</ul>
    	</p>
    </div>
    <!-- /Modal Body -->
    
	<!-- Close Dialog button -->
	<div class="modal-footer">
		<button type="button" class="btn btn-default" ng-click="helpCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
	</div>
	<!-- /Close Dialog button-->
<?php
/*
*  Component  : Help
*  View       : Help
*  Engine     : MasterEngine
*  File       : role-permissions-help.blade.php  
*  Controller : ManageController
----------------------------------------------------------------------------- */ 
?>
	<!-- Modal Heading -->
    <div class="modal-header" autofocus>
        <h3 class="modal-title"><?=  __tr( 'Help : Manage User Roles' )  ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Modal Body -->
    <div class="modal-body">
    	<p>
    		<b><?=  __tr( 'Manage User Roles' )  ?></b> <?=  __tr( 'facilitates, adding new role and assigning Permissions to role, delete and modify permissions.' )  ?>
    	</p>
    	<hr>
    	<p>
    		<h4><?=  __tr( 'Add New User Role' )  ?></h4>
			<p><?=  __tr( 'To add new user role, follow the steps below :')?></p>
			<ul type="circle">
				<li><?=  __tr( 'Click on + New User role button, then a dialog will appear' )  ?></li>
				<li><?=  __tr( 'Add title of role' )  ?></li>
				<li><?=  __tr( 'You can also import permissions from pre-added role' )  ?></li>
				<li><?=  __tr( 'Select permissions you want assign for a role' )  ?></li>
				<li><?=  __tr( 'Now Click on Submit button to add new role' )  ?></li>
			</ul>
    	</p>
		<hr>
		<p>
    		<h4><?=  __tr( 'Modify Permission for a specific role :' )  ?></h4>
			<p><?=  __tr( 'To modify permissions for a specific  user role, follow the steps below :')?></p>
			<ul type="circle">
				<li><?=  __tr( 'Click on Permission button, then a dialog will appear' )  ?></li>
				<li><?=  __tr( 'Select permissions you want assign or modify for a role' )  ?></li>
				<li><?=  __tr( 'Now Click on Update button to add role' )  ?></li>
			</ul>
    	</p>
		<hr>
		<p>
    		<h4><?=  __tr( 'Delete role' )  ?></h4>
			<p><?=  __tr( 'To delete role, follow the steps below :')?></p>
			<ul type="circle">
				<li><?=  __tr( 'Click on Delete button, then a dialog will appear' )  ?></li>
				<li><?=  __tr( 'Click on Yes, delete it to delete the role' )  ?></li>
 			</ul>
    	</p>
		<hr>
    </div>
    <!-- /Modal Body -->
    
	<!-- Close Dialog button -->
	<div class="modal-footer">
		<button type="button" class="btn btn-default" ng-click="helpCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
	</div>
	<!-- /Close Dialog button-->
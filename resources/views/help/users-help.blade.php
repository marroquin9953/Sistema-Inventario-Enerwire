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
        <h3 class="modal-title"><?=  __tr( 'Help : Manage Users' )  ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Modal Body -->
    <div class="modal-body">
		<p>
    		<b> <?=  __tr( 'Manage Users' )  ?></b>
    		<?=  __tr( 'facilitates,  to manage users, by creating new users, assigning roles and permissions to added user or modifying existing user roles and permission and changing password of each user. ' )  ?>
    	</p>
    	<hr>
    	<p>
    		<h4><?=  __tr( 'Add New User' )  ?></h4>
			<p><?=  __tr( 'To add new user, follow the steps below :')?></p>
			<ul type="circle">
				<li><?=  __tr( 'Click on + New User button, then a window will appear' )  ?></li>
				<li><?=  __tr( 'Add first name of user' )  ?></li>
				<li><?=  __tr( 'Add last name of user' )  ?></li>
				<li><?=  __tr( 'Select role of user' )  ?></li>
				<li><?=  __tr( 'Add username of user' )  ?></li>
				<li><?=  __tr( 'Add email of user (optional)' )  ?></li>
				<li><?=  __tr( 'Add password and confirm password' )  ?></li>
				<li><?=  __tr( 'Now Click on Add button to add user' )  ?></li>
			</ul>
    	</p>
		<hr>
		<h4><?=  __tr( 'Edit or Delete a User' )  ?></h4>

		<ul type="circle">
			<li><?=  __tr( 'In the action column edit and delete button options are available ' )  ?></li>
			<li><?=  __tr( 'Click the Edit button to Edit the details of the User or to inactive the User. Click Update or Cancel' )  ?></li>

				<ul type="disc">
						<li><?=  __tr( 'In edit dialog select active or inacitve User switchery to change the status of User' )  ?></li>
						<li><?=  __tr( 'If User inactive then, User move to inactive tab, else active tab' )  ?></li>
				</ul>
			<li><?=  __tr( 'Click on Delete button to move the the User in the delete tab' )  ?></li>
				<ul type="disc">
					<li><?=  __tr( 'From deleted tab you can select User restore button, then User will restore in active tab' )  ?></li>
				</ul>	
		</ul>

		<hr>

    	<p>
    		<h4><?=  __tr( 'Permissions' )  ?></h4>
			<?=  __tr( 'To change user permissions, follow the steps below :')?>
			<ul type="circle">
				<li><?=  __tr( 'Click on Permission button, then a window will appear' )  ?></li>
				<li><?=  __tr( 'You can give module wise permissions to user' )  ?></li>
				<li><?=  __tr( 'Now Click on Update button to update permissions' )  ?></li>
			</ul>
    	</p>
    	<hr>
    	<p>
    		<h4><?=  __tr( 'Change password' )  ?></h4>
			<?=  __tr( 'To change password of user, follow the steps below :')?>
			<ul type="circle">
				<li><?=  __tr( 'Click on Change password button, then a window will appear' )  ?></li>
				<li><?=  __tr( 'Type new password and new password confirmation' )  ?></li>
				<li><?=  __tr( 'Now Click on Update password button to update user password' )  ?></li>
			</ul>
    	</p>
    	
    </div>
    <!-- /Modal Body -->
    
	<!-- Close Dialog button -->
	<div class="modal-footer">
		<button type="button" class="btn btn-default" ng-click="helpCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
	</div>
	<!-- /Close Dialog button-->
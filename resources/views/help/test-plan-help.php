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
        <h3 class="modal-title"><?=  __tr('Help : Manage Test Plan')  ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Modal Body -->
    <div class="modal-body">
    	<p><b> <?=  __tr('Manage Test Plan')  ?> </b> <?=  __tr('facilitates, helps to keep track of all the project test plans, adding new test plans or updating existing test plans.')  ?>
    	<br>
    		<?=  __tr('Also, on clicking the Test Plan title complete Test Plan details can be obtained. And you will be able to manage complete test plan related activities.')?> <br>
    	</p>
    	<hr> 

    	<h4><?=  __tr('Adding a Test Plan')  ?></h4>
    	<p><?=  __tr('Follow the steps below to add a Test Plan :')  ?></p>

    	<ul type="circle">
			<li><?=  __tr('Click on the +New Test Plan button')  ?></li>
			<li><?=  __tr('The Add New Test Plan dialog will appear')  ?></li>
			<li><?=  __tr('Add Title of the Test Plan')  ?></li>
			<li><?=  __tr('Select and add the parameter of Test Plan.')  ?></li>
			<li><?=  __tr('Add a description for the Test Plan')  ?></li>
			<li><?=  __tr('If you want to add a new parameter, then click +New Parameter button to add new parameter and description. ')  ?></li>
		</ul>

		<hr>

		<h4><?=  __tr('Edit or Delete a Test Plan')  ?></h4>

		<ul type="circle">
			<li><?=  __tr('In the action column edit and delete button options are available ')  ?></li>
			<li><?=  __tr('Click the Edit button to Edit the details of the Test Plan. Click Update or Cancel')  ?></li>

			<li><?=  __tr('Click the Delete button to delete the Test Plan permanently from the system')  ?></li>

		</ul>

		</ul>
    </div>
    <!-- /Modal Body -->
    
	<!-- Close Dialog button -->
	<div class="modal-footer">
		<button type="button" class="btn btn-default" ng-click="helpCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
	</div>
	<!-- /Close Dialog button-->
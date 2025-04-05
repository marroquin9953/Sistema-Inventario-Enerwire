<?php
/*
*  Component  : Help
*  View       : Help
*  Engine     : MasterEngine
*  File       : projects-testcase-help.blade.php  
*  Controller : ManageController
----------------------------------------------------------------------------- */ 
?>
<div>
<!-- Modal Heading -->
    <div class="modal-header">
        <h3 class="modal-title"><?=  __tr( 'Help : Test Cases' )  ?></h3>
    </div>
<!-- /Modal Heading -->
	<!-- Modal Body -->
    <div class="modal-body">
    	<p>
    		<b><?=  __tr( 'Test Cases' )  ?></b> <?=  __tr( 'facilitates, adding new test case, deleting existing test case, duplicate existing test case and generate an excel sheet of all test cases. Also, on clicking the test case title complete test case details can be obtained.' )  ?>
    	</p>

    	<hr>
    	<p>
    		<h4><?=  __tr( 'Add New Test Case' )  ?></h4>
			<p><?=  __tr( 'To add new test case, follow the steps below :')?></p>
			<ul type="circle">
				<li><?=  __tr( 'Click on + New Test Case button, then a dialog will appear' )  ?></li>
				<li><?=  __tr( 'Add Expected Result' )  ?></li>
 				<li><?=  __tr( 'Select Test Suite' )  ?></li>
 				<li><?=  __tr( 'Select Requiremets' )  ?></li>
				<li><?=  __tr( 'Now Click on Add button to add new test case' )  ?></li>
			</ul>
    	</p>
    	<hr>

    	<h4><?=  __tr( 'Edit or Delete a Test Case' )  ?></h4>

		<ul type="circle">
			<li><?=  __tr( 'In the action column edit and delete button options are available ' )  ?></li>
			<li><?=  __tr( 'Click the Edit button to Edit the details of the Test Case. Click Update or Cancel' )  ?></li>

			<li><?=  __tr( 'Click the Delete button to delete the Test Case permanently from the system' )  ?></li>

		</ul>

		<hr>
		
    	<p>
    		<h4><?=  __tr( 'Manage Test Case' )  ?></h4>
			<p><?=  __tr( 'To manage test case, follow the steps below :')?></p>
			<ul type="circle">
				<li><?=  __tr( 'Add new Test Data, follow the steps below :')?></li>

				<ul type="disc">
					<li><?=  __tr( 'Click on Manage button, then a new page will appear related to Test Steps' )  ?></li>
					<li><?=  __tr( 'Click on Test Data button, then a dialog will appear' )  ?> </li>
					<li><?=  __tr( 'Add name of Test Data' )  ?></li>
					<li><?=  __tr( 'Select type of Test Data' )  ?></li>
					<li><?=  __tr( 'Select validations of Test Data' )  ?></li>
					<li><?=  __tr( 'Add input value of Test Data' )  ?></li>
					<li><?=  __tr( 'Add description of Test Data' )  ?></li>
					<li><?=  __tr( 'Click on Add More button, then your can multiple Test Data or else Click on remove button to remove Test Data' )  ?> </li>
					<li><?=  __tr( 'Now Click on Add button to add new test Data' )  ?></li>

				</ul>
				<br>
				<li><?=  __tr( 'Add new Test Step or Additional Test Item, follow the steps below :')?></li>

					<ul type="disc">
						<li><?=  __tr( 'Click on Add New button, then you can select Steps or Additional Test Item' )  ?></li>
						<li><?=  __tr( 'If you can select Steps then Add New Step panel will open.' )  ?> </li>
						<li><?=  __tr( 'Add description of Step' )  ?></li>
						<li><?=  __tr( 'Now Click on Add button to add new Step' )  ?></li>
						<li><?=  __tr( 'If you can select Additional Test Item then Add New Additional Step Item panel will open' )  ?> </li>
						<li><?=  __tr( 'Select type of Additional Test Item' )  ?> </li>
						<li><?=  __tr( 'Add description of Additional Test Item' )  ?></li>
						<li><?=  __tr( 'Now Click on Add button to add new Additional Test Item' )  ?></li>
					</ul>
			</ul>
    	</p>

		<hr>

    	<p>
    		<h4><?=  __tr( 'Duplicating Test Case' )  ?></h4>
			<p><?=  __tr( 'To duplicate test case, follow the steps below :')?></p>
			<ul type="circle">
				<li><?=  __tr( 'Click on Duplicate button, then a dialog will appear' )  ?></li>
				<li><?=  __tr( 'Add title of test case' )  ?></li>
				<li><?=  __tr( 'Click on Duplicate button to duplicate test case' )  ?> </li>
			</ul>
    	</p>
		<hr>
		<p>
    		<h4><?=  __tr( 'Generate Excel File' )  ?></h4>
			<p><?=  __tr( 'To Generate Excel File of Test cases, Click on Generate excel file button.')?></p>
    	</p>
    </div>
    <!-- /Modal Body -->
    
	<!-- Close Dialog button -->
	<div class="modal-footer">
		<button type="button" class="btn btn-default" ng-click="helpCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
	</div>
	<!-- /Close Dialog button-->
	
</div>
<?php 
/*
*  Component  : Activity
*  View       : ActivityLog Controller
*  Engine     : ActivityEngine  
*  File       : activity-log.blade.php  
*  Controller : ActivityLogListController
----------------------------------------------------------------------------- */
?>
<div>
    <div class="lw-section-heading-block">
      	<!-- main heading -->
        <h3 class="lw-section-heading">
            <div class="lw-heading">
                <?= __tr('Manage Activity log') ?>
            </div>
        </h3>
    </div>
   	<!-- /main heading -->

   	<form class="ui form lw-form lw-ng-form" name="activityLogListCtrl.[[activityLogListCtrl.ngFormName]]" novalidate >
		
		<div class="form-row">
			<!-- Duration -->
            <div class="col">
    			<lw-form-field field-for="duration" label="<?= __tr('Duration') ?>">
    	            <select class="form-control" 
                        name="duration" ng-model="activityLogListCtrl.duration" ng-options="role as key for (role, key) in activityLogListCtrl.durations" ng-required="true" ng-change="activityLogListCtrl.activityDataTable(activityLogListCtrl.duration)">
                        <option value='' disabled selected><?=  __tr('-- Select Duration --')  ?></option>
                    </select> 
    			</lw-form-field>
            </div>
			<!-- /Duration-->
			
			<!-- startDate -->
            <div class="col">
    			<lw-form-field field-for="startDate" label="Start Date">
    	            <input 
    					type="text" 
    					class="lw-form-field form-control" 
    					lw-bootstrap-md-datetimepicker 
    					id="startDate"	
    					name="startDate"
    					ng-change="activityLogListCtrl.changeDate(activityLogListCtrl.startDate)"
    					ng-model="activityLogListCtrl.startDate"
    					data-dtp="dtp_G0UIR"
    					options="[[ activityLogListCtrl.startDateConfig ]]">
    			</lw-form-field>
            </div>
			<!-- /startDate-->

			<!-- endDate -->
            <div class="col">
    			<lw-form-field field-for="endDate" label="End Date">
    	            <input 
    					type="text" 
    					class="lw-form-field form-control" 
    					name="endDate" 
    					id="endDate"
    					ng-change="activityLogListCtrl.changeDate(activityLogListCtrl.endDate)"
    					lw-bootstrap-md-datetimepicker 
    					ng-model="activityLogListCtrl.endDate"
    					data-dtp="dtp_wUc37"
    					options="[[ activityLogListCtrl.startDateConfig ]]">
    			</lw-form-field>
            </div>
			<!-- /endDate-->


            <div >
                <!-- Show button -->
                <button type="submit" style="margin-top: 25px;" class="btn btn-primary" ng-click="activityLogListCtrl.dateChange(activityLogListCtrl.selectedProject)" title="<?= __tr('Show') ?>"><?= __tr('Show') ?> <span></span></button>
                <!-- / Show button -->
            </div>

		</div>

	</form>

   	<div class="tab-content lw-tab-content">

		<!--  Active Users Tab  -->
        <div role="tabpanel" class="tab-pane fade in show active">

		   	<table class="table table-striped table-bordered" id="lwActivityLogList" cellspacing="0" width="100%">
		      	<thead>
		         	<tr>
						<th><?= __tr('Date') ?></th>
						<th><?= __tr('Action By') ?></th>
						<th><?= __tr('Entity Type') ?></th>
						<th><?= __tr('IP Address') ?></th>
                        <th><?= __tr('Activity') ?></th>
                        <th><?= __tr('Description') ?></th>
		         	</tr>
		      	</thead>
		      	<tbody></tbody>
		   	</table>

		</div>
	</div>
</div>

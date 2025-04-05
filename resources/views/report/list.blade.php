<?php 
/*
*  Component  : Report
*  View       : Report Controller
*  Engine     : ReportEngine  
*  File       : report.list.blade.php  
*  Controller : ReportListController 
----------------------------------------------------------------------------- */
?>

<div>
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <span>
                <?= __tr('Reports') ?>
            </span>
        </h3>
    </div>
    <!-- /main heading -->

    <!-- form section -->
    <form class="lw-form lw-ng-form lw-ng-form" name="reportListCtrl.[[ reportListCtrl.ngFormName ]]" novalidate>

        <div class="form-row">

            <div class="col-md-4">
                <!-- duration -->
                <lw-form-field field-for="duration" label="<?= __tr( 'Duration' ) ?>" advance="true">
                    <select class="lw-form-field form-control" name="duration" ng-model="reportListCtrl.duration"
                        ng-options="role as key for (role, key) in reportListCtrl.durations" ng-required="true"
                        ng-change="reportListCtrl.durationChange(reportListCtrl.duration)">
                    </select>
                </lw-form-field>
                <!-- /duration -->
            </div>

            <div class="col-md-4">
                <!-- Start Date -->
                <lw-form-field field-for="start" label="<?= __tr( 'Start Date' ) ?>">
                    <input type="text" class="lw-form-field form-control lw-readonly-control" name="start" id="start"
                        lw-bootstrap-md-datetimepicker ng-required="true"
                        ng-change="reportListCtrl.endDateUpdated(reportListCtrl.reportData.start)"
                        options="[[ reportListCtrl.startDateConfig ]]" readonly
                        ng-model="reportListCtrl.reportData.start" />
                </lw-form-field>
                <!-- /Start Date -->
            </div>

            <div class="col-md-4">
                <!-- end Date -->
                <lw-form-field field-for="end" label="<?= __tr( 'End Date' ) ?>">
                    <input type="text" class="lw-form-field form-control lw-readonly-control" name="end" id="end"
                        lw-bootstrap-md-datetimepicker
                        ng-change="reportListCtrl.endDateUpdated(reportListCtrl.reportData.end)"
                        options="[[ reportListCtrl.endDateConfig ]]" ng-required="true" readonly
                        ng-model="reportListCtrl.reportData.end" />
                </lw-form-field>
                <!-- /end Date -->
            </div>

            <!-- Type -->
            <div class="col-md-4">

                <lw-form-field field-for="order" label="<?= __tr( 'Type' ) ?>">
                    <select class="lw-form-field form-control" name="order"
                        ng-model="reportListCtrl.reportData.stock_subtype"
                        ng-options="role as key for (role, key) in reportListCtrl.stock_subtype" ng-required="true">
                    </select>
                </lw-form-field>

            </div>
            <!-- /Type-->

            <div class="col-md-4">
                <!-- Locations  -->
                <lw-form-selectize-field field-for="locations" label="<?= __tr( 'Locations' ) ?>" class="lw-selectize">
                    <selectize config='reportListCtrl.locationsSelectConfig' class=" form-control lw-form-field"
                        name="locations" ng-model="reportListCtrl.reportData.locations"
                        options='reportListCtrl.locations' placeholder="<?= __tr( 'Select Locations' ) ?>">
                    </selectize>
                </lw-form-selectize-field>
                <!-- /Locations -->
            </div>

            <!-- show button for show order-->
            <div class="col-md-4">

                <button type="submit" ng-click="reportListCtrl.getReports()"
                    class="btn btn-primary lw-btn lw-form-row-btn" title="<?= __tr('Show') ?>">
                    <?= __tr('Show') ?>
                </button>

            </div>
            <!-- /show button for show order-->

        </div>

    </form>
    <!-- / form section -->

    <div class="card mb-2">
        <div class="card-body">
            <div>
                <?= __tr('Showing __type__ Report for', [
                '__type__' => '[[ reportListCtrl.stock_subtype[reportListCtrl.reportData.stock_subtype] ]]'
            ]) ?>
                <span>duration : </span>
                <strong>
                    <span ng-bind="reportListCtrl.reportData.start"></span> -
                    <span ng-bind="reportListCtrl.reportData.end"></span>
                </strong>
            </div>
        </div>
    </div>

    <div>

        <input type="hidden" id="lwReportInput" data-filename="<?= __tr( " __filename__ Report") ?>"
        data-top-message="
        <?= __tr( 'Duration : __start__ - __end__') ?>"
        data-success-text="
        <?= __tr( 'Deleted!') ?>">

        <table class="table table-striped table-bordered" id="lwreportList" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>
                        <?= __tr('SKU') ?>
                    </th>
                    <th>
                        <?= __tr('Product Combinations') ?>
                    </th>
                    <th>
                        <?= __tr('Location') ?>
                    </th>
                    <th>
                        <?= __tr('Created At') ?>
                    </th>
                    <th>
                        <?= __tr('Qty') ?>
                    </th>
                    <th>
                        <?= __tr('Price') ?>
                    </th>
                    <th>
                        <?= __tr('Product Total') ?>
                    </th>
                    <th>
                        <?= __tr('Tax') ?>
                    </th>
                    <th>
                        <?= __tr('Amount') ?>
                    </th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th width="20%" class="text-right"></th>
                </tr>
            </tfoot>
        </table>
        <div ui-view></div>
    </div>

</div>


<script type="text/_template" id="taxAmountTemplate">

    <table class="table table-borderless table-sm">
		<tbody>
			<% _.map(__tData.formatted_tax_title, function(tax) { %>
				<tr class="bg-white">
					<td>
						<span><%- tax.tax_title %></span>
					</td>
					<td class="text-right"><%- tax.amount %></td>
				</tr>
			<% }); %>
		</tbody>
	</table>
</script>
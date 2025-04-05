<?php 
/*
*  Component  : Billing
*  View       : Billing Controller
*  Engine     : BillingEngine  
*  File       : billing.list.blade.php  
*  Controller : BillingListController 
----------------------------------------------------------------------------- */
?>
<div>
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <span>
                <?= __tr('Manage Billings') ?>
            </span>
        </h3>

        <!-- button -->
        <div class="lw-section-right-content float-right">
            <a title="<?= __tr('Add New Bill') ?>" ng-show="canAccess('manage.billing.write.store_bill')" class="lw-btn btn btn-sm btn-primary pull-right" ui-sref="billing_add">
            <i class="fa fa-plus"></i> <?= __tr('Add New Bill') ?> </a>
        </div>
        <!--/ button -->
    </div>
    <!-- /main heading -->

    <table class="table table-striped table-bordered" id="lwbillingList" class="ui celled table" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th><?= __tr('Bill No') ?></th>
                <th><?= __tr('Txn') ?></th>
                <th><?= __tr('Customer Name') ?></th>
                <th><?= __tr('Total Amount') ?></th>
                <th><?= __tr('Status') ?></th>
                <th><?= __tr('Bill Date') ?></th>
                <th><?= __tr('Action') ?></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div ui-view></div>  
</div> 

<!-- action template -->
<script type="text/_template" id="billingActionColumnTemplate">

	<% if(__tData.view_details)  { %>
		<button type="button" class="btn btn-default btn-xs" ui-sref="billing_details({ 'billUid' : '<%- __tData._uid %>' })"><i class="fa fa-eye"></i> <?= __tr('Preview') ?></button>
	<% } %>
	<% if(__tData.status == 1)  { %>
		<% if(__tData.edit_bill)  { %>
    		<button type="button" class="btn btn-default btn-xs" ui-sref="billing_edit({ 'billUid' : '<%- __tData._uid %>' })"><i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></button>
    	<% } %>

    	<% if(__tData.can_delete) { %>
	      <button type="button" class="btn btn-danger btn-xs" ng-click="billingListCtrl.delete('<%- __tData._uid %>', '<%- _.escape(__tData.bill_number) %>')"><i class="fa fa-trash-o"></i> <?= __tr('Delete') ?></button>
	    <% } %>
    
    <% } %>
</script>
<!-- /action template -->


<script type="text/_template" id="billingTitleColumnTemplate">
	<% if(__tData.view_details)  { %>
		<a ui-sref="billing_details({ 'billUid' : '<%- __tData._uid %>' })"> <%- __tData.bill_number %> </a>
    <% } else { %>
    	<%- __tData.bill_number %>
    <% } %>
</script>


<!-- Delete bill button -->
<input type="hidden" id="billDeleteConfirm" 
    data-message="<?= __tr( 'You want to delete Bill No <strong> __billno__ </strong>.') ?>" 
    data-delete-button-text="<?= __tr('Yes, delete it') ?>" 
    data-success-text="<?= __tr( 'Deleted!') ?>">
<!-- Delete bill button -->
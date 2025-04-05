<?php 
/*
*  Component  : Tax
*  View       : Tax Controller
*  Engine     : TaxEngine  
*  File       : Tax.list.blade.php  
*  Controller : TaxListController 
----------------------------------------------------------------------------- */
?>
<div>
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">

            <div class="lw-heading">
                <a ui-sref="tax_preset" title="<?= __tr('Manage Tax Presets') ?>">
                    <?= __tr('Manage Tax Presets') ?>
                </a> &raquo;
                <?= __tr('Tax') ?>
            </div>
        </h3>


        <!-- button -->
        <div class="lw-section-right-content float-right">
            <a ng-if="canAccess('manage.tax.write.create')" href title="<?= __tr('Add New Tax') ?>"
                class="lw-btn btn btn-sm btn-primary pull-right" ng-click="taxListCtrl.openAddDialog()">
                <i class="fa fa-plus"></i>
                <?= __tr('Add New Tax') ?>
            </a>
        </div>
        <!--/ button -->

    </div>
    <!-- /main heading -->

    <table class="table table-striped table-bordered" id="lwTaxList" class="ui celled table" cellspacing="0"
        width="100%">
        <thead>
            <tr>
                <th>
                    <?= __tr('Title') ?>
                </th>
                <th>
                    <?= __tr('Type') ?>
                </th>
                <th>
                    <?= __tr('Status') ?>
                </th>
                <th>
                    <?= __tr('Amount/Rate') ?>
                </th>
                <th>
                    <?= __tr('Action') ?>
                </th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div ui-view></div>


</div>

<!-- action template -->
<script type="text/_template" id="taxActionColumnTemplate">
    <% if(__tData.can_edit) { %>
        <button title="<?= __tr('Edit Tax') ?>" class="lw-btn btn btn-sm btn-default" ng-click="taxListCtrl.openEditDialog('<%- __tData._uid %>')">
        <i class="fa fa-pencil-square-o"></i> Edit</button>
	<% } %>
 	<% if(__tData.can_delete) { %>
     	<button class="btn btn-danger btn-xs" title="<?= __tr('Delete') ?>" href="" ng-click="taxListCtrl.delete('<%- __tData._uid %>')"><i class="fa fa-trash-o fa-lg"></i> <?= __tr('Delete') ?></button>
	<% } %>

</script>
<!-- /action template -->

<!-- Delete customer button -->
<input type="hidden" id="taxpresetDeleteConfirm"
    data-message="<?= __tr( 'You want to delete <strong> __name__ </strong> tax.') ?>"
    data-delete-button-text="<?= __tr('Yes, delete it') ?>" data-success-text="<?= __tr( 'Deleted!') ?>">
<!-- Delete customer button -->
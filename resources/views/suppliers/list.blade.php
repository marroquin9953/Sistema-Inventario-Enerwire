<?php 
/*
*  Component  : Suppliers
*  View       : Suppliers Controller
*  Engine     : SuppliersEngine  
*  File       : suppliers.list.blade.php  
*  Controller : SuppliersListController 
----------------------------------------------------------------------------- */
?>
<style>
    div#lwsuppliersList_wrapper {
        margin-top: 3.5rem;
    }
</style>
<div>
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <div class="lw-heading">
                <?= __tr('Manage Suppliers') ?>
            </div>
        </h3>

        <!-- button -->
        <div class="lw-section-right-content">
            <a href title="<?= __tr('Add New Supplier') ?>" ng-show="canAccess('manage.suppliers.write.create')"
                class="lw-btn btn btn-sm btn-primary float-right" ng-click="suppliersListCtrl.openAddDialog()">
                <i class="fa fa-plus"></i>
                <?= __tr('Add New Supplier') ?>
            </a>
        </div>
        <!--/ button -->

    </div>
    <!-- /main heading -->

    <table class="table table-striped table-bordered" id="lwsuppliersList" class="ui celled table" cellspacing="0"
        width="100%">
        <thead>
            <tr>
                <th>
                    <?= __tr('Name') ?>
                </th>
                <th width="20%">
                    <?= __tr('Description') ?>
                </th>
                <th>
                    <?= __tr('Action') ?>
                </th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div ui-view></div>

    <!-- Delete customer button -->
    <input type="hidden" id="suppliersDeleteConfirm"
        data-message="<?= __tr( 'You want to delete <strong> __name__ </strong> supplier, all the related inventories will be unlinked for this supplier.') ?>"
        data-delete-button-text="<?= __tr('Yes, delete it') ?>" data-success-text="<?= __tr( 'Deleted!') ?>">
    <!-- Delete customer button -->
</div>

<!-- action template -->
<script type="text/_template" id="suppliersActionColumnTemplate">
    <% if(__tData.can_edit) { %>
          <button type="button" class="btn btn-default btn-xs" ng-click="suppliersListCtrl.openEditDialog('<%- __tData._uid %>')"><i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></button>
        <% } %>

        <% if(__tData.can_delete) { %>
          <button type="button" class="btn btn-danger btn-xs" ng-click="suppliersListCtrl.delete('<%- __tData._uid %>', '<%- _.escape(__tData.name) %>')"><i class="fa fa-trash-o"></i> <?= __tr('Delete') ?></button>
        <% } %>
    </script>
<!-- /action template -->
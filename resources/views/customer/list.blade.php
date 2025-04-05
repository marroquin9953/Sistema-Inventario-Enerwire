<?php 
/*
*  Component  : Customer
*  View       : Customer Controller
*  Engine     : CustomerEngine  
*  File       : customer.list.blade.php  
*  Controller : CustomerListController 
----------------------------------------------------------------------------- */
?>
<style>
    div#lwcustomerList_wrapper {
        margin-top: 3.5rem;
    }
</style>
<div>
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <div class="lw-heading">
                <?= __tr('Manage Customers') ?>
            </div>
        </h3>
        
        <!-- button -->
        <div class="lw-section-right-content  float-right">
            <a href title="<?= __tr('Add New Customer') ?>" ng-show="canAccess('manage.customer.write.create')" class="lw-btn btn btn-sm btn-primary pull-right" ng-click="customerListCtrl.openAddDialog()">
            <i class="fa fa-plus"></i> <?= __tr('Add New Customer') ?> </a>
        </div>
        <!--/ button -->
  
    </div>
    <!-- /main heading -->

    <table class="table table-striped table-bordered" id="lwcustomerList" class="ui celled table" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th><?= __tr('Name') ?></th>
                <th><?= __tr('Description') ?></th>
                <th><?= __tr('Action') ?></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div ui-view></div>
    
    <!-- Delete customer button -->
    <input type="hidden" id="customerDeleteConfirm" 
        data-message="<?= __tr( 'You want to delete <strong> __name__ </strong> customer') ?>" 
        data-delete-button-text="<?= __tr('Yes, delete it') ?>" 
        data-success-text="<?= __tr( 'Deleted!') ?>">
    <!-- Delete customer button -->
</div> 

    <!-- action template -->
    <script type="text/_template" id="customerActionColumnTemplate">
        <% if(__tData.can_edit) { %>
          <button type="button" class="btn btn-default btn-xs" ng-click="customerListCtrl.openEditDialog('<%- __tData._uid %>')"><i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></button>
        <% } %>

        <% if(__tData.can_delete) { %>
          <button type="button" class="btn btn-danger btn-xs" ng-click="customerListCtrl.delete('<%- __tData._uid %>', '<%- _.escape(__tData.name) %>')"><i class="fa fa-trash-o"></i> <?= __tr('Delete') ?></button>
        <% } %>
    </script>
    <!-- /action template -->
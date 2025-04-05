<?php 
/*
*  Component  : Location
*  View       : Location Controller
*  Engine     : LocationEngine  
*  File       : location.list.blade.php  
*  Controller : LocationListController 
----------------------------------------------------------------------------- */
?>
<style>
    div#lwlocationList_wrapper {
        margin-top: 3.5rem;
    }
</style>
<div>
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <div class="lw-heading">
                <?= __tr('Manage Locations / Warehouses') ?>
            </div>
        </h3>
        <!-- button -->
        <div class="lw-section-right-content float-right">
            <a href title="<?= __tr('Add New Location / Warehouse') ?>"
                ng-show="canAccess('manage.location.write.create')" class="lw-btn btn btn-sm btn-primary"
                ng-click="locationListCtrl.openAddDialog()">
                <i class="fa fa-plus"></i>
                <?= __tr('Add New Location / Warehouse') ?>
            </a>
        </div>
        <!--/ button -->

    </div>
    <!-- /main heading -->

    <table class="table table-striped table-bordered" id="lwlocationList" class="ui celled table" cellspacing="0"
        width="100%">
        <thead>
            <tr>
                <th>
                    <?= __tr('Name') ?>
                </th>
                <th>
                    <?= __tr('Location Id') ?>
                </th>
                <th>
                    <?= __tr('Status') ?>
                </th>
                <th width="20%">
                    <?= __tr('Short Description') ?>
                </th>
                <th>
                    <?= __tr('Action') ?>
                </th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div ui-view></div>
    <!-- Delete location button -->
    <input type="hidden" id="locationDeleteConfirm"
        data-message="<?= __tr( 'You want to delete <strong> __name__ </strong> location.') ?>"
        data-delete-button-text="<?= __tr('Yes, delete it') ?>" data-success-text="<?= __tr( 'Deleted!') ?>">
    <!-- Delete location button -->

</div>

<!-- action template -->
<script type="text/_template" id="locationActionColumnTemplate">
    <% if(__tData.can_edit) { %>
      <button type="button" class="btn btn-default btn-xs" ng-click="locationListCtrl.openEditDialog('<%- __tData._uid %>')"><i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></button>
    <% } %>

    <% if(__tData.can_delete) { %>
      <button type="button" class="btn btn-danger btn-xs" ng-click="locationListCtrl.delete('<%- __tData._uid %>', '<%- _.escape(__tData.name) %>')"><i class="fa fa-trash-o"></i> <?= __tr('Delete') ?></button>
    <% } %>

    <% if(__tData.can_assign) { %>
      <button type="button" class="btn btn-default btn-xs" ng-click="locationListCtrl.assignUser('<%- __tData._id %>', '<%- _.escape(__tData.name) %>')"><i class="fa fa-plus"></i> <?= __tr('Assign User') ?></button>
    <% } %>
</script>
<!-- /action template -->
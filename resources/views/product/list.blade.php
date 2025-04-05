<?php 
/*
*  Component  : Product
*  View       : Product Controller
*  Engine     : ProductEngine  
*  File       : product.list.blade.php  
*  Controller : ProductListController 
----------------------------------------------------------------------------- */
?>
<div>
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <div class="lw-heading">
                <?= __tr('Manage Products') ?>
            </div>
        </h3>
        
        <!-- button --> 
        <div class="lw-section-right-content float-right">
            <a ng-if="canAccess('manage.product.write.create')" title="<?= __tr('Add New Product') ?>" class="lw-btn btn btn-sm btn-primary" ui-sref="product_add">
            <i class="fa fa-plus"></i> <?= __tr('Add New Product') ?> </a>
        </div>
        <!--/ button -->
   
    </div>
    <!-- /main heading -->

    <table class="table table-striped table-bordered" id="lwproductList" class="ui celled table" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th><?= __tr('Name') ?></th>
                <th width="20%"><?= __tr('Short Description') ?></th>
                <th><?= __tr('Category') ?></th>
                <th><?= __tr('Status') ?></th>
                <th><?= __tr('Action') ?></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div ui-view></div>
    
    <!-- Delete Product button -->
    <input type="hidden" id="productDeleteConfirm" 
        data-message="<?= __tr( 'You want to delete <strong> __name__ </strong> product.') ?>" 
        data-delete-button-text="<?= __tr('Yes, delete it') ?>" 
        data-success-text="<?= __tr( 'Deleted!') ?>">
    <!-- Delete Product button -->
    
       <!-- action template -->
    <script type="text/_template" id="productNameColumnTemplate">
        <a href  ng-click="productListCtrl.openDetailsDialog('<%- __tData._uid %>')"> <%= _.escape(__tData.name) %></a>
    </script>



    <!-- action template -->
    <script type="text/_template" id="productActionColumnTemplate">

    <% if(__tData.can_edit) { %>
        <button type="button" class="btn btn-default btn-xs" ui-sref="product_edit({ 'productIdOrUid' : '<%- __tData._uid %>' })"><i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></button>
    <% } %>

    <% if(__tData.can_delete) { %>
        <button type="button" class="btn btn-danger btn-xs" ng-click="productListCtrl.delete('<%- __tData._uid %>', '<%- _.escape(__tData.name) %>')"><i class="fa fa-trash-o"></i> <?= __tr('Delete') ?></button>
    <% } %>
    </script>
    <!-- /action template -->
</div> 
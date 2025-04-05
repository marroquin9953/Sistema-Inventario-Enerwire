<?php 
/*
*  Component  : Category
*  View       : CategoryListController
*  Engine     : CategoryEngine  
*  File       : list.blade.php  
*  Controller : CategoryListController as CategoryListCtrl
----------------------------------------------------------------------------- */
?>
<div>
    <div class="lw-section-heading-block">

        <!--  main heading  -->
        <h3 class="lw-section-heading">
            <div class="lw-heading"><?= __tr( 'Manage Categories' ) ?></div>
        </h3>
        <!--  /main heading  -->

        <!-- heading and add button -->
        <div class="lw-section-right-content" ng-if="canAccess('manage.category.write.create')">
            <div class="offset-lg-6">  
                <!-- Category Add Form -->
                <form class="lw-form form lw-ng-form " name="CategoryListCtrl.[[CategoryListCtrl.ngFormName]]" ng-submit="CategoryListCtrl.submit()" novalidate ng-if="canAccess('manage.category.write.create')">
                    <!-- Name -->              
                        <lw-form-field field-for="name" label="" v-label="<?= __tr('Category Name') ?>">
                            <div class="input-group">
                                <input type="text" 
                                    class="lw-form-field form-control" 
                                    ng-model="CategoryListCtrl.categoryData.name" 
                                    name="name"
                                    ng-maxlength="45"
                                    placeholder="Add New Category"            
                                    />
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary" title="<?= __tr('Add') ?>"><?= __tr('Add') ?></button>
                                </div>
                            </div>
                        </lw-form-field>
                    <!-- /Name -->
                </form>
                <!-- /Category Add Form -->
            </div>
        </div>
    </div>
    
    <table class="table table-striped table-bordered" id="lwCategoryList" cellspacing="0" width="100%">
          <thead>
             <tr>
                <th><?= __tr('Name') ?></th>
                <th><?= __tr('Status') ?></th>
                <th><?= __tr('Created On') ?></th>
                <th><?= __tr('Action') ?></th>
             </tr>
          </thead>
          <tbody></tbody>
    </table>
       <div ui-view></div>
    </div>

    <!-- Delete category button -->
    <input type="hidden" id="categoryDeleteConfirm" 
            data-message="<?= __tr( 'You want to delete <strong> __name__ </strong> category, all the related products and product combinations belongs to this category will be deleted') ?>" 
            data-delete-button-text="<?= __tr('Yes, delete it') ?>" 
            data-success-text="<?= __tr( 'Deleted!') ?>">
    <!-- Delete category button -->
</div>

<!-- action template -->
<script type="text/_template" id="categoryActionColumnTemplate">

    <% if(__tData.can_edit) { %>
      <button type="button" class="btn btn-default btn-xs" ng-click="CategoryListCtrl.openEditDialog('<%- __tData._uid %>')"><i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></button>
    <% } %>

    <% if(__tData.can_delete) { %>
      <button type="button" class="btn btn-danger btn-xs" ng-click="CategoryListCtrl.delete('<%- __tData._uid %>', '<%- _.escape(__tData.name) %>')"><i class="fa fa-trash-o"></i> <?= __tr('Delete') ?></button>
    <% } %>
</script>
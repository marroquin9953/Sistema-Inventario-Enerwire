<?php 
/*
*  Component  : TaxPreset
*  View       : Taxpreset Controller
*  Engine     : TaxPresetEngine  
*  File       : taxpreset.list.blade.php  
*  Controller : TaxpresetListController 
----------------------------------------------------------------------------- */
?>
<div>

	<div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <div class="lw-heading">
                <?= __tr('Manage Tax Presets') ?>
            </div>
        </h3>

         <!-- button -->
        <div class="lw-section-right-content float-right">
            <a ng-if="canAccess('manage.tax_preset.write.create')" href title="<?= __tr('Add New Tax Preset') ?>" class="lw-btn btn btn-sm btn-primary pull-right" ng-click="taxpresetListCtrl.openAddDialog()">
            <i class="fa fa-plus"></i> <?= __tr('Add New Tax Preset') ?> </a>
        </div>
        <!--/ button -->
  
    </div>
    <!-- /main heading -->

    <table class="table table-striped table-bordered" id="lwtaxpresetList" class="ui celled table" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th><?= __tr('Title') ?></th>
                <th><?= __tr('Description') ?></th>
                <th><?= __tr('Status') ?></th>
                <th><?= __tr('Action') ?></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div ui-view></div>
    
        
</div> 

<!-- action template -->
<script type="text/_template" id="taxpresetActionColumnTemplate">
	<% if(__tData.can_edit) { %>
		<button title="<?= __tr('Edit Taxpreset') ?>" class="btn btn-default btn-xs lw-btn" ng-click="taxpresetListCtrl.openEditDialog('<%- __tData._uid %>')"><i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></button>
	<% } %>

	<% if(__tData.can_delete) { %>
		<button class="lw-btn btn btn-danger btn-xs" title="<?= __tr('Delete') ?>" ng-click="taxpresetListCtrl.delete('<%- __tData._uid %>', '<%- __tData.title %>')"><i class="fa fa-trash-o"></i> <?= __tr('Delete') ?></button>
	<% } %>

</script>
<!-- /action template -->

<!-- action template -->
<script type="text/_template" id="titleColumnTemplate">
	<% if(__tData.can_view_tax_list) { %>
		<a  ui-sref="tax({'taxPresetIdOrUid' : '<%- __tData._uid %>' })"> <%- __tData.title %> </a>
	<% } else { %>
		<%- __tData.title %>
	<% } %>
</script>
<!-- /action template -->


<!-- Delete customer button -->
<input type="hidden" id="taxpresetDeleteConfirm" 
    data-message="<?= __tr( 'You want to delete <strong> __name__ </strong> tax preset, all the related taxes will be deleted.') ?>" 
    data-delete-button-text="<?= __tr('Yes, delete it') ?>" 
    data-success-text="<?= __tr( 'Deleted!') ?>">
<!-- Delete customer button -->
<?php
/*
*  Component  : Upload
*  View       : Uploaded attachment 
*  Engine     : UploaderEngine  
*  File       : uploaded-attachment.blade.php  
*  Controller : uploadedFileDialogController 
----------------------------------------------------------------------------- */ 
?>

<div ng-controller='uploadedFileDialogController as uploadedFileDialogCtrl'>
    <!-- Modal Heading -->
    <div class="modal-header">
        <h3 class="modal-title"><?= __tr('Uploaded Files') ?></h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Modal Body -->
    <div class="modal-body">
        <div class="table-responsive">
            <table class="table table-bordred">
                <thead>
                    <th ng-if="uploadedFileDialogCtrl.uploadedFiles.length > 0">
                        <lw-select-all-checkbox 
                            checkboxes="uploadedFileDialogCtrl.uploadedFiles" 
                            all-selected="uploadedFileDialogCtrl.allSelectedItems"
                            id="selectedStatus"
                            all-clear="uploadedFileDialogCtrl.noSelectedItems"
                            >
                        </lw-select-all-checkbox>
                    </th>
                    <th><?= __tr('Thumbnail') ?></th>
                    <th><?= __tr('Name') ?></th>
                    <th><?= __tr('Action') ?></th>
                </thead>
                <tbody >
                    <tr ng-if="uploadedFileDialogCtrl.uploadedFiles.length > 0" ng-repeat="uploadedFile in uploadedFileDialogCtrl.uploadedFiles">
                        <td>
                            <input  type="checkbox" id="lw_[[uploadedFile.name]]" ng-model="uploadedFile.isSelected"/><label for="lw_[[uploadedFile.name]]"></label>
                        </td>
                        <td>
                            <img class="image-responsive thumbnail" height="50" ng-src="[[uploadedFile.path]]">
                        </td>
                        <td ng-bind="uploadedFile.name"></td>
                        <td>
                            <button class="btn btn-danger btn-xs" ng-click="uploadedFileDialogCtrl.delete(uploadedFile.name)">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </td>
                    </tr>
                    <tr ng-if="uploadedFileDialogCtrl.uploadedFiles.length > 0" >
                        <td col-span="4">
                            <button class="btn btn-danger btn-xs" ng-click="uploadedFileDialogCtrl.deleteMultipleFiles()">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </td>
                    </tr>
                    <tr ng-if="uploadedFileDialogCtrl.uploadedFiles.length == 0">
                        <td col-span="3"><?= __tr('There are no uploaded files.') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal Body -->
    <!-- Modal footer -->
    <div class="modal-footer">
        <button class="btn btn-primary" title="<?= __tr( 'Select' ) ?>" ng-click="uploadedFileDialogCtrl.selectFiles()"><?= __tr( 'Select' ) ?></button>

        <button class="btn btn-default" title="<?= __tr( 'Close' ) ?>" ng-click="uploadedFileDialogCtrl.closeDialog()"><?= __tr( 'Close' ) ?></button>
    </div>
    <!-- /Modal footer -->

</div>
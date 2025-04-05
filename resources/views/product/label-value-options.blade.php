<div class="card mb-3" ng-repeat="(labelKey, labelOption) in productLabelCtrl.productData.optionLabels">
    <div class="card-header">
        <strong>#00[[ labelKey + 1 ]]</strong>
        <button type="button" ng-if="!labelOption._id && !$first" ng-show="$last" class="close" aria-label="Close" ng-click="productLabelCtrl.removeCombination(labelKey)">
          <span aria-hidden="true">&times;</span>
        </button>

        <button type="button" ng-if="labelOption._id && !$first" class="close" aria-label="Close" ng-click="productLabelCtrl.deleteCombination(labelOption._id, labelKey)">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="card-body">

        <div class="form-row">
            <div class="col">
                <!-- Title -->
                <lw-form-field field-for="optionLabels.[[ labelKey ]].title" label="<?= __tr('Title') ?>">
                    <input type="text" 
                        class="lw-form-field form-control"
                        ng-model="productLabelCtrl.productData.optionLabels[labelKey]['title']" 
                        name="optionLabels.[[ labelKey ]].title"
                        ng-required="true" 
                        ng-maxlength="150"/>
                </lw-form-field>
                <!-- /Title -->
            </div>
            <div class="col">
                <!-- Price -->
                <lw-form-field field-for="optionLabels.[[ labelKey ]].price" label="<?= __tr('Purchase Price') ?>">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                [[ productLabelCtrl.currencySymbol ]]
                            </span>
                        </div>
                        <input type="number" 
                            class="lw-form-field form-control"
                            ng-model="productLabelCtrl.productData.optionLabels[labelKey]['price']" 
                            name="optionLabels.[[ labelKey ]].price"
                            ng-required="true" 
                            ng-maxlength="45" 
                            placeholder="0"            
                        />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                [[ productLabelCtrl.currency ]]
                            </span>
                        </div>
                    </div>
                </lw-form-field>
                <!-- /Price -->
            </div>
            <div class="col">
                <!-- Sell Price -->
                <lw-form-field field-for="optionLabels.[[ labelKey ]].selling_price" label="<?= __tr('Sell Price') ?>">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                [[ productLabelCtrl.currencySymbol ]]
                            </span>
                        </div>
                        <input type="number" 
                            class="lw-form-field form-control"
                            ng-model="productLabelCtrl.productData.optionLabels[labelKey]['selling_price']" 
                            name="optionLabels.[[ labelKey ]].selling_price"
                            ng-required="true" 
                            ng-maxlength="45"
                            placeholder="0"              
                        />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                [[ productLabelCtrl.currency ]]
                            </span>
                        </div>
                    </div>
                </lw-form-field>
                <!-- /Sell Price -->
            </div>
        </div>
        
        <div class="form-row">
           
            <div class="col">
                <!-- Product ID / SKU -->
                <lw-form-field field-for="optionLabels.[[ labelKey ]].product_id" label="<?= __tr('Product ID / SKU') ?>">
                    <input type="text" 
                        class="lw-form-field form-control"
                        ng-model="productLabelCtrl.productData.optionLabels[labelKey]['product_id']" 
                        name="optionLabels.[[ labelKey ]].product_id"
                        ng-required="true" autocomplete="off"
                        ng-maxlength="150"/>
                </lw-form-field>
                <!-- /Product ID / SKU -->
            </div>

            <div class="col">

                <!-- Barcode -->
                <lw-form-selectize-field field-for="optionLabels.[[ labelKey ]].barcodes" label="<?= __tr('Barcodes') ?>" class="lw-selectize">
                    <selectize config='productLabelCtrl.barcodesSelectConfig' 
                        class="form-control lw-form-field" 
                        ng-model="productLabelCtrl.productData.optionLabels[labelKey]['barcodes']" 
                        name="optionLabels.[[ labelKey ]].barcodes"
                        id="optionLabels.[[ labelKey ]].barcodes" 
                        options='productLabelCtrl.barcodesOp[labelKey]' 
                        placeholder="<?= __tr('Add barcodes..') ?>"
                        ng-maxlength="45" lw-detect-barcode>
                    </selectize>
                </lw-form-selectize-field>
                <!-- Barcode -->
            </div>
            
        </div>

        <ul class="list-group list-group-flush mt-4">
            <li class="list-group-item" ng-repeat="(valueKey, optionValue) in labelOption.values">
                <div class="form-row">
                    <div class="col">
                        <!-- Label Name -->
                        <lw-form-selectize-field field-for="optionLabels.[[ labelKey ]].values.[[ valueKey ]].label_name" label="<?= __tr( 'Label' ) ?>" class="lw-selectize">
                            <selectize config='productLabelCtrl.labelSelectConfig' 
                                class="form-control lw-form-field" 
                                name="optionLabels.[[ labelKey ]].values.[[ valueKey ]].label_name" 
                                ng-model="productLabelCtrl.productData.optionLabels[labelKey]['values'][valueKey]['label_name']" 
                                options='productLabelCtrl.labelData' 
                                placeholder="<?= __tr('Color, Size etc..') ?>"
                                ng-maxlength="150">
                            </selectize>
                        </lw-form-selectize-field>
                        <!-- /Label -->
                    </div>
                    <div class="col">
                        <!-- Value -->
                        <lw-form-field field-for="optionLabels.[[ labelKey ]].values.[[ valueKey ]].value_name" label="<?= __tr('Value') ?>">
                            <input type="text" 
                                class="lw-form-field form-control"
                                ng-model="productLabelCtrl.productData.optionLabels[labelKey]['values'][valueKey]['value_name']" 
                                name="optionLabels.[[ labelKey ]].values.[[ valueKey ]].value_name" 
                                placeholder="<?= __tr("Red, Yellow, 15.5 etc..") ?>"
                                ng-maxlength="150"             
                                />
                        </lw-form-field>
                        <!-- /Value -->
                    </div>
                    <div class="col-lg-1">
                        <!-- non deletable entry -->
                        <a href ng-if="$first && !productLabelCtrl.productData.optionLabels[labelKey]['values'][valueKey]['value_id']" title="Remove"><i class="fa fa-minus-circle fa-2x lw-minus-icon"></i></a>
                        <!-- /non deletable entry -->

                        <!-- non deletable entry -->
                        <a href class="text-danger" ng-if="$first && productLabelCtrl.productData.optionLabels[labelKey]['values'][valueKey]['value_id']" title="Delete"><i class="fa fa-times-circle fa-2x lw-minus-icon"></i></a>
                        <!-- /non deletable entry -->

                        <!-- deletable entry -->
                        <a href ng-if="!$first && !productLabelCtrl.productData.optionLabels[labelKey]['values'][valueKey]['value_id']" ng-click="productLabelCtrl.removeValue(labelKey, valueKey)" title="Remove"><i class="fa fa-minus-circle fa-2x lw-minus-icon"></i></a>
                        <!-- deletable entry -->

                        <!-- deletable entry -->
                        <a href class="text-danger" ng-if="!$first && productLabelCtrl.productData.optionLabels[labelKey]['values'][valueKey]['value_id']" ng-click="productLabelCtrl.deleteValue(productLabelCtrl.productData.optionLabels[labelKey]['values'][valueKey]['value_id'], labelKey, valueKey, productLabelCtrl.productData.optionLabels[labelKey]['_id'])" title="Delete"><i class="fa fa-times-circle fa-2x lw-minus-icon"></i></a>
                        <!-- deletable entry -->
                    </div>
                </div>
                <a href ng-if="$last" title="<?= __tr('Add More') ?>" ng-click="productLabelCtrl.addMoreValue(labelKey)"><i class="fa fa-plus-circle fa-2x"></i></a>
            </li>
        </ul>
    </div>
    <div class="card-footer" ng-if="$last">
        <button type="button" class="lw-btn btn btn-secondary btn-sm" title="<?= __tr('Create New Combination') ?>" ng-click="productLabelCtrl.addMoreCombination(productLabelCtrl.productData.optionLabels[labelKey]['label_name'])"><i class="fa fa-plus"></i> <?= __tr('Create New Combination') ?></button>
    </div>
</div>

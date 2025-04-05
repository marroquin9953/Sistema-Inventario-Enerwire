<?php   
/*
*  Component  : Billing
*  View       : Billing Controller
*  Engine     : BillingEngine  
*  File       : billing.add.blade.php  
*  Controller : BillingEditController as billingEditCtrl 
----------------------------------------------------------------------------- */
?>
<div class="lw-section-heading-block">
    <!-- main heading -->
    <h3 class="lw-section-heading">
        <div class="lw-heading">
            <?= __tr('Edit Bill') ?>
        </div>
    </h3>
    <!-- /main heading -->
</div>

<input type="hidden" id="billingMessagesInfo" data-confirm-button-text="<?= __tr('Update Quantity') ?>" data-success-text="<?= __tr( 'Deleted!') ?>">

<div class="container">
    <div class="row justify-content-md-center">            
        <div class="col col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form class="ui form lw-form lw-ng-form" name="billingEditCtrl.[[billingEditCtrl.ngFormName]]" novalidate>
                        <p class="card-text">
                            <!-- Logo Image -->
                            <a class="lw-item-link float-right mt-4" ng-show="manageCtrl.isLoggedIn()" ui-sref="dashboard"><img src="<?=  getConfigurationSettings('logo_image_url')  ?>" alt=""></a>
                            <!-- /Logo Image -->

                            <!-- Store Name -->
                            <strong><?= getConfigurationSettings('name') ?></strong>                        
                            <!-- /Store Name -->
                            <div>[[ billingEditCtrl.userData.fullName ]]</div>
                            <div>[[ billingEditCtrl.userData.address1 ]]</div>
                            <div>[[ billingEditCtrl.userData.address2 ]]</div>
                            <div>[[ billingEditCtrl.userData.country ]]</div>
                        </p>
                        <div class="row">
                            <div class="col-lg-6 float-left mt-4">
                                <!-- Customer -->
                                <lw-form-selectize-field field-for="customer" label="<?= __tr( 'Customer' ) ?>" class="lw-selectize lw-billing-selectize">
                                    <selectize config='billingEditCtrl.customerSelectConfig' 
                                        class=" form-control lw-form-field" 
                                        name="customer" 
                                        ng-model="billingEditCtrl.billingData.customer" 
                                        ng-required="true"
                                        ng-change="billingEditCtrl.selectCustomer(billingEditCtrl.billingData.customer)"
                                        options='billingEditCtrl.customerData' 
                                        placeholder="<?= __tr('Select Customer') ?>">
                                    </selectize>
                                </lw-form-selectize-field>
                                <!-- /Customer -->
                                <h5>
                                    <div>[[ billingEditCtrl.billingData.customerDetails.name ]]</div>
                                    <div>[[ billingEditCtrl.billingData.customerDetails.description ]]</div>
                                    <div>[[ billingEditCtrl.billingData.customerDetails.country ]]</div>
                                </h5>

                                <!-- Customer -->
                                <lw-form-selectize-field field-for="combination" label="" class="lw-selectize lw-billing-selectize">
                                    <selectize config='billingEditCtrl.combinationSelectConfig' 
                                        class="form-control lw-form-field"
                                        name="combination" 
                                        ng-model="billingEditCtrl.billingData.combination" 
                                        data-key="[[ index ]]"
                                        ng-required="!billingEditCtrl.billingData.productCombinations[ (billingEditCtrl.billingData.productCombinations.length - 1) ]['showDetails']"
                                        ng-change="billingEditCtrl.showProductDetails(billingEditCtrl.billingData.combination)"
                                        options='billingEditCtrl.productData' 
                                        placeholder="<?= __tr('Search Using Barcode, Combination or Product Name') ?>">
                                    </selectize>
                                </lw-form-selectize-field>
                                <!-- /Customer -->

                            </div>
                            <div class="col-lg-6">
                                <table class="table lw-billing-meta-table table-bordered table-sm">
                                    <tr>
                                        <td><?= __tr('Bill Date') ?></td>
                                        <td>
                                            <!-- Bill Date -->
                                            <lw-form-field field-for="bill_date" label=""> 
                                                <input type="text" 
                                                    class="lw-form-field form-control lw-readonly-control"
                                                    name="bill_date"
                                                    id="bill_date"
                                                    lw-bootstrap-md-datetimepicker
                                                    options="[[ reportListCtrl.dateConfig ]]"
                                                    readonly
                                                    ng-model="billingEditCtrl.billingData.bill_date" 
                                                />
                                            </lw-form-field>
                                            <!-- /Bill Date -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?= __tr('Due Date') ?></td>
                                        <td>
                                            <!-- Due Date -->
                                            <lw-form-field field-for="due_date" label=""> 
                                                <input type="text" 
                                                    class="lw-form-field form-control lw-readonly-control"
                                                    name="due_date"
                                                    id="due_date"
                                                    lw-bootstrap-md-datetimepicker
                                                    options="[[ reportListCtrl.dateConfig ]]"
                                                    readonly
                                                    ng-model="billingEditCtrl.billingData.due_date" 
                                                />
                                            </lw-form-field>
                                            <!-- /Due Date -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?= __tr('Bill No') ?></td>
                                        <td>
                                            <!-- Bill No -->
                                            <lw-form-field field-for="bill_number" label="">
                                                <input type="text" 
                                                    class="lw-form-field form-control" 
                                                    ng-model="billingEditCtrl.billingData.bill_number" 
                                                    name="bill_number"
                                                    ng-required="true"
                                                    ng-maxlength="45"             
                                                />
                                            </lw-form-field>
                                            <!-- /Bill No -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?= __tr('Txn') ?></td>
                                        <td>
                                            <!-- Txn -->
                                            <lw-form-field field-for="txn_id" label="">
                                                <input type="text" 
                                                    class="lw-form-field form-control" 
                                                    ng-model="billingEditCtrl.billingData.txn_id" 
                                                    name="txn_id"
                                                    ng-maxlength="150"             
                                                />
                                            </lw-form-field>
                                            <!-- /Txn -->
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="40%"><?= __tr('Product Combination') ?></th>
                                        <th align="right" width="10%"><?= __tr('Unit Price') ?></th>
                                        <th align="right" width="15%"><?= __tr('Quantity') ?></th>
                                        <th align="right" width="10%"><?= __tr('Price') ?></th>
                                        <th colspan="2" align="right" width="25%"><?= __tr('Tax') ?></th>
                                    </tr>
                                </thead>
                                <tbody  class="lw-selectize-parent">
                                    <tr ng-repeat="(index, productCombination) in billingEditCtrl.billingData.productCombinations" class="lw-combination-row-[[index]]">
                                        <td>
                                            <span ng-show="billingEditCtrl.billingData.productCombinations[index]['showDetails']">

                                                <strong>[[ billingEditCtrl.billingData.productCombinations[index]['combination']['name'] ]]</strong>                                                
                                                <div  ng-if="billingEditCtrl.billingData.productCombinations[index]['combination']['combinations'].length > 0">
                                                    (<small ng-repeat="combo in billingEditCtrl.billingData.productCombinations[index]['combination']['combinations']">
                                                        <strong>[[ combo.labelName ]]: </strong>[[ combo.valueName ]]<span ng-if="!$last">,</span>
                                                    </small>)
                                                </div>
                                                
                                                <small class="text-secondary">
                                                    <?= __tr('SKU') ?>: [[ billingEditCtrl.billingData.productCombinations[index]['combination']['comboSKU'] ]]
                                                </small>
                                                <div>
                                                    <small class="text-secondary">
                                                        <?= __tr('Combination') ?>: [[ billingEditCtrl.billingData.productCombinations[index]['combination']['combinationTitle'] ]]
                                                    </small>
                                                </div>
                                                <div>
                                                    <small class="text-secondary">
                                                        <?= __tr('Available Quantity') ?>: [[ billingEditCtrl.billingData.productCombinations[index]['combination']['quantity'] ]]
                                                        <span ng-if="billingEditCtrl.billingData.productCombinations[index]['combination']['lockQuantity'] > 0">
                                                            (<?= __tr('but locked in other drafted bill') ?> : [[ billingEditCtrl.billingData.productCombinations[index]['combination']['lockQuantity'] ]])
                                                        </span>
                                                    </small>
                                                </div>
                                                <div ng-if="billingEditCtrl.billingData.productCombinations[index]['location_name']">
                                                    <small class="text-secondary">
                                                        <?= __tr('Location') ?>: [[ billingEditCtrl.billingData.productCombinations[index]['location_name'] ]]
                                                    </small>
                                                </div>

                                                <!-- Product is inactive -->
                                                <small ng-if="billingEditCtrl.billingData.productCombinations[index]['combination']['product_status'] == 2" class="text-danger">
                                                    <i class="fa fa-exclamation-circle"></i>
                                                    <?= __tr('Product is inactive') ?>
                                                </small>
                                                <!-- /Product is inactive -->

                                                <!-- Product is deleted -->
                                                <small ng-if="billingEditCtrl.billingData.productCombinations[index]['combination']['product_status'] == 3" class="text-danger">
                                                    <i class="fa fa-exclamation-circle"></i>
                                                    <?= __tr('Product is deleted') ?>
                                                </small>
                                                
                                                <!-- /Product is deleted -->

                                                <!-- Product combination is deleted -->
                                                <small ng-if="billingEditCtrl.billingData.productCombinations[index]['combination']['combinationStatus'] == 3" class="text-danger">
                                                    <i class="fa fa-exclamation-circle"></i>
                                                    <?= __tr('Product combination is deleted') ?>
                                                </small>
                                                <!-- /Product combination is deleted -->
                                            </span>
                                        </td>
                                        <td align="right" class="lw-middile-align">
                                            [[ productCombination.formattedUnitPrice ]]
                                        </td>
                                        <td>
                                            <!-- Quantity -->
                                            <lw-form-field field-for="productCombinations.[[ index ]].quantity" label="">
                                                <div class="input-group">
                                                <input type="number" 
                                                    class="lw-form-field form-control"
                                                    ng-model="billingEditCtrl.billingData.productCombinations[index]['quantity']" 
                                                    style="text-align: center;"
                                                    name="productCombinations.[[ index ]].quantity"
                                                    ng-change="billingEditCtrl.calculateTotalPrice()"
                                                    ng-keypress="billingEditCtrl.isEnterKeyPress($event, index)"
                                                    min="0"
                                                    max="[[ billingEditCtrl.billingData.productCombinations[index]['combination']['availableQty'] ]]"/>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                           / [[ billingEditCtrl.billingData.productCombinations[index]['combination']['availableQty'] ]]
                                                        </span>
                                                    </div>
                                                </div>
                                            </lw-form-field>
                                            <!-- /Quantity -->
                                        </td>
                                        <td align="right" class="lw-middile-align">
                                            <div class="lw-billing-amount">
                                                [[ productCombination.formattedPrice ]]
                                            </div>
                                            <button type="button" ng-if="billingEditCtrl.billingData.productCombinations.length == 1" class="btn btn-outline-secondary btn-sm lw-billing-remove-btn"><i class="fa fa-times"></i></button>

                                            <button type="button" ng-if="billingEditCtrl.billingData.productCombinations.length > 1" class="btn btn-outline-secondary btn-sm lw-billing-remove-btn" ng-click="billingEditCtrl.removeItem(index, productCombination.stock_transaction_uid)"><i class="fa fa-times"></i></button>
                                        </td>
                                        <td colspan="2" class="lw-middile-align">
                                        	 <div>
	                                        	<table class="table table-borderless">
													<tbody>
														<tr ng-repeat="tax_d in billingEditCtrl.billingData.productCombinations[index].tax_details">
															<td>
																<span ng-bind="tax_d.title"></span>
																<span ng-if="tax_d.type == 2">([[tax_d.tax_amount]]%)</span>
															</td>
															<td>[[billingEditCtrl.currencySymbol]][[tax_d.tax_amount_on_product]]</td>
														</tr>
													</tbody>
												</table>
	                                        </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" align="right">
                                            <h5><?= __tr('Product Total') ?></h5>
                                        </td>
                                        <td align="right">
                                            <div class="lw-billing-amount">
                                                [[ billingEditCtrl.formattedUnitPriceTotal ]]
                                            </div>
                                        </td>
                                        <td align="right">
                                            <h5><?= __tr('Tax Total') ?></h5>
                                        </td>
                                        <td align="right">
                                            <div class="lw-billing-amount">
                                                [[ billingEditCtrl.formattedTaxTotalAmount ]]
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- <tr>
                                        <td colspan="3" align="right">
                                            <h5><?= __tr('Sub Total') ?></h5>
                                        </td>
                                        <td colspan="2" align="right">
                                            <div class="lw-billing-amount">
                                                [[ billingEditCtrl.formattedSubTotal ]]
                                            </div>
                                        </td>
                                    </tr> -->
                                    <tr>
                                        <td colspan="3" align="right">
                                            <!-- Add Tax -->
                                            <lw-form-checkbox-field field-for="is_add_tax" label="<?= __tr( 'Add Tax' ) ?>">
                                                <input type="checkbox" 
                                                    class="lw-form-field js-switch"
                                                    name="is_add_tax"
                                                    ng-change="billingEditCtrl.calculateTotalPrice()" 
                                                    ng-model="billingEditCtrl.billingData.is_add_tax"
                                                    ui-switch="" />
                                            </lw-form-checkbox-field>
                                            <!-- /Add Tax -->

                                            <span ng-if="billingEditCtrl.billingData.is_add_tax">
                                                <!-- Tax -->
                                                <lw-form-field field-for="tax" label="Tax">
                                                    <div class="input-group lw-bill-input-field">
                                                        <input type="number" 
                                                            class="lw-form-field form-control"
                                                            ng-model="billingEditCtrl.billingData.tax"
                                                            name="tax"
                                                            ng-change="billingEditCtrl.calculateTotalPrice()" 
                                                            min="1"       
                                                        />
                                                        <div class="input-group-append">
                                                            <select ng-model="billingEditCtrl.billingData.tax_type" ng-change="billingEditCtrl.calculateTotalPrice()">
                                                            <option value="1">%</option>
                                                            <option value="2">[[ billingEditCtrl.currencySymbol ]]</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </lw-form-field>
                                                <!-- /Tax -->

                                                <!-- Tax Description -->
                                                <lw-form-field field-for="tax_description" label="<?= __tr('Description') ?>">
                                                    <textarea 
                                                        ng-model="billingEditCtrl.billingData.tax_description"
                                                        cols="10" 
                                                        rows="2" 
                                                        class="lw-form-field form-control lw-bill-input-field" 
                                                        name="tax_description"
                                                        ng-maxlength="255"
                                                        ></textarea>
                                                </lw-form-field>
                                                <!-- /Tax Description -->
                                            </span>
                                        </td>
                                        <td colspan="3" align="right">
                                            <div class="lw-billing-amount" ng-if="billingEditCtrl.billingData.is_add_tax">
                                                + [[ billingEditCtrl.billingData.formatted_tax_amount ]]
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" align="right">

                                            <!-- Add Discount -->
                                            <lw-form-checkbox-field field-for="is_add_discount" label="<?= __tr( 'Add Discount' ) ?>">
                                                <input type="checkbox" 
                                                    class="lw-form-field js-switch"
                                                    name="is_add_discount"
                                                    ng-change="billingEditCtrl.calculateTotalPrice()" 
                                                    ng-model="billingEditCtrl.billingData.is_add_discount"
                                                    ui-switch="" />
                                            </lw-form-checkbox-field>
                                            <!-- /Add Discount -->

                                            <span ng-if="billingEditCtrl.billingData.is_add_discount">
                                                <!-- Discount -->
                                                <lw-form-field field-for="discount" label="<?= __tr('Discount') ?>">
                                                    <div class="input-group lw-bill-input-field">
                                                        <input type="number" 
                                                            class="lw-form-field form-control"
                                                            ng-model="billingEditCtrl.billingData.discount"
                                                            name="discount"
                                                            ng-change="billingEditCtrl.calculateTotalPrice()" 
                                                            min="1"       
                                                        />
                                                        <div class="input-group-append">
                                                            <select ng-model="billingEditCtrl.billingData.discount_type" ng-change="billingEditCtrl.calculateTotalPrice()">
                                                                <option value="1">%</option>
                                                                <option value="2">[[ billingEditCtrl.currencySymbol ]]</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </lw-form-field>
                                                <!-- /Discount -->

                                                <!-- Discount Description -->
                                                <lw-form-field field-for="discount_description" label="<?= __tr('Description') ?>">
                                                    <textarea 
                                                        ng-model="billingEditCtrl.billingData.discount_description"
                                                        cols="10" 
                                                        rows="2" 
                                                        class="lw-form-field form-control lw-bill-input-field" 
                                                        name="discount_description"
                                                        ng-maxlength="255"
                                                        ></textarea>
                                                </lw-form-field>
                                                <!-- /Discount Description -->
                                            </span>
                                        </td>
                                        <td colspan="3" align="right">
                                            <div class="lw-billing-amount" ng-if="billingEditCtrl.billingData.is_add_discount">
                                                - [[ billingEditCtrl.billingData.formatted_discount_amount ]]
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" align="right">
                                            <h4><?= __tr('Total Amount') ?></h4>
                                        </td>
                                        <td colspan="3" align="right">
                                            <div class="lw-billing-amount">
                                                <h4>[[ billingEditCtrl.formattedTotalAmount ]]</h4>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>                    
                        <div class="lw-actions m-2">
                            <button type="button" class="lw-btn btn btn-primary" title="<?= __tr('Mark as Draft') ?>" ng-click="billingEditCtrl.update(1)">
                            <?= __tr('Mark as Draft') ?></button>

                            <button type="button" class="lw-btn btn btn-primary" title="<?= __tr('Mark as Paid') ?>" ng-click="billingEditCtrl.update(2)">
                            <?= __tr('Mark as Paid') ?></button>
                            
                            <a ui-sref="billing" title="<?= __tr('Back') ?>" class="lw-btn btn btn-secondary mt-1"><?= __tr('Back') ?></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/_template" id="lwSelectizeOp">
    <div>
        <span class="title">
            <%= __tData.item.name %>
        </span>
        <span class="text-muted">
            <small>(SKU : <%= __tData.item.comboSKU %>)</small>
        </span>
        <span class="text-muted">
            <small>(combination : <%= __tData.item.combinationTitle %>)</small>
        </span>
        <small>
        	<% if(!_.isEmpty(__tData.item.combinations)) { %>
	            (<% _.forEach(__tData.item.combinations, function(combo) { %>            
	                <%= combo.labelName %> : <%= combo.valueName %>            
	            <% }) %>)
            <% } %>
        </small>        
    </div>
</script>

<!-- Delete bill button -->
<input type="hidden" id="billTransactionDelete" 
    data-message="<?= __tr( 'You want to delete this product combination from bill.') ?>" 
    data-delete-button-text="<?= __tr('Yes, delete it') ?>" 
    data-success-text="<?= __tr( 'Deleted!') ?>">
<!-- Delete bill button -->
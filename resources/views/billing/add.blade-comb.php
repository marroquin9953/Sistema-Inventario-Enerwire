<?php   
/*
*  Component  : Billing
*  View       : Billing Controller
*  Engine     : BillingEngine  
*  File       : billing.add.blade.php  
*  Controller : BillingAddController as billingAddCtrl 
----------------------------------------------------------------------------- */
?>
<div class="lw-section-heading-block">
    <!-- main heading -->
    <h3 class="lw-section-heading">
        <div class="lw-heading">
            <?= __tr('Egreso y Actualización de Producto') ?>
        </div>
    </h3>
    <!-- /main heading -->
</div>

<input type="hidden" id="billingMessagesInfo" data-confirm-button-text="<?= __tr('Actualizar Cantidad') ?>" data-success-text="<?= __tr( 'Eliminado!') ?>">

<form class="ui form lw-form lw-ng-form" name="billingAddCtrl.[[billingAddCtrl.ngFormName]]" novalidate>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">
                            <!-- Logo Image -->
                            <a class="lw-item-link float-right mt-4" ng-show="manageCtrl.isLoggedIn()" ui-sref="dashboard"><img src="<?=  getConfigurationSettings('logo_image_url')  ?>" alt=""></a>
                            <!-- /Logo Image -->

                            <!-- Store Name -->
                            <strong><?= getConfigurationSettings('name') ?></strong>                        
                            <!-- /Store Name -->
                            <div>[[ billingAddCtrl.userData.fullName ]]</div>
                            <div>[[ billingAddCtrl.userData.address1 ]]</div>
                            <div>[[ billingAddCtrl.userData.address2 ]]</div>
                            <div>[[ billingAddCtrl.userData.country ]]</div>
                        </p>
                        <div class="row">
                            <div class="col-lg-6 float-left mt-4">
                                <!-- Customer -->
                                <lw-form-selectize-field field-for="customer" label="<?= __tr( 'Cliente' ) ?>" class="lw-selectize lw-billing-selectize">
                                    <selectize config='billingAddCtrl.customerSelectConfig' 
                                        class=" form-control lw-form-field" 
                                        name="customer" 
                                        ng-model="billingAddCtrl.billingData.customer" 
                                        ng-required="true"
                                        ng-change="billingAddCtrl.selectCustomer(billingAddCtrl.billingData.customer)"
                                        options='billingAddCtrl.customerData' 
                                        placeholder="<?= __tr('Seleccionar Cliente') ?>">
                                    </selectize>
                                </lw-form-selectize-field>
                                <!-- /Customer -->
                                <h5>
                                    <div>[[ billingAddCtrl.billingData.customerDetails.name ]]</div>
                                    <div>[[ billingAddCtrl.billingData.customerDetails.description ]]</div>
                                    <div>[[ billingAddCtrl.billingData.customerDetails.country ]]</div>
                                </h5>

                                <!-- Search Using Barcode, Combination or Product Name -->
                                <lw-form-selectize-field field-for="combination" label="" class="lw-selectize lw-billing-selectize">
                                    <selectize config='billingAddCtrl.combinationSelectConfig' 
                                        class="form-control lw-form-field"
                                        name="combination"
                                        ng-model="billingAddCtrl.billingData.combination"
                                        ng-required="!billingAddCtrl.billingData.productCombinations[ (billingAddCtrl.billingData.productCombinations.length - 1) ]['showDetails']"
                                        ng-change="billingAddCtrl.showProductDetails(billingAddCtrl.billingData.combination); billingAddCtrl.setInventoryUpdateData(billingAddCtrl.billingData.combination)"
                                        options='billingAddCtrl.productData' 
                                        placeholder="<?= __tr('Buscar por Código de Barras, Combinación o Nombre de Producto') ?>">
                                    </selectize>
                                </lw-form-selectize-field>
                                <!-- /Search Using Barcode, Combination or Product Name -->
                            </div>
                            <div class="col-lg-6">
                                <table class="table lw-billing-meta-table table-bordered table-sm w-100">
                                    <tr>
                                        <td><?= __tr('Fecha de Factura') ?></td>
                                        <td>
                                            <!-- Bill Date -->
                                            <lw-form-field field-for="bill_date" label=""> 
                                                <input type="text" 
                                                    class="lw-form-field form-control lw-readonly-control"
                                                    name="bill_date"
                                                    id="bill_date"
                                                    lw-bootstrap-md-datetimepicker
                                                    options="[[ reportListCtrl.dateConfig ]]"
                                                    readonly
                                                    ng-model="billingAddCtrl.billingData.bill_date" 
                                                />
                                            </lw-form-field>
                                            <!-- /Bill Date -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?= __tr('Fecha de Vencimiento') ?></td>
                                        <td>
                                            <!-- Due Date -->
                                            <lw-form-field field-for="due_date" label=""> 
                                                <input type="text" 
                                                    class="lw-form-field form-control lw-readonly-control"
                                                    name="due_date"
                                                    id="due_date"
                                                    lw-bootstrap-md-datetimepicker
                                                    options="[[ reportListCtrl.dateConfig ]]"
                                                    readonly
                                                    ng-model="billingAddCtrl.billingData.due_date" 
                                                />
                                            </lw-form-field>
                                            <!-- /Due Date -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?= __tr('No. de Documento') ?></td>
                                        <td>
                                            <!-- Bill No -->
                                            <lw-form-field field-for="bill_number" label="">
                                                <input type="text" 
                                                    class="lw-form-field form-control" 
                                                    ng-model="billingAddCtrl.billingData.bill_number" 
                                                    name="bill_number"
                                                    ng-required="true"
                                                    ng-maxlength="45"             
                                                />
                                            </lw-form-field>
                                            <!-- /Bill No -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?= __tr('Transacción') ?></td>
                                        <td>
                                            <!-- Txn -->
                                            <lw-form-field field-for="txn_id" label="">
                                                <input type="text" 
                                                    class="lw-form-field form-control" 
                                                    ng-model="billingAddCtrl.billingData.txn_id" 
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
                                        <th width="40%"><?= __tr('Combinación de Producto') ?></th>
                                        <th align="right" width="15%"><?= __tr('Precio Unitario') ?></th>
                                        <th align="right" width="15%"><?= __tr('Cantidad') ?></th>
                                        <th align="right" width="15%"><?= __tr('Precio') ?></th>
                                        <th colspan="2" align="right" width="15%"><?= __tr('Impuesto') ?></th>
                                    </tr>
                                </thead>
                                <tbody  class="lw-selectize-parent">
                                    <tr ng-repeat="(index, productCombination) in billingAddCtrl.billingData.productCombinations" class="lw-combination-row-[[index]]">
                                        <td>
                                            <span ng-show="billingAddCtrl.billingData.productCombinations[index]['showDetails']">
                                                <strong>[[ billingAddCtrl.billingData.productCombinations[index]['combination']['name'] ]]</strong>
                                                <div ng-if="billingAddCtrl.billingData.productCombinations[index]['combination']['combinations'].length > 0">
                                                    (<small ng-repeat="combo in billingAddCtrl.billingData.productCombinations[index]['combination']['combinations']">
                                                        <strong>[[ combo.labelName ]]: </strong>[[ combo.valueName ]]<span ng-if="!$last">,</span>
                                                    </small>)
                                                </div>
                                                <i ng-if="billingAddCtrl.billingData.productCombinations[index]['combination']['product_status'] == 2" class="fa fa-exclamation-circle text-danger" aria-hidden="true"></i> <br>
                                                <small class="text-secondary">
                                                    <?= __tr('SKU') ?>: [[ billingAddCtrl.billingData.productCombinations[index]['combination']['comboSKU'] ]]
                                                </small>
                                                <div>
                                                    <small class="text-secondary">
                                                        <?= __tr('Combinación') ?>: [[ billingAddCtrl.billingData.productCombinations[index]['combination']['combinationTitle'] ]]
                                                    </small>
                                                </div>
                                                <div>
                                                    <small class="text-secondary">
                                                        <?= __tr('Cantidad Disponible') ?>: [[ billingAddCtrl.billingData.productCombinations[index]['combination']['quantity'] ]] 
                                                        <span ng-if="billingAddCtrl.billingData.productCombinations[index]['combination']['lockQuantity'] > 0">
                                                            (<?= __tr('pero bloqueado en otra factura en borrador') ?>: [[ billingAddCtrl.billingData.productCombinations[index]['combination']['lockQuantity'] ]])
                                                        </span>
                                                    </small>
                                                </div>
                                                <div ng-if="billingAddCtrl.billingData.productCombinations[index]['location_name']">
                                                    <small class="text-secondary">
                                                        <?= __tr('Ubicación') ?>: [[ billingAddCtrl.billingData.productCombinations[index]['location_name'] ]]
                                                    </small>
                                                </div>
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
                                                        ng-model="billingAddCtrl.billingData.productCombinations[index]['quantity']" 
                                                        style="text-align: center;"
                                                        name="productCombinations.[[ index ]].quantity"
                                                        ng-change="billingAddCtrl.calculateTotalPrice(); billingAddCtrl.syncInventoryQuantity(index);"
                                                        ng-keypress="billingAddCtrl.isEnterKeyPress($event, index)"
                                                        min="0"
                                                        required="true"
                                                        max="[[ billingAddCtrl.billingData.productCombinations[index]['combination']['availableQty'] ]]"
                                                    />
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                           / [[ billingAddCtrl.billingData.productCombinations[index]['combination']['availableQty'] ]]
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
                                            <button type="button" ng-if="billingAddCtrl.billingData.productCombinations.length == 1" class="btn btn-outline-secondary btn-sm lw-billing-remove-btn"><i class="fa fa-times"></i></button>

                                            <button type="button" ng-if="billingAddCtrl.billingData.productCombinations.length > 1" class="btn btn-outline-secondary btn-sm lw-billing-remove-btn" ng-click="billingAddCtrl.removeItem(index)"><i class="fa fa fa-times"></i></button>
                                        </td>
                                        <td colspan="2" class="lw-middile-align">
	                                        <div>
	                                        	<table class="table table-borderless table-sm">
													<tbody>
														<tr ng-repeat="tax_d in billingAddCtrl.billingData.productCombinations[index].tax_details">
															<td>
																<span ng-bind="tax_d.title"></span>
																<span ng-if="tax_d.type == 2">([[tax_d.tax_amount]]%)</span>
															</td>
															<td class="text-right">[[billingAddCtrl.currencySymbol]][[tax_d.tax_amount_on_product]]</td>
														</tr>
													</tbody>
												</table>
	                                        </div>
                                        </td>
                                    </tr>
                                    <tr ng-if="billingAddCtrl.billingData.productCombinations.length == 0">
                                    	<td class="text-center" colspan="6">
                                    		<h6 class="p-4"><?= __tr('No se han añadido combinaciones') ?></h6>
                                    	</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" align="right">
                                            <h5><?= __tr('Total de Productos') ?></h5>
                                        </td>
                                        <td align="right">
                                            <div class="lw-billing-amount">
                                                [[ billingAddCtrl.formattedUnitPriceTotal ]]
                                            </div>
                                        </td>
                                        <td align="right">
                                            <h5><?= __tr('Total de Impuestos') ?></h5>
                                        </td>
                                        <td align="right">
                                            <div class="lw-billing-amount text-right">
                                                [[ billingAddCtrl.formattedTaxTotalAmount ]]
                                            </div>
                                        </td>
                                    </tr> 
                                  <!--   <tr>
                                        <td colspan="3" align="right">
                                            <h5><?= __tr('Subtotal') ?></h5>
                                        </td>
                                        <td colspan="3" align="right">
                                            <div class="lw-billing-amount">
                                                [[ billingAddCtrl.formattedSubTotal ]]
                                            </div>
                                        </td>
                                    </tr> -->
                                    <tr>
                                        <td colspan="3" align="right">
                                            <!-- Add Tax -->
                                            <lw-form-checkbox-field field-for="is_add_tax" label="<?= __tr( 'Añadir Impuesto' ) ?>">
                                                <input type="checkbox" 
                                                    class="lw-form-field js-switch"
                                                    name="is_add_tax"
                                                    ng-change="billingAddCtrl.calculateTotalPrice()"
                                                    ng-model="billingAddCtrl.billingData.is_add_tax"
                                                    ui-switch="" />
                                            </lw-form-checkbox-field>
                                            <!-- /Add Tax -->

                                            <span ng-if="billingAddCtrl.billingData.is_add_tax">
                                                <!-- Tax -->
                                                <lw-form-field field-for="tax" label="Impuesto">
                                                    <div class="input-group lw-bill-input-field">
                                                        <input type="number" 
                                                            class="lw-form-field form-control"
                                                            ng-model="billingAddCtrl.billingData.tax"
                                                            name="tax"
                                                            ng-change="billingAddCtrl.calculateTotalPrice()" 
                                                            min="1"       
                                                        />
                                                        <div class="input-group-append">
                                                            <select class="custom-select"  ng-model="billingAddCtrl.billingData.tax_type" ng-change="billingAddCtrl.calculateTotalPrice()">
                                                            <option value="1">%</option>
                                                            <option value="2">[[ billingAddCtrl.currencySymbol ]]</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </lw-form-field>
                                                <!-- /Tax -->

                                                <!-- Tax Description -->
                                                <lw-form-field field-for="tax_description" label="<?= __tr('Descripción') ?>">
                                                    <textarea 
                                                        ng-model="billingAddCtrl.billingData.tax_description"
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
                                            <div class="lw-billing-amount" ng-if="billingAddCtrl.billingData.is_add_tax">
                                                + [[ billingAddCtrl.billingData.formatted_tax_amount ]]
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" align="right">

                                            <!-- Add Discount -->
                                            <lw-form-checkbox-field field-for="is_add_discount" label="<?= __tr( 'Añadir Descuento' ) ?>">
                                                <input type="checkbox" 
                                                    class="lw-form-field js-switch"
                                                    name="is_add_discount"
                                                    ng-change="billingAddCtrl.calculateTotalPrice()" 
                                                    ng-model="billingAddCtrl.billingData.is_add_discount"
                                                    ui-switch="" />
                                            </lw-form-checkbox-field>
                                            <!-- /Add Discount -->

                                            <span ng-if="billingAddCtrl.billingData.is_add_discount">
                                                <!-- Discount -->
                                                <lw-form-field field-for="discount" label="<?= __tr('Descuento') ?>">
                                                    <div class="input-group lw-bill-input-field">
                                                        <input type="number" 
                                                            class="lw-form-field form-control"
                                                            ng-model="billingAddCtrl.billingData.discount"
                                                            name="discount"
                                                            ng-change="billingAddCtrl.calculateTotalPrice()" 
                                                            min="1"       
                                                        />
                                                        <div class="input-group-append">
                                                            <select class="custom-select" ng-model="billingAddCtrl.billingData.discount_type" ng-change="billingAddCtrl.calculateTotalPrice()">
                                                                <option value="1">%</option>
                                                                <option value="2">[[ billingAddCtrl.currencySymbol ]]</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </lw-form-field>
                                                <!-- /Discount -->

                                                <!-- Discount Description -->
                                                <lw-form-field field-for="discount_description" label="<?= __tr('Descripción') ?>">
                                                    <textarea 
                                                        ng-model="billingAddCtrl.billingData.discount_description"
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
                                            <div class="lw-billing-amount" ng-if="billingAddCtrl.billingData.is_add_discount">
                                                - [[ billingAddCtrl.billingData.formatted_discount_amount ]]
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" align="right">
                                            <h4><?= __tr('Monto Total') ?></h4>
                                        </td>
                                        <td colspan="3" align="right">
                                            <div class="lw-billing-amount">
                                                <h4>[[ billingAddCtrl.formattedTotalAmount ]]</h4>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Sección de Actualización de Inventario -->
                        <div class="card mt-4" ng-if="billingAddCtrl.billingData.productCombinations.length > 0">
                            <div class="card-header">
                                <h5><?= __tr('Actualización de Inventario') ?></h5>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col">
                                        <lw-form-field field-for="inventorySubType" label="<?= __tr('Tipo de Actualización') ?>">
                                            <select class="form-control" 
                                                ng-model="billingAddCtrl.inventoryData.sub_type" 
                                                ng-options="subTypeKey as subType for (subTypeKey, subType) in billingAddCtrl.inventorySubTypes" 
                                                ng-change="billingAddCtrl.changeCalculationSign(billingAddCtrl.inventoryData.sub_type)"
                                                name="inventorySubType"   
                                                ng-required="true">
                                            </select>  
                                        </lw-form-field>                    
                                    </div>

                                    <div class="col">
                                        <!-- Location -->
                                        <lw-form-selectize-field field-for="inventoryLocation" label="<?= __tr('Ubicación') ?>" class="lw-selectize">
                                            <selectize config='billingAddCtrl.locationSelectConfig' class="form-control lw-form-field" name="inventoryLocation" ng-model="billingAddCtrl.inventoryData.location" ng-required="true" options='billingAddCtrl.inventoryLocations' placeholder="<?= __tr('Seleccionar Ubicación') ?>"></selectize>
                                        </lw-form-selectize-field>
                                        <!-- /Location -->
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="col">
                                        <!-- Supplier -->
                                        <lw-form-selectize-field field-for="inventorySupplier" label="<?= __tr('Proveedor') ?>" class="lw-selectize">
                                            <selectize config='billingAddCtrl.supplierSelectConfig' class="form-control lw-form-field" name="inventorySupplier" ng-model="billingAddCtrl.inventoryData.supplier" options='billingAddCtrl.inventorySuppliers' ng-disabled="billingAddCtrl.disableSupplier" ng-required="billingAddCtrl.supplierRequired" placeholder="<?= __tr('Seleccionar Proveedor') ?>"></selectize>
                                        </lw-form-selectize-field>
                                        <!-- /Supplier -->
                                    </div>

                                    <!--  Quantity  -->
                                    <div class="col">
                                        <lw-form-field field-for="inventoryQuantity" label="<?=  __tr('Cantidad a Actualizar')  ?>"> 
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">[[ billingAddCtrl.availableStockText ]] : [[ billingAddCtrl.availableQuantity ]]</span>
                                                    <span class="input-group-text">[[ billingAddCtrl.calculationSign ]]</span>
                                                </div>
                                                <input type="number" 
                                                    class="form-control lw-form-field" 
                                                    name="inventoryQuantity"
                                                    ng-required="true"
                                                    min="1"
                                                    ng-model="billingAddCtrl.inventoryData.quantity">
                                            </div>
                                        </lw-form-field>
                                    </div>
                                    <!--  /Quantity  -->
                                </div>

                                <!-- Mostrar información de la combinación seleccionada -->
                                <div class="card mt-3" ng-if="billingAddCtrl.combinationOption != ''">
                                    <div class="card-header"><?= __tr('Detalles de Combinación') ?></div>
                                    <div class="card-body">
                                        [[ billingAddCtrl.combinationOption ]]
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Sección de Actualización de Inventario -->
                                       
                        <div class="lw-actions m-2">
                            <button type="button" class="lw-btn btn btn-primary" title="<?= __tr('Marcar como Borrador y Actualizar Inventario') ?>" ng-click="billingAddCtrl.submitCombined(1)">
                            <?= __tr('Guardar como Borrador y Actualizar Inventario') ?></button>

                            <button type="button" class="lw-btn btn btn-primary" title="<?= __tr('Marcar como Pagado y Actualizar Inventario') ?>" ng-click="billingAddCtrl.submitCombined(2)">
                            <?= __tr('Marcar como Pagado y Actualizar Inventario') ?></button>
                            
                            <a ui-sref="billing" title="<?= __tr('Volver') ?>" class="lw-btn btn btn-secondary mt-1"><?= __tr('Volver') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/_template" id="lwSelectizeOp">
    <div>
        <span class="title">
            <%= __tData.item.name %>
        </span>
        <span class="text-muted">
            <small>(SKU : <%= __tData.item.comboSKU %>)</small>
        </span>
        <span class="text-muted">
            <small>(combinación : <%= __tData.item.combinationTitle %>)</small>
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
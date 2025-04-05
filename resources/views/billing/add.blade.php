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
            <?= __tr('Egreso de producto') ?>
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
                                        ng-change="billingAddCtrl.showProductDetails(billingAddCtrl.billingData.combination)"
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
                                                        ng-change="billingAddCtrl.calculateTotalPrice()"
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
                        <div class="lw-actions m-2">
                            <button type="button" class="lw-btn btn btn-primary" title="<?= __tr('Marcar como Borrador') ?>" ng-click="billingAddCtrl.submit(1)">
                            <?= __tr('Marcar como Borrador') ?></button>

                            <button type="button" class="lw-btn btn btn-primary" title="<?= __tr('Marcar como Pagado') ?>" ng-click="billingAddCtrl.submit(2)">
                            <?= __tr('Marcar como Pagado') ?></button>
                            
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

<!-- Button to Open Inventory Modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#inventoryModal" onclick="openInventoryModal()">
    <i class="fa fa-box"></i> <?= __tr('Retaceo') ?>
</button>

<!-- Modal for Inventory Management -->
<div class="modal fade" id="inventoryModal" tabindex="-1" role="dialog" aria-labelledby="inventoryModalLabel" aria-hidden="true" ng-controller="InventoryListController as InventoryListCtrl">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inventoryModalLabel"><?= __tr('Retaceo') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <div class="lw-section-heading-block">
                        <h3 class="lw-section-heading">
                            <div class="lw-heading">
                                <?= __tr( 'Administrar inventario' ) ?>
                            </div>
                        </h3>
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <lw-form-field field-for="search_term" label="<?= __tr('Buscar') ?>">
                                <input type="search" class="lw-form-field form-control" ng-model="InventoryListCtrl.search_term"
                                    ng-model-options="{ debounce: 500 }"
                                    ng-change="InventoryListCtrl.searchInvetory(InventoryListCtrl.search_term)" name="search_term" />
                            </lw-form-field>
                        </div>

                        <div class="col">
                            <lw-form-selectize-field field-for="category" label="<?= __tr( 'Categoría' ) ?>" class="lw-selectize">
                                <selectize config='InventoryListCtrl.categorySelectConfig' class="form-control lw-form-field"
                                    name="category" ng-model="InventoryListCtrl.category" ng-required="true"
                                    options='InventoryListCtrl.categories' placeholder="<?= __tr('Seleccionar categoría') ?>"></selectize>
                            </lw-form-selectize-field>
                        </div>

                        <div class="col">
                            <div class="btn-group lw-form-inline-btn">
                                <button type="button" class="btn btn-primary" ng-click="InventoryListCtrl.getInventories()">
                                    <?= __tr('Mostrar') ?>
                                </button>
                                <button type="button" class="btn btn-default" ng-click="InventoryListCtrl.clearFilter()">
                                    <?= __tr('Reiniciar') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="card mb-4" ng-repeat="inventory in InventoryListCtrl.invetoryData">
                            <div class="card-header">
                                [[ inventory.productTitle ]]
                                &nbsp;<i ng-if="inventory.productStatus == 2" class="fa fa-eye-slash text-danger"
                                    aria-hidden="true"></i>
                                <small> <em>from</em>
                                    <span ng-if="!canAccess('manage.category.read.list')">
                                        [[ inventory.categoryName ]]
                                    </span>
                                    <a ui-sref="categories" ng-if="canAccess('manage.category.read.list')">[[ inventory.categoryName
                                        ]]</a>
                                </small>
                                &nbsp;<i ng-if="inventory.categoryStatus == 2" class="fa fa-eye-slash text-danger"
                                    aria-hidden="true"></i>
                                <span class="float-right">
                                    <a href
                                        ng-click="InventoryListCtrl.getInventoryTransaction(inventory.productId, inventory.productTitle, value.combination_id, 2, null)">Transacciones</a>
                                    |
                                    <span>
                                        <?= __tr('Stock total en todas las ubicaciones') ?>: <strong
                                            ng-bind="inventory.totalStockInAllLocation"></strong>
                                    </span>
                                </span>
                            </div>
                            <div class="">
                                <table class="table" ng-repeat="(location, combination) in inventory.combinationData">
                                    <thead>
                                        <tr>
                                            <th colspan="6">
                                                <?= __tr('Location') ?>: [[ location ]]

                                                &nbsp;<i ng-if="combination[0].location_status == 2" class="fa fa-eye-slash text-danger"
                                                    aria-hidden="true"></i>

                                                <span class="float-right">
                                                    <span>
                                                        <?= __tr('Total Stock') ?>: <strong>[[ combination.totalStock ]]</strong>
                                                    </span>
                                                </span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                <?= __tr('SKU') ?>
                                            </th>
                                            <th width="45%">
                                                <?= __tr('Producto') ?>
                                            </th>
                                            <th class="text-right">
                                                <?= __tr('Precio de compra') ?>
                                            </th>
                                            <th class="text-right">
                                                <?= __tr('Precio de venta') ?>
                                            </th>
                                            <th class="text-right">
                                                <?= __tr('Disponible en stock') ?>
                                            </th>
                                            <th>
                                                <?= __tr('Action') ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-if="InventoryListCtrl.isArray(value)" ng-repeat="value in combination">
                                            <td>
                                                [[ value.product_id ]]
                                            </td>
                                            <td>
                                                [[ value.combination_title ]]
                                                <small ng-repeat="option in value.combinations">
                                                    - <strong>[[ option.label_name ]]</strong>: [[ option.value_name ]]
                                                </small>
                                            </td>
                                            <td class="text-right">
                                                [[ value.formattedPrice ]]
                                            </td>
                                            <td class="text-right">
                                                [[ value.formattedSalePrice ]]
                                            </td>
                                            <td class="text-right">
                                                <strong>
                                                    <a href
                                                        ng-click="InventoryListCtrl.getInventoryTransaction(inventory.productId, inventory.productTitle, value.combination_id, 1, value.location_id)">
                                                        [[ value.totalStock ]]
                                                    </a>
                                                </strong>
                                            </td>
                                            <td>
                                                <div
                                                    ng-if="value.location_status != 1 || inventory.productStatus != 1 || inventory.categoryStatus != 1 || value.status == 3">
                                                    <i class="fa fa-exclamation-triangle"
                                                        title="<?= __tr('Location, Category, Product may not available.') ?>"></i>
                                                </div>
                                                <button
                                                    ng-if="value.location_status == 1 && inventory.productStatus == 1 && inventory.categoryStatus == 1 && value.status != 3"
                                                    type="button" class="btn btn-success btn-sm"
                                                    ng-click="InventoryListCtrl.updateInventory(inventory.productId, inventory.productTitle, value.combination_id, value.location_id, '1', value.supplier_id)">
                                                    <?= __tr('Entrada') ?>
                                                </button>

                                                <button type="button" class="btn btn-default btn-sm"
                                                    ng-click="InventoryListCtrl.getInventoryTransaction(inventory.productId, inventory.productTitle, value.combination_id, 1, value.location_id)">
                                                    <?= __tr('Transacciones') ?>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card mb-4" ng-if="InventoryListCtrl.invetoryData.length == 0">
                            <div class="card-body">
                                <?= __tr('No Inventories Found.') ?>
                            </div>
                        </div>
                    </div>

                    <!-- pagination -->
                    <div class="pull-right lw-pagination-container float-right" ng-show="InventoryListCtrl.pageContentLoaded && InventoryListCtrl.paginationLinks">
                    </div>
                    <!-- /pagination -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <?= __tr('Close') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Function to open the inventory modal
function openInventoryModal() {
    // Ensure Angular controller is initialized
    angular.element(document.body).injector().invoke(function($controller, $rootScope) {
        // Create a new scope for the modal
        var $scope = angular.element(document.getElementById('inventoryModal')).scope();
        
        // Initialize the InventoryListController if not already initialized
        if (!$scope.InventoryListCtrl) {
            $scope.InventoryListCtrl = $controller('InventoryListController', {$scope: $scope});
        }
        
        // Trigger initial data load
        $scope.$apply(function() {
            $scope.InventoryListCtrl.getInventories();
        });
        
        // Open the modal
        $('#inventoryModal').modal('show');
    });
}

// Optional: Add a button to trigger the modal
// <button onclick="openInventoryModal()" class="btn btn-primary">Manage Inventory</button>
</script>
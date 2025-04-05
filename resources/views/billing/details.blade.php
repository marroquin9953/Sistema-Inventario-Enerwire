<?php   
/*
*  Component  : Billing
*  View       : Billing Controller
*  Engine     : BillingEngine  
*  File       : billing.add.blade.php  
*  Controller : BillingDetailsController as billingDetailsCtrl 
----------------------------------------------------------------------------- */
?>
<div class="lw-section-heading-block">
    <!-- main heading -->
    <h3 class="lw-section-heading">
        <div class="lw-heading">
            <?= __tr('Detalles de la factura') ?>
        </div>
    </h3>
    <!-- /main heading -->
</div>
<div class="container">
    <div class="row mb-4">
        <div class="col col-lg-12">
            
            <a title="<?= __tr('Download as Pdf') ?>" ng-show="canAccess('manage.billing.read.download_pdf')" class="lw-btn btn btn-sm btn-secondary float-right ml-2" href="[[ billingDetailsCtrl.downloadPdfUrl ]]">
            <i class="fa fa-download"></i> <?= __tr('Descargar como PDF') ?> </a>

            <a title="<?= __tr('Print') ?>" ng-show="canAccess('manage.billing.read.download_pdf')" class="lw-btn btn btn-sm btn-secondary float-right ml-2" target="_blank" href="[[ billingDetailsCtrl.printUrl ]]">
            <i class="fa fa-print"></i> <?= __tr('Imprimir') ?> </a>

            <a title="<?= __tr('Go Back') ?>" ui-sref="billing" class="lw-btn btn btn-sm btn-secondary float-right">
            <i class="fa fa-reply"></i> <?= __tr('Volver') ?> </a>

        </div>
    </div>

    <div class="row justify-content-md-center">
        <div class="col col-lg-12">
            <div class="card">
                <div class="card-body lw-box">
                    <div class="lw-ribbon-wrapper">
                        <span ng-class="{'lw-draft-bill' : billingDetailsCtrl.billingData.bill_status == 1}" ng-bind="billingDetailsCtrl.billingData.formatted_status"></span>
                    </div>
                    <form class="ui form lw-form lw-ng-form" name="billingDetailsCtrl.[[billingDetailsCtrl.ngFormName]]" novalidate>
                        <p class="card-text">
                            <!-- Logo Image -->
                            <a class="lw-item-link float-right mt-4" ng-show="manageCtrl.isLoggedIn()" ui-sref="dashboard"><img src="<?=  getConfigurationSettings('logo_image_url')  ?>" alt=""></a>
                            <!-- /Logo Image -->

                            <!-- Store Name -->
                            <strong><?= getConfigurationSettings('name') ?></strong>                        
                            <!-- /Store Name -->
                            <div>[[ billingDetailsCtrl.userData.fullName ]]</div>
                            <div>[[ billingDetailsCtrl.userData.address1 ]]</div>
                            <div>[[ billingDetailsCtrl.userData.address2 ]]</div>
                            <div>[[ billingDetailsCtrl.userData.country ]]</div>
                        </p>
                        <div class="row">
                            <div class="col-lg-6 float-left mt-4">
                                <h5>
                                    <div>[[ billingDetailsCtrl.billingData.customerDetails.name ]]</div>
                                    <div>[[ billingDetailsCtrl.billingData.customerDetails.description ]]</div>
                                    <div>[[ billingDetailsCtrl.billingData.customerDetails.country ]]</div>
                                </h5>
                            </div>
                            <div class="col-lg-6">
                                <table class="table lw-billing-meta-detail-table table-bordered table-sm w-100">
                                    <tr>
                                        <td><?= __tr('Fecha de Documento') ?></td>
                                        <td>
                                            <!-- Bill Date -->
                                            [[ billingDetailsCtrl.billingData.bill_date ]]
                                            <!-- /Bill Date -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?= __tr('Fecha de vencimiento') ?></td>
                                        <td>
                                            <!-- Due Date -->
                                            [[ billingDetailsCtrl.billingData.due_date ]]
                                            <!-- /Due Date -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?= __tr('Documento No.') ?></td>
                                        <td>
                                            <!-- Bill No -->
                                            [[ billingDetailsCtrl.billingData.bill_number ]]
                                            <!-- /Bill No -->
                                        </td>
                                    </tr>
                                    <tr ng-if="billingDetailsCtrl.billingData.txn_id">
                                        <td><?= __tr('Transacción') ?></td>
                                        <td>
                                            <!-- Txn -->
                                            [[ billingDetailsCtrl.billingData.txn_id ]]
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
                                        <th width="40%"><?= __tr('Combinación de productos') ?></th>
                                        <th align="right" width="15%"><?= __tr('Precio unitario') ?></th>
                                        <th align="right" width="15%"><?= __tr('Cantidad') ?></th>
                                        <th align="right" width="15%"><?= __tr('Precio') ?></th>
                                        <th colspan="2" align="right" width="15%"><?= __tr('Tax') ?></th>
                                    </tr>
                                </thead>
                                <tbody  class="lw-selectize-parent">
                                    <tr ng-repeat="(index, productCombination) in billingDetailsCtrl.billingData.productCombinations" class="lw-combination-row-[[index]]">
                                        <td>
                                            <span ng-show="billingDetailsCtrl.billingData.productCombinations[index]['showDetails']">
                                                <strong>[[ billingDetailsCtrl.billingData.productCombinations[index]['combination']['name'] ]]</strong>
                                                <span ng-if="billingDetailsCtrl.billingData.productCombinations[index]['combination']['combinations'].length > 0">
                                                    (<small ng-repeat="combo in billingDetailsCtrl.billingData.productCombinations[index]['combination']['combinations']">
                                                        <strong>[[ combo.labelName ]]: </strong>[[ combo.valueName ]]<span ng-if="!$last">,</span>
                                                    </small>)
                                                </span><br>
                                                <small class="text-secondary">
                                                    <?= __tr('SKU') ?>: [[ billingDetailsCtrl.billingData.productCombinations[index]['combination']['comboSKU'] ]]
                                                </small>
                                                <div>
                                                    <small class="text-secondary">
                                                        <?= __tr('Combination') ?>: [[ billingDetailsCtrl.billingData.productCombinations[index]['combination']['combinationTitle'] ]]
                                                    </small>
                                                </div>
                                            </span>
                                        </td>
                                        <td align="right" class="lw-middile-align">
                                            [[ productCombination.formattedUnitPrice ]]
                                        </td>
                                        <td class="text-center lw-middile-align">
                                            <!-- Quantity -->
                                            [[ billingDetailsCtrl.billingData.productCombinations[index]['quantity'] ]]
                                            <!-- /Quantity -->
                                        </td>
                                        <td align="right" class="lw-middile-align">
                                            <div class="lw-billing-amount">
                                                [[ productCombination.formattedPrice ]]
                                            </div>
                                        </td>
                                        <td colspan="2">
                                            <div>
	                                        	<table class="table table-borderless table-sm">
													<tbody>
														<tr ng-repeat="tax_d in billingDetailsCtrl.billingData.productCombinations[index].tax_details">
															<td>
																<span ng-bind="tax_d.title"></span>
																<span ng-if="tax_d.type == 2">([[tax_d.tax_amount]]%)</span>
															</td>
															<td class="text-right">[[billingDetailsCtrl.currencySymbol]][[tax_d.tax_amount_on_product]]</td>
														</tr>
													</tbody>
												</table>
	                                        </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" align="right">
                                            <h5><?= __tr('Total del producto') ?></h5>
                                        </td>
                                        <td align="right">
                                            <div class="lw-billing-amount">
                                                [[ billingDetailsCtrl.formattedUnitPriceTotal ]]
                                            </div>
                                        </td>
                                        <td align="right">
                                            <h5><?= __tr('Total de impuestos') ?></h5>
                                        </td>
                                        <td align="right">
                                            <div class="lw-billing-amount">
                                                [[ billingDetailsCtrl.formattedTaxTotalAmount ]]
                                            </div>
                                        </td>
                                    </tr>
                                    <tr ng-if="billingDetailsCtrl.billingData.is_add_tax">
                                        <td colspan="3" align="right">
                                            <strong><?= __tr('Impuesto') ?> : </strong> 
                                            <span ng-if="billingDetailsCtrl.billingData.tax_type == 2">
                                                [[ billingDetailsCtrl.currencySymbol ]]
                                            </span>
                                            [[ billingDetailsCtrl.billingData.tax ]]
                                            <span ng-if="billingDetailsCtrl.billingData.tax_type == 1">
                                                %
                                            </span>
                                            <div>
                                                <!-- Tax Description -->
                                                [[ billingDetailsCtrl.billingData.tax_description ]]
                                                <!-- /Tax Description -->
                                            </div>                                                
                                        </td>
                                        <td colspan="3" align="right">
                                            <div class="lw-billing-amount" ng-if="billingDetailsCtrl.billingData.is_add_tax">
                                                + [[ billingDetailsCtrl.billingData.formatted_tax_amount ]]
                                            </div>
                                        </td>
                                    </tr>
                                    <tr ng-if="billingDetailsCtrl.billingData.is_add_discount">
                                        <td colspan="3" align="right">
                                            <strong><?= __tr('Descuento') ?> : </strong> 
                                            <span ng-if="billingDetailsCtrl.billingData.discount_type == 2">
                                                [[ billingDetailsCtrl.currencySymbol ]]
                                            </span>
                                            [[ billingDetailsCtrl.billingData.discount ]]
                                            <span ng-if="billingDetailsCtrl.billingData.discount_type == 1">
                                                %
                                            </span>

                                            <div>
                                                <!-- Discount Description -->
                                                [[ billingDetailsCtrl.billingData.discount_description ]]
                                                <!-- /Discount Description -->
                                            </div>
                                        </td>
                                        <td colspan="3" align="right">
                                            <div class="lw-billing-amount" ng-if="billingDetailsCtrl.billingData.is_add_discount">
                                                - [[ billingDetailsCtrl.billingData.formatted_discount_amount ]]
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" align="right">
                                            <h4><?= __tr('Monto Total') ?></h4>
                                        </td>
                                        <td colspan="3" align="right">
                                            <div class="lw-billing-amount">
                                                <h4>[[ billingDetailsCtrl.formattedTotalAmount ]]</h4>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
        <small>
            (<% _.forEach(__tData.item.combinations, function(combo) { %>            
                <%= combo.labelName %> : <%= combo.valueName %>            
            <% }) %>)
        </small>        
    </div>
</script>
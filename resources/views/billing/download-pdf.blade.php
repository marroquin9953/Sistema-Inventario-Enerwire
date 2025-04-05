<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
    <title>Factura</title>
    <style type="text/css">
        body {
            font-family: 'roboto', 'DejaVu Sans', sans-serif;
        }
        #page-wrap {
            width: 730px;
            margin: 0 auto;
        }
        .center-justified {
            text-align: justify;
            margin: 0 auto;
            width: 30em;
        }
        table.outline-table {
            border: 1px solid;
            border-spacing: 0;
        }
        tr.border-bottom td, td.border-bottom {
            border-bottom: 1px solid;
        }
        tr.border-top td, td.border-top {
            border-top: 1px solid;
        }
        tr.border-right td, td.border-right {
            border-right: 1px solid;
        }
        tr.border-right td:last-child {
            border-right: 0px;
        }
        tr.center td, td.center {
            text-align: center;
            vertical-align: text-top;
        }
        td.pad-left {
            padding-left: 5px;
        }
        tr.right-center td, td.right-center {
            text-align: right;
            padding-right: 50px;
        }
        tr.right td, td.right {
            text-align: right;
        }
        .grey {
            background:grey;
        }
        .lw-column-text-font {
            font-size: 15px;
        }
        .lw-row-text-font {
            font-size: 13px;
        }
    </style>
    </head>
    <body>
        <div id="page-wrap">
            <table width="100%">
                <tbody>
                    <tr>
                        <td width="30%">
                            <img src="<?=  getConfigurationSettings('logo_image_url')  ?>"> <!-- your logo here -->
                        </td>
                        <td width="70%" align="right">
                            <h2>Factura</h2><br>
                            <strong>Fecha:</strong> <?php echo date('Y-m-d');?><br>
                            <strong>No. de Factura:</strong> <?= $billData['updateData']['bill_number'] ?><br>
                            <strong>Fecha de Factura:</strong> <?= $billData['updateData']['bill_date'] ?><br>
                            <strong>Fecha de Vencimiento:</strong> <?= $billData['updateData']['due_date'] ?><br>
                            @if(isset($billData['updateData']['txn_id']) and !__isEmpty($billData['updateData']['txn_id']))
                            	<strong>Transacción:</strong> <?= $billData['updateData']['txn_id'] ?><br>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="center-justified">
                                <?= $billData['updateData']['customerInfo']['name'] ?> <br>
                                <?= $billData['updateData']['customerInfo']['country_name'] ?><br>
                                Monto de Factura: <?= $billData['updateData']['formattedTotalAmount'] ?>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
          <br>
            <table width="100%" class="outline-table">
                <tbody>
                    <tr class="border-bottom border-right grey">
                        <td colspan="8"><strong>Resumen</strong></td>
                    </tr>
                    <tr class="border-right center lw-column-text-font">
                        <td width="30%"><strong>Combinación de Producto</strong></td>
                        <td width="15%"><strong>Precio Unitario</strong></td>
                        <td width="10%"><strong>Cantidad</strong></td>
                        <td width="15%"><strong>Precio</strong></td>
                        <td colspan="2" width="30%"><strong>Impuesto del Producto</strong></td>
                    </tr>
                    @foreach($billData['updateData']['productCombinations'] as $product)
                        <tr class="border-right lw-row-text-font">
                            <td class="pad-left border-top">
                                <strong><?= $product['combination']['name'] ?></strong><br>
                                @if(!__isEmpty($product['combination']['combinations']))
                                    (@foreach($product['combination']['combinations'] as $combination)
                                        <strong><?= $combination['labelName'] ?>: </strong> <?= $combination['valueName'] ?> 
                                    @endforeach)<br>
                                @endif
                                
                                SKU: <?= $product['combination']['comboSKU'] ?><br>
                                Combinación: <?= $product['combination']['combinationTitle'] ?><br>
                            </td>
                            <td class="right border-top">
                                <?= $product['formattedUnitPrice'] ?>
                            </td>
                            <td class="right border-top">
                                <?= $product['quantity'] ?>
                            </td>
                            <td class="right border-top">
                                <?= $product['formattedPrice'] ?>
                            </td>
                            <td colspan="4" class="border-top">
                            	<table class="table table-borderless table-sm" width="100%">
									@if(!__isEmpty($product['formattedTaxDetails']))
										@foreach($product['formattedTaxDetails'] as $taxD)
											<tr align="center">
												<td><?= $taxD['title'] ?></td>
												<td align="right">
													<?= $taxD['amount'] ?>
												</td>
											</tr>
									 	@endforeach
									@endif
								</table>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="border-top lw-row-text-font">
                        <td  align="right" colspan="3">
                            <strong>Total de Productos</strong>
                        </td>
                        <td align="right">
                            <?= $billData['updateData']['calculatedTotalUnitPrice'] ?>
                        </td>
                        <td align="right">
                            <strong>Total de Impuestos: </strong>
                        </td>
                        <td align="right" colspan="3">
                            <?= $billData['updateData']['calculatedTotalProductTax'] ?>
                        </td>
                    </tr>
                    @if($billData['updateData']['is_add_tax'])
                        <tr class="border-top lw-row-text-font">
                            <td colspan="4" align="right">
                                <strong>Impuesto: </strong>
                                @if($billData['updateData']['tax_type'] == 2)
                                    <?=  $billData['currencySymbol'] ?>
                                @endif
                                <?= $billData['updateData']['tax'] ?>
                                @if($billData['updateData']['tax_type'] == 1)
                                    %
                                @endif :<br>
                                <?= $billData['updateData']['tax_description'] ?>
                            </td>
                            <td colspan="4" align="right">
                            	<?= $billData['updateData']['formattedCustomTax'] ?>
                            </td>
                        </tr>
                    @endif
                    @if($billData['updateData']['is_add_discount'])
                        <tr class="border-top lw-row-text-font">
                            <td colspan="4" align="right">
                                <strong>Descuento: </strong>
                                @if($billData['updateData']['discount_type'] == 2)
                                  <?= $billData['currencySymbol'] ?>
                                @endif
                                <?= $billData['updateData']['discount'] ?>
                                @if($billData['updateData']['discount_type'] == 1)
                                    %
                                @endif :<br>
                                <?= $billData['updateData']['discount_description'] ?>
                            </td>
                            <td  colspan="4" align="right">
                            	<?= $billData['updateData']['formattedDiscount'] ?>
                            </td>
                        </tr>
                    @endif
                    <tr class="border-top lw-row-text-font">
                        <td colspan="4" align="right">
                            <h2>Monto Total : </h2>
                        </td>
                        <td colspan="4" align="right">
                            <h2><?= $billData['updateData']['formattedTotalAmount'] ?></h2>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html> 
<script type="text/javascript">
    window.print();

    var divToPrint=document.getElementById('lwPrintPreview');

    $('<iframe>', {
        name: 'myiframe',
        class: 'lwPrintFrame'
    }).appendTo('body').contents().find('body').append(divToPrint.innerHTML);

    window.frames['myiframe'].focus();
    window.frames['myiframe'].print();

    setTimeout(() => { $(".printFrame").remove(); }, 1000)
</script>
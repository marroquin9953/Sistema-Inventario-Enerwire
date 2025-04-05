<?php
/*
*  Component  : Configuration
*  View       : currency
*  Engine     : ConfigurationEngine  
*  File       : currency.blade.php  
*  Controller : CurrencyConfigurationDialogController 
----------------------------------------------------------------------------- */ 
?>
<div>
    
    <!-- /error notification -->
    <div class="col-md-8 col-xs-12 offset-md-2 lw-login-form-box shadow p-4 border">
        <div class="lw-section-heading-block">
        <!--  main heading  -->
            <h3 class="lw-section-heading"> 
            <div class="lw-heading"><?=  __tr( 'Currency Settings' )  ?></div></h3>
        <!--  /main heading  -->
    </div>


    <!-- Loading (remove the following to stop the loading)-->
    <div class="overlay" ng-if="currencyConfigurationCtrl.pageStatus == false">
       <div class="loader"></div>
    </div>
    <!-- end loading -->

    <div ng-include src="'lw-settings-update-reload-button-template.html'"></div>

    <input type="hidden" id="lwCurrencySettingTxtMsg" other-text="<?= __tr( 'Other') ?>">

    <form class="lw-form lw-ng-form" name="currencyConfigurationCtrl.[[ currencyConfigurationCtrl.ngFormName ]]" 
    ng-submit="currencyConfigurationCtrl.submit()" 
    novalidate>

        <fieldset class="lw-fieldset-2">
            <legend class="lw-fieldset-legend-font"><?= __tr('Base Currency') ?></legend>
                 <!-- Currency -->
                <div class="form-group">
                    <lw-form-selectize-field field-for="currency" label="<?= __tr( 'Currency' ) ?>" class="lw-selectize">
                        <selectize config='currencyConfigurationCtrl.currencies_select_config' class="lw-form-field" name="currency" ng-model="currencyConfigurationCtrl.editData.currency" options='currencyConfigurationCtrl.currencies_options' placeholder="<?= __tr( 'Select Currency' ) ?>" ng-required="true"  ng-change="currencyConfigurationCtrl.currencyChange(currencyConfigurationCtrl.editData.currency)"></selectize>
                    </lw-form-selectize-field>
                </div>
                <!-- /Currency -->
                
                <div class="form-row">
                    <div class="col">
                        <!-- Currency Value -->
                        <lw-form-field field-for="currency_value" label="<?= __tr( 'Currency Code' ) ?>"> 
                            <input type="text" 
                                class="lw-form-field form-control"
                                name="currency_value"
                                ng-required="true"
                                ng-change="currencyConfigurationCtrl.currencyValueChange(currencyConfigurationCtrl.editData.currency_value)" 
                                ng-model="currencyConfigurationCtrl.editData.currency_value"/>
                        </lw-form-field>
                        <!-- Currency Value -->
                    </div>

                    <div class="col">
                    <!-- Currency Symbol -->
                        <lw-form-field field-for="currency_symbol" label="<?= __tr( 'Currency Symbol' ) ?>"> 
                            <div class="input-group">
                                <input type="text" 
                                  class="lw-form-field form-control"
                                  name="currency_symbol"
                                  ng-required="true" 
                                  ng-model="currencyConfigurationCtrl.editData.currency_symbol"
                                  ng-change="currencyConfigurationCtrl.updateCurrencyPreview(currencyConfigurationCtrl.editData.currency_symbol, currencyConfigurationCtrl.editData.currency_value)" />
                                  <div class="input-group-append" id="basic-addon1">
                                        <span class="input-group-text" ng-bind-html="currencyConfigurationCtrl.editData.currency_symbol"></span>
                                    </div>
                                </div>
                        </lw-form-field>
                    <!-- Currency Symbol -->
                    </div>
                </div>

                <span ng-show="currencyConfigurationCtrl.isZeroDecimalCurrency">
                    <!-- Round Zero Decimal Currency -->
                    <lw-form-checkbox-field field-for="round_zero_decimal_currency" label="<?= __tr( 'Round Zero Decimal Currency' ) ?>" advance="true">
                        <input type="checkbox" 
                            class="lw-form-field js-switch"
                            name="round_zero_decimal_currency"
                            ng-model="currencyConfigurationCtrl.editData.round_zero_decimal_currency"
                            ui-switch="" />
                    </lw-form-checkbox-field>
                    <!-- /Round Zero Decimal Currency -->

                    <div class="alert alert-warning" ng-show="currencyConfigurationCtrl.editData.round_zero_decimal_currency">
                        <?= __tr('All the price and amount will be rounded. Eg : 10.25 It will become 10 , 10.57 It will become 11.') ?>
                    </div>

                    <div class="alert alert-danger" ng-show="!currencyConfigurationCtrl.editData.round_zero_decimal_currency">
                        <i class="fa fa-exclamation-triangle"></i>  <?= __tr("This currency doesn't support Decimal values it may create error at payment.") ?>
                    </div>

                </span>

                <span class="pull-right"><?= __tr('Refer for') ?> <a href="http://goo.gl/zRJRq" target="_blank"><?= __tr('ASCII Codes') ?></a></span>
        </fieldset>

        <div class="alert alert-info">
            <?= __tr("Items in the curly braces are application variables.") ?>
        </div>

        <!-- Currency Format -->
        <lw-form-field field-for="currency_format" label="<?= __tr( 'Currency Format' ) ?>"> 
            <div class="input-group">
                <input type="text" 
                    class="lw-form-field form-control"
                    name="currency_format"
                    ng-required="true" 
                    ng-model="currencyConfigurationCtrl.editData.currency_format"
                    ng-keyup="currencyConfigurationCtrl.updateCurrencyPreview(currencyConfigurationCtrl.currencySymbol, currencyConfigurationCtrl.editData.currency_value)"/>
                    <div class="input-group-append" id="basic-addon1">
                        <span 
                            class="input-group-text" 
                            data-format="[[currencyConfigurationCtrl.editData.currency_format]]"
                            title="<?= __tr('This is currency format preview, Which is display in publically.') ?>" 
                            id="lwCurrencyFormat" ng-bind-html="currencyConfigurationCtrl.currency_format_preview">
                        </span>
                        <span class="input-group-text">
                          <a href ng-click="currencyConfigurationCtrl.useDefaultFormat(currencyConfigurationCtrl.default_currency_format, currencyConfigurationCtrl.currencySymbol, currencyConfigurationCtrl.editData.currency_value)" title="<?= __tr('Reset this format and use default format') ?>"><?= __tr('Use Default') ?></a></span>
                    </div>
                </div>
        </lw-form-field>
        <!-- Currency Format -->
        <div class="form-group">
             <button type="submit" class="btn btn-primary lw-btn" title="<?= __tr('Update') ?>">
                <?= __tr('Update') ?></button>
        </div>

    </form>


</div>

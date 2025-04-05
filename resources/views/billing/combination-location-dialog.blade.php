<?php 
/*
*  Component  : Billing
*  View       : Billing Controller
*  Engine     : BillingEngine  
*  File       : combination-location-dialog.blade.php
*  Controller : CombinationLocationController as CombinationLocationCtrl
----------------------------------------------------------------------------- */
?>
<div>

    <!-- Modal Heading -->
    <div class="modal-header">
        <h3 class="modal-title">
            <?= __tr('Choose Location') ?>
        </h3>
    </div>
    <!-- /Modal Heading -->

    <!-- Modal Body -->
    <div class="modal-body">
        <table class="table table-striped lw-table-noborder-first-row">
            <tbody>
                <tr>
                  <td scope="row">Product</td>
                  <td ng-bind="CombinationLocationCtrl.productData.name"></td>
                </tr>
                <tr>
                  <td scope="row">Combination name</td>
                  <td>
                  	<div ng-bind="CombinationLocationCtrl.productData.combinationTitle"></div>
                    <span ng-if="CombinationLocationCtrl.productData.combinations.length > 0">
                        (<small ng-repeat="combo in CombinationLocationCtrl.productData.combinations">
                            <strong>[[ combo.labelName ]]: </strong>[[ combo.valueName ]]<span ng-if="!$last">,</span>
                        </small>)
                    </span>
                  </td>
                </tr>
                <tr>
                  <td scope="row">Combo SKU</td>
                  <td ng-bind="CombinationLocationCtrl.productData.comboSKU"></td>
                </tr> 
          </tbody>
        </table>

        <h6 class="lw-text-divider">
            <span><?= __tr('Locations') ?></span>
        </h6>

		<div class="row">
			<div class="col-lg-4" ng-repeat="location in CombinationLocationCtrl.productData.stockByLocations">
				<div class="custom-control custom-radio">
					<input type="radio" id="location__[[location.location_id]]" ng-change="CombinationLocationCtrl.changeLocation(CombinationLocationCtrl.selected_location)" ng-value="[[location.location_id]]" ng-model="CombinationLocationCtrl.selected_location" name="stock_location" class="custom-control-input">
					<label class="custom-control-label" for="location__[[location.location_id]]">
						<span ng-bind="location.location"></span>
						<span>
							(qty : <span ng-bind="location.quantity"></span>)
						</span>
					</label>
				</div>
			</div>
		</div>
        <!-- /options -->
    </div>
    <!-- /Modal Body -->

    <!-- Modal footer -->
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-disabled="true" ng-if="!CombinationLocationCtrl.showLocationBtn" title="<?= __tr('Choose Location') ?>"><?= __tr('Choose Location') ?></button>
        <button type="button" class="btn btn-primary"  ng-if="CombinationLocationCtrl.showLocationBtn" ng-click="CombinationLocationCtrl.chooseLocation(CombinationLocationCtrl.selected_location)" title="<?= __tr('Choose Location') ?>"><?= __tr('Choose Location') ?></button>

        <button type="button" title="<?= __tr('Close') ?>" class="btn btn-default" ng-click="CombinationLocationCtrl.closeDialog()"><?= __tr('Close') ?></button>
    </div>
    <!-- /Modal footer -->

</div>
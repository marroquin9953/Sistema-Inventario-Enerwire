<?php
/*
*  Component  : Add Country
*  View       : Add User Country View  
*  Engine     : UserEngine.js  
*  File       : add-country.blade.php  
*  Controller : UserAddCountryController 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="UserAddCountryController as CountryCtrl">

    <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __tr( 'Add Country' ))<?=  __tr( 'Add Country' )  ?></h3>
        <!--  /main heading  -->
    </div>
    <div>
        <form class="lw-form lw-ng-form " 
            name="CountryCtrl.[[ CountryCtrl.ngFormName ]]" 
            ng-submit="CountryCtrl.submit()" 
            novalidate>

            <!-- loader -->
            <div class="lw-main-loader lw-show-till-loading" ng-if="CountryCtrl.request_completed == false">
                <div class="loader"><?=  __tr('Loading...')  ?></div>
            </div>
            <!-- / loader -->
            <div ng-if="CountryCtrl.request_completed">  

                <!-- Country -->
                <lw-form-selectize-field field-for="country" label="<?= __tr( 'Country' ) ?>" class="lw-selectize">
                    <selectize config='CountryCtrl.countrySelectConfig' class="lw-form-field" name="country" ng-model="CountryCtrl.userData.country" ng-required="true" options='CountryCtrl.countries' placeholder="<?= __tr( 'Select Country' ) ?>" ></selectize>
                </lw-form-selectize-field>
                <!-- /Country -->

                <!--  update button  -->
                <div class="form-group">
                    <button type="submit" class="lw-btn btn btn-primary" title="<?=  __tr('Add')  ?>"><?=  __tr('Add')  ?> <span></span> </button>
                </div>
                <!--  /update button  -->

            </div>

        </form>

    </div>

</div>
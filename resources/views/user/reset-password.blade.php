<?php
/*
*  Component  : User
*  View       : Reset Password  
*  Engine     : UserEngine.js  
*  File       : reset-password.blade.php  
*  Controller : UserResetPasswordController 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="UserResetPasswordController as resetPasswordCtrl">
    
    <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __tr('Reset Password'))<div class="lw-heading"><?=  __tr( 'Reset Password' )  ?></div></h3>
        <!--  /main heading  -->
    </div>
	<!--  form action  -->
    <form class="lw-form lw-ng-form col-md-6 col-xs-12 col-md-offset-3" 
        name="resetPasswordCtrl.[[ resetPasswordCtrl.ngFormName ]]" 
        ng-submit="resetPasswordCtrl.submit()" 
        novalidate>

        <!--  Email  -->
        <lw-form-field field-for="email" label="<?=  __tr( 'Email' )  ?>"> 
            <input type="email" 
              class="lw-form-field form-control"
              name="email"
              ng-required="true" 
              ng-model="resetPasswordCtrl.userData.email" />
        </lw-form-field>
        <!--  /Email  -->

        <!--  Password  -->
        <lw-form-field field-for="password" label="<?=  __tr( 'Password' )  ?>"> 
            <input type="password" 
                  class="lw-form-field form-control"
                  name="password"
                  ng-minlength="6"
                  ng-maxlength="30"
                  ng-required="true" 
                  ng-model="resetPasswordCtrl.userData.password" />
        </lw-form-field>
        <!--  /Password  -->

        <!--  Password Confirmation  -->
        <lw-form-field field-for="password_confirmation" label="<?=  __tr( 'Password Confirmation' )  ?>"> 
            <input type="password" 
                  class="lw-form-field form-control"
                  name="password_confirmation"
                  ng-minlength="6"
                  ng-maxlength="30"
                  ng-required="true" 
                  ng-model="resetPasswordCtrl.userData.password_confirmation" />
        </lw-form-field>
        <!--  /Password Confirmation  -->
		
		<!--  submit button  -->
        <div class="form-group lw-form-actions">
            <button type="submit" class="lw-btn btn btn-primary" title="<?=  __tr('Reset Password')  ?>"><?=  __tr('Reset Password')  ?></button>
        </div>
        <!--  /submit button  -->

    </form>
	<!--  /form action  -->
</div>
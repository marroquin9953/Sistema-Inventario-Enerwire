<?php
/*
*  Component  : User
*  View       : Login  
*  Engine     : UserEngine.js  
*  File       : login-dialog.blade.php  
*  Controller : UserLoginDialogController 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="UserLoginDialogController as UserLoginDialogCtrl">

	<!-- Modal Heading -->
    <div class="modal-header">
       <h3 class="modal-title"><?=  __tr( 'Login' )  ?></h3>
    </div>
    <!-- /Modal Heading -->

    <div class="alert alert-danger" ng-if="UserLoginDialogCtrl.isInActive">
       <?= __tr("Your account seems to be non-active please __link__", [
            '__link__' => "<a href='".route('get.user.contact')."'>Contact</a>"
      ]) ?>
      
    </div>

    <!-- error notification --> 
	<div ng-show="UserLoginDialogCtrl.errorMessage && UserLoginDialogCtrl.accountDeleted" class="alert alert-danger" role="alert">
		<div class="ui bottom error message animated fadeIn">
			<i class="fa fa-exclamation-circle" aria-hidden="true"></i>&nbsp;<span ng-bind="UserLoginDialogCtrl.errorMessage"></span>
		</div>
	</div>

	<div>
    	<!-- Start Form --> 
	    <form class="lw-form lw-ng-form" 
	        name="UserLoginDialogCtrl.[[ UserLoginDialogCtrl.ngFormName ]]" 
	        ng-submit="UserLoginDialogCtrl.submit()" 
	        novalidate>

	        <!-- Modal Body -->
	        <div class="modal-body">
	            
	            <!--  Email  -->
		        <lw-form-field field-for="emailOrUsername" label="<?=  __tr( 'Username / Email' )  ?>"> 
		            <input type="text" 
		              	class="lw-form-field form-control"
		              	name="emailOrUsername"
		              	ng-required="true" 
						placeholder="<?= __tr('Enter username / email') ?>" 
		              	ng-model="UserLoginDialogCtrl.loginData.emailOrUsername" />
		        </lw-form-field>
		        <!--  Email  -->

		        <!--  Password  -->
		        <lw-form-field field-for="password" label="<?=  __tr( 'Password' )  ?>">
		            <div class="input-group mb-3">
		                <input type="password" 
		                  	class="lw-form-field form-control"
		                  	name="password"
		                  	ng-minlength="6"
		                  	ng-maxlength="30"
		                  	ng-required="true" 
							placeholder="<?= __tr('Enter password') ?>" 
		                  	ng-model="UserLoginDialogCtrl.loginData.password" />
		                <div class="input-group-append">
		                    <a class="input-group-text" href ui-sref="forgot_password" title="<?=  __tr('Forgot Password?')  ?>"><?=  __tr('Forgot Password?')  ?></a>
		                </div>
		            </div>
		            
		        </lw-form-field>
		        <!--  Password  -->

		        <div ng-if="UserLoginDialogCtrl.show_captcha == true">
		            <!--  Confirmation Code  -->
		            <lw-form-field field-for="confirmation_code" label="<?=  __tr( 'I know you are a human' )  ?>" v-label="<?=  __tr( 'Confirmation Code' )  ?>"> 
		                <div class="input-group">
		                    <span class="input-group-addon"><img ng-src="[[ UserLoginDialogCtrl.captchaURL ]]" alt=""></span>
		                    <input type="text" 
		                      class="lw-form-field form-control input-lg"
		                      name="confirmation_code"
		                      ng-required="true" 
		                      ng-model="UserLoginDialogCtrl.loginData.confirmation_code" />
		                    <span class="input-group-addon">
		                        <a href="" title="<?=  __tr('Refresh Captcha')  ?>" ng-click="UserLoginDialogCtrl.refreshCaptcha()"><i class="fa fa-refresh"></i></a>
		                    </span>
		                </div>
		            </lw-form-field>
		            <!--  Confirmation Code  -->
		        </div>
		        <div class="lw-form-inline-elements">
			        <!--  Remember me  -->
			        <lw-form-checkbox-field field-for="remember_me" label="<?=  __tr( 'Remember Me' )  ?>" class="lw-margin-link lw-contain-remember-me-link">
			            <input type="checkbox" 
			                class="lw-form-field"
			                name="remember_me"
			                ng-model="UserLoginDialogCtrl.loginData.remember_me" />
			        </lw-form-checkbox-field>
				</div>
				<!--  button  -->

	        </div>
	        <!-- /Modal Body -->
	        
	        <!-- /Modal footer -->
	        <div class="modal-footer">
	            
	            <button type="submit" class="lw-btn btn btn-success lw-responsive-btn" title="<?=  __tr('Login')  ?>"><?=  __tr('Login')  ?> </button>

	        </div>
	        <!-- /Modal footer -->
    </form>
    <!-- / Start Form -->
</div>
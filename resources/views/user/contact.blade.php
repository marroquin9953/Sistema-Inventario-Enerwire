<?php
/*
*  Component  : User
*  View       : Contact  
*  Engine     : UserEngine.js  
*  File       : contact.blade.php  
*  Controller : UserContactController 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="UserContactController as contactCtrl">

     <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __tr( 'Contact' )) <div class="lw-heading"><?= __tr( 'Contact' ) ?></div></h3>
        <!--  /main heading  -->
    </div>

    <div ng-if="contactCtrl.requestSuccess">
        <div class="alert alert-success" role="alert"><?= __tr('Your contact request has been submitted successfully we will set back to you shortly') ?></div>
    </div>

    <div class="lw-contact-form"> 
        <div class="col-md-6 col-lg-6 col-xs-12">

            <!--  form action  -->
            <form class="lw-form lw-ng-form form-horizontal" 
                name="contactCtrl.[[ contactCtrl.ngFormName ]]" 
                ng-submit="contactCtrl.submit(1)" 
                novalidate>

                <!--  user name  -->
                <lw-form-field field-for="name" label="<?= __tr( 'Name' ) ?>"> 
                    <input type="text" 
                      class="lw-form-field form-control"
                      name="name"
                      ng-readonly="contactCtrl.isLoggedIn == true"
                      ng-required="true"
					  min="2" 
					  max="30" 
                      ng-model="contactCtrl.userData.name" />
                </lw-form-field>
                <!--  /user name  -->

                <!--  user Email  -->
                <lw-form-field field-for="email" label="<?= __tr( 'Email' ) ?>"> 
                    <input type="email" 
                      class="lw-form-field form-control"
                      name="email"
                      ng-required="true" 
                      ng-readonly="contactCtrl.isLoggedIn == true"
                      ng-model="contactCtrl.userData.email" />
                </lw-form-field>
                <!--  /user Email  -->

                <!--  subject  -->
                <lw-form-field field-for="subject" label="<?= __tr( 'Subject' ) ?>"> 
                    <input type="text" 
                      class="lw-form-field form-control"
                      name="subject"
                      ng-required="true" 
                      ng-model="contactCtrl.userData.subject" />
                </lw-form-field>
                <!--  /subject  -->
				
				<div ng-if="contactCtrl.showCaptcha == true">
	               	<!--  Confirmation Code  -->
	               	<lw-form-field field-for="confirmation_code"
	                    label="<?=  __tr( 'I know you are a human' )  ?>"
	                    v-label="<?=  __tr( 'Confirmation Code' )  ?>">
	                    <div class="input-group">
                         	<span class="input-group-addon">
	                              	<img ng-src="[[ contactCtrl.captchaURL ]]" alt="">
                         	</span>
                         	<input type="text"
	                              class="lw-form-field form-control input-lg"
	                              name="confirmation_code"
	                              ng-required="true"
	                              ng-model="contactCtrl.userData.confirmation_code" />
                         	<span class="input-group-addon">
	                              	<a href="" title="<?=  __tr('Refresh Captcha')  ?>" ng-click="contactCtrl.refreshCaptcha()"><i class="fa fa-refresh"></i></a>
                         	</span>
	                    </div>
	               	</lw-form-field>
	               	<!--  Confirmation Code  -->
	          </div>

                <!--  message Description  -->
                    <lw-form-field field-for="message" label="<?= __tr('Message') ?>"> 
                        <textarea name="message" class="lw-form-field form-control"
                         cols="10" rows="3" ng-required="true" ng-model="contactCtrl.userData.message" lw-ck-editor additional-options="contactCtrl.AdditionalOptionOfCkeditor" limited-options="true"></textarea>
                    </lw-form-field>
                <!--  /message Description  -->
                
                <!--  submit button  -->
                <div class="lw-form-actions">
                    <button type="submit" class="btn btn-primary" title="<?= __tr('Submit') ?>"><?= __tr('Submit') ?></button>
                </div>
                <!--  /submit button  -->
            </form>
            <!--  form action  -->
        </div>
        <div class="col-md-5 col-lg-5 col-xs-12 pull-right">
            
            @if(getConfigurationSettings('show_contact_info_on_contact_page') && getConfigurationSettings('contact_address'))
                <div class="panel panel-default">
                <div class="panel-heading"><?= __tr('Address') ?></div>
                <div class="panel-body">
                     <?= getConfigurationSettings('contact_address') ?>
                </div>
                </div>
            @endif
        </div>
    </div>
<!--  load ck editor js file  -->
<script src="<?= __yesset('dist/ckeditor/ckeditor*.js') ?>"></script>
 </div>
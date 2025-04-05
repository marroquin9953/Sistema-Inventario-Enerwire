<?php
/*
*  Component  : User
*  View       : Profile  
*  Engine     : CommonUserEngine.js  
*  File       : profile.blade.php  
*  Controller : UserProfileController 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="UserProfileController as profileCtrl" class="">
    <div class="lw-section-heading-block"> 
        <!--  main heading  -->
        <h3 class="lw-section-heading"> @section('page-title',  __tr( 'Profile' ))<div class="lw-heading"><?= __tr( 'Profile' ) ?></div></h3>
        <!--  /main heading  -->
    </div>
	<div ng-if="profileCtrl.request_completed" class="form-horizontal col-lg-6 col-md-8 col-sm-12 col-xs-12">

		<!-- thumbnail -->
        <div class="form-group">
			<div class="lw-thumb-logo">
	        	<a href="[[ profileCtrl.existingProfilePictureURL ]]" lw-ng-colorbox class="lw-thumb-logo"><img  ng-src="[[ profileCtrl.existingProfilePictureURL ]]" alt=""></a>
	        </div>
        </div>
		<!-- /thumbnail -->
		
		<!--  first name  -->
        <div class="form-group ">
	      	<label for="fname" class="control-label"><?= __tr('First Name') ?></label>
		      <input readonly type="text" class="form-control" id="fname" value="[[ profileCtrl.profileData.first_name ]]">
	    </div>
	    <!--  first name  -->

	    <!--  last name  -->
		<div class="form-group">
	      	<label  for="lname" class="control-label"><?= __tr('Last Name') ?></label>
		    <input readonly type="text" class="form-control" id="lname" value="[[ profileCtrl.profileData.last_name ]]">
	    </div>
	    <!--  last name  -->

	    <!--  Address Line 1  -->
		<div class="form-group">
	      	<label  for="address_line_1" class="control-label"><?= __tr('Address Line 1') ?></label>
		    <input readonly type="text" class="form-control" id="address_line_1" value="[[ profileCtrl.profileData.address_line_1 ]]">
	    </div>
	    <!--  Address Line 1  -->

	    <!--  Address Line 2  -->
		<div class="form-group">
	      	<label  for="address_line_2" class="control-label"><?= __tr('Address Line 2') ?></label>
		    <input readonly type="text" class="form-control" id="address_line_2" value="[[ profileCtrl.profileData.address_line_2 ]]">
	    </div>
	    <!--  Address Line 2  -->

	    <!--  Country  -->
		<div class="form-group">
	      	<label  for="country" class="control-label"><?= __tr('Country') ?></label>
		    <input readonly type="text" class="form-control" id="country" value="[[ profileCtrl.profileData.country ]]">
	    </div>
	    <!--  Country  -->

		<!--  edit button  -->
      	<div class="form-group">
        	<a href ng-href="<?=  route('user.profile.update')  ?>" title="<?= __tr('Edit') ?>" class="btn btn-primary"><?=  __tr('Edit')  ?></a>
        </div>    
        <!--  /edit button  -->
    </div>
</div>
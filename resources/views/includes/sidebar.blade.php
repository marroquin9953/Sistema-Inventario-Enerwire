<!-- start sidebar nav --> 
<div role="navigation" class="custom-sidebar-master navbar-left col-sm-12 col-md-12 col-lg-12">
	<div class="sidebar-nav lw-sidebar-inner">
		
		{{-- This section display on mobile view --}}
		<div class="lw-smart-menu-container"> 
			<div class="panel panel-default visible-xs">
			    <div class="panel-body lw-sidebar-list-panel-body">
					<!-- Right nav -->
					<ul class="top-horizental-menu sm sm-clean sm-vertical list-group lw-sidebar-list-menu">
                        
					  	@if (canAccess('manage.app'))
							<li class="navbar-right">
								<a href="<?=  route('manage.app')  ?>" title="<?= __tr( 'Console' ) ?>">
									<i class="fa fa-cogs"></i> <?=  __tr('Console')  ?>
								</a>
							</li>
						@endif

						<!-- Menu List -->
						@if (isLoggedIn())
					      	<li>
					            <a href class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
									<i class="fa fa-user"></i> <span ng-bind="publicCtrl.auth_info.profile.full_name"></span> 
								</a>
						        <ul>
						          	<li class="">
										<a href="<?=  route('user.profile')  ?>" title="<?=  __tr('Profile')  ?>"><?=  __tr('Profile')  ?></a>
									</li>
									<li class="">
										<a href="<?=  route('user.change_password')  ?>" title="<?=  __tr('Change Password')  ?>"><?=  __tr('Change Password')  ?></a>
									</li>
									<li class="">
									<a href="<?=  route('user.change_email')  ?>" title="<?= __tr('Change Email') ?>"><?= __tr('Change Email') ?></a>
									</li>
									<li class="">
										<a href="<?=  route('my_invoices.list')  ?>" title="<?=  __tr('My Invoices')  ?>"><?=  __tr('My Invoices')  ?></a>
									</li>
					                <li class="">
										<a href="<?=  route('user.logout')  ?>" title="<?= __tr('Logout') ?>"><?= __tr('Logout') ?> <i class="fa fa-sign-out"></i></a>
									</li>
						        </ul>
					      	</li>
					  	@endif
					  	<!-- /Menu List -->
					</ul>
				</div>
			</div>
		</div>
		{{--/ This section display on mobile view --}}

	</div>
</div>
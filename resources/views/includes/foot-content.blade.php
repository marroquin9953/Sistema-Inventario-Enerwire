    <!--[if lte IE 9]>
        <script src="//cdnjs.cloudflare.com/ajax/libs/Base64/0.3.0/base64.min.js"></script>
    <![endif]-->

    <!-- required for sweet alert shim -->
    <!--[if lte IE 11]>
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill@7.1.0/dist/promise.min.js"></script>
    <![endif]-->

	<!--[if gt IE 11]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
	<![endif]-->
	
    @if(getConfigurationSettings('enable_login_attempt'))
        <script src="https://www.google.com/recaptcha/api.js"></script>
    @endif

    <?= __yesset([
        'dist/js/vendorlibs-first.js',
        'dist/js/vendorlibs-jquery-ui.js',
        'dist/jquery-typeahead/dist/jquery.typeahead.min.js',
        'dist/js/vendor-second.js'
    ], true) ?>

    
    @stack('vendorScripts')
    <?= __yesset([
        'dist/js/vendorlibs-datatable-buttons.js'
    ], true) ?>
    
    <?= __yesset('dist/js/application.*.js', true) ?>  
    <?= __yesset('dist/js/common-files*.js', true) ?>

    @stack('appScripts')

<!-- container -->
<script type="text/javascript">
$(document).ready(function () {

    $('body').on('click','.lw-prevent-default-action', function(e) {
        e.preventDefault();
     });

  $('html').removeClass('lw-has-disabled-block');

    $('html body').on('click','.lw-show-process-action', function(e) {

		setTimeout(function(){ 

		    $('html').addClass('lw-has-disabled-block');

       		$('.lw-disabling-block').addClass('lw-disabled-block lw-has-processing-window');

		}, 3000);
       
    });

    $('.hide-till-load').removeClass('hide-till-load');
    $('.lw-show-till-loading').removeClass('lw-show-till-loading');
    $('.lw-main-loader').hide();
    // $('.lw-sidebar,html').toggleClass('lw-sidebar-collapsed');

    $('.lw-sidebar-collapse').on('click', function () {
	    $('.lw-sidebar,html').toggleClass('lw-sidebar-collapsed');
	});
});
</script>

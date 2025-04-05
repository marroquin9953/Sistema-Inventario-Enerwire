<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="user-scalable=1.0,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="description" property="og:description" content="@yield('description')">
<meta name="keywordDescription" property="og:keywordDescription" content="@yield('keywordDescription')">
<meta name="keywordName" property="og:keywordName" content="@yield('keywordName')">
<meta name="keyword" content="@yield('keyword')">
<meta name="title" content="@yield('page-title')">
<meta name="store" content="<?= getConfigurationSettings('name') ?>">
<style>

  h3.modal-title {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    max-height: 3.6em
  }

  .lw-page-content,
  .hide-till-load,
  .lw-main-loader {
    display: none;
  }

  .lw-main-loader {
    text-align: center;
  }

  .lw-zero-opacity {
    -webkit-opacity: 0;
    -moz-opacity: 0;
    -o-opacity: 0;
    opacity: 0;
  }

  .lw-hidden {
    display: none;
  }

  .lw-show-till-loading {
    display: block;
  }

  .loader:before,
  .loader:after,
  .loader {
    border-radius: 50%;
    width: 2.5em;
    height: 2.5em;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
    -webkit-animation: load7 1.8s infinite ease-in-out;
    animation: load7 1.8s infinite ease-in-out;
  }

  .loader {
    color: green;
    font-size: 10px;
    margin: 80px auto;
    position: relative;
    text-indent: -9999em;
    -webkit-transform: translateZ(0);
    -ms-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-animation-delay: -0.16s;
    animation-delay: -0.16s;
  }

  .loader:before {
    left: -3.5em;
    -webkit-animation-delay: -0.32s;
    animation-delay: -0.32s;
  }

  .loader:after {
    left: 3.5em;
  }

  .loader:before,
  .loader:after {
    content: '';
    position: absolute;
    top: 0;
  }

  @-webkit-keyframes load7 {

    0%,
    80%,
    100% {
      box-shadow: 0 2.5em 0 -1.3em;
    }

    40% {
      box-shadow: 0 2.5em 0 0;
    }
  }

  @keyframes load7 {

    0%,
    80%,
    100% {
      box-shadow: 0 2.5em 0 -1.3em;
    }

    40% {
      box-shadow: 0 2.5em 0 0;
    }
  }
</style>
<script>
  window.appConfig = {
    'appBaseURL' : "<?= asset('') ?>"
};
</script>

<?= __yesset([
    'dist/css/vendorlibs-manage.css',
    'dist/fontawesome/css/all.min.css',
    'dist/css/vendor-second.css',
    'dist/css/vendorlibs-first.css',
    'dist/jquery-typeahead/src/jquery.typeahead.css',
    'dist/css/vendorlibs-datatable-buttons.css'
], true) ?>

<link rel="shortcut icon" type="image/x-icon" href="<?= getConfigurationSettings('favicon_image_url') ?>">
<style type="text/css">
  .modal-title,
  .ngdialog.ngdialog-theme-default .modal-header,
  .ngdialog-close,
  .ngdialog.ngdialog-theme-default .ngdialog-close:before,
  .ngdialog.ngdialog-theme-default .ngdialog-close:hover,
  .ngdialog.ngdialog-theme-default .ngdialog-close:active:before,
  .ngdialog.ngdialog-theme-default .ngdialog-close:hover:before {
    background-color: #<?= !__isEmpty(getConfigurationSettings('selected_background_theme_color')) ? getConfigurationSettings('selected_background_theme_color'): getConfigurationSettings('header_background_color');
    ?>;
    background: #<?= !__isEmpty(getConfigurationSettings('selected_background_theme_color')) ? getConfigurationSettings('selected_background_theme_color'): getConfigurationSettings('header_background_color');
    ?> !important;
    color: #<?= !__isEmpty(getConfigurationSettings('selected_text_theme_color')) ? getConfigurationSettings('selected_text_theme_color'):getConfigurationSettings('header_text_link_color');
    ?> !important;
  }

  .navbar-nav a.nav-link,
  .navbar-default .navbar-nav>.active>a,
  .navbar-default .navbar-nav>.active>a:focus,
  .navbar-default .navbar-nav>.active>a:hover,
  .navbar-default .navbar-nav>li>a:hover,
  .navbar-default .navbar-nav>li>a:focus,
  .navbar-default .navbar-nav>.open>a,
  .navbar-default .navbar-nav>.open>a:focus,
  .navbar-default .navbar-nav>.open>a:hover,
  .lw-main-navbar {
    background: #<?= !__isEmpty(getConfigurationSettings('selected_background_theme_color')) ? getConfigurationSettings('selected_background_theme_color'): getConfigurationSettings('header_background_color');
    ?> !important;
    color: #<?= !__isEmpty(getConfigurationSettings('selected_text_theme_color')) ? getConfigurationSettings('selected_text_theme_color'):getConfigurationSettings('header_text_link_color');
    ?> !important;
  }

  .lw-change-menu-bg-color {
    background-color: #<?= !__isEmpty(getConfigurationSettings('selected_background_theme_color')) ? getConfigurationSettings('selected_background_theme_color'): getConfigurationSettings('header_background_color');
    ?> !important;
  }
</style>
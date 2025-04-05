<?php
/*
*  Component  : User
*  View       : Login
*  Engine     : UserEngine.js
*  File       : login.blade.php
*  Controller : UserLoginController
----------------------------------------------------------------------------- */
?>
<div ng-controller="UserLoginController as loginCtrl" ng-show="loginCtrl.request_completed == true" class="login-container">
    <div class="login-wrapper">
        <div class="login-brand text-center mb-4">
            <!-- Logo could be added here -->
            <h2 class="brand-text">
                <i class="fa fa-lock"></i> 
                @section('page-title',  __tr('Account Access'))
                <?= __tr('') ?>
            </h2>
        </div>

        @if(!empty(Session::get('invalidUserMessage')))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                <?= 'Session'::get('invalidUserMessage') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- error notification -->
        <div ng-show="loginCtrl.errorMessage && loginCtrl.accountDeleted" class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="ui error message animated fadeIn">
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>&nbsp;<span ng-bind="loginCtrl.errorMessage"></span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <!-- /error notification -->

        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-gradient-primary text-white text-center py-3">
                <h4 class="m-0 font-weight-bold">
                    <i class="fa fa-user-circle"></i> <?= __tr('Acceso a la cuenta') ?>
                </h4>
            </div>
            <div class="card-body p-4">
                <!--  form action  -->
                <form class="lw-form lw-ng-form"
                    name="loginCtrl.[[ loginCtrl.ngFormName ]]"
                    ng-submit="loginCtrl.submit()"
                    novalidate>

                    <!--  Email/Username  -->
                    <div class="form-group">
                        <lw-form-field field-for="emailOrUsername" label="<?= __tr('Usuario / Correo') ?>">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                </div>
                                <input type="text"
                                    class="lw-form-field form-control"
                                    name="emailOrUsername"
                                    ng-required="true"
                                    placeholder="<?= __tr('Ingrese su usuario / Correo') ?>" 
                                    ng-model="loginCtrl.loginData.emailOrUsername" />
                            </div>
                        </lw-form-field>
                    </div>
                    <!--  /Email/Username  -->

                    <!--  Password  -->
                    <div class="form-group">
                        <lw-form-field field-for="password" label="<?= __tr('Password') ?>">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                </div>
                                <input type="password"
                                    class="lw-form-field form-control"
                                    name="password"
                                    ng-minlength="6"
                                    ng-required="true"
                                    ng-maxlength="30"
                                    placeholder="<?= __tr('Enter password') ?>" 
                                    ng-model="loginCtrl.loginData.password" />
                            </div>
                        </lw-form-field>
                    </div>
                    <!--  /Password  -->
                    
                    <!-- Forgot Password Link -->
                    <div class="text-right mb-3">
                        <a class="small text-primary" ui-sref="forgot_password">
                            <i class="fa fa-question-circle"></i> <?= __tr('¿Has olvidado tu contraseña?') ?>
                        </a>
                    </div>
                    <!-- /Forgot Password Link -->

                    <!-- Captcha -->
                    <div ng-if="loginCtrl.show_captcha == true" class="form-group">
                        <lw-form-field class="lw-recaptcha" field-for="recaptcha" v-label="Captcha" label="<?= __tr('Verify you are not robot') ?>">
                            <lw-recaptcha class="lw-form-field g-recaptcha" 
                                ng-model='loginCtrl.loginData.recaptcha' 
                                name="recaptcha" 
                                sitekey="[[loginCtrl.site_key]]" ng-required="loginCtrl.show_captcha == true">
                            </lw-recaptcha>
                        </lw-form-field>
                    </div>
                    <!-- /Captcha -->

                    <!--  Remember me  -->
                    <div class="form-group">
                        <lw-form-checkbox-field
                            field-for="remember_me"
                            label="<?= __tr('') ?>"
                            class="custom-control custom-checkbox">
                            <input type="checkbox"
                                class="lw-form-field custom-control-input"
                                name="remember_me"
                                id="remember_me"
                                ng-model="loginCtrl.loginData.remember_me" />
                            <label class="custom-control-label" for="remember_me">
                                <?= __tr('Acuérdate de mí') ?>
                            </label>
                        </lw-form-checkbox-field>
                    </div>
                    <!--  /Remember me  -->

                    <!-- Login Button -->
                    <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                        <button type="submit" class="btn btn-primary btn-block py-2" title="<?= __tr('Iniciar sesión') ?>">
                            <i class="fa fa-sign-in"></i> <?= __tr('Iniciar sesión') ?>
                        </button>
                    </div>
                    <!-- /Login Button -->
                </form>
                <!--  /form action  -->
            </div>
        </div>
    </div>
</div>

<style>
.login-container {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 20px;
}

.login-wrapper {
    width: 100%;
    max-width: 450px;
}

.login-brand {
    margin-bottom: 20px;
}

.login-brand .brand-text {
    font-weight: 700;
    color: #3a3a3a;
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);
}

.card {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    overflow: hidden;
}

.card-header {
    border-bottom: 0;
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
    transition: all 0.2s ease;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(46, 89, 217, 0.2);
}

.alert {
    border-radius: 5px;
}

.input-group-text {
    background-color: #f8f9fc;
    border-right: none;
}

.input-group .form-control {
    border-left: none;
    background-color: #f8f9fc;
}

.input-group .form-control:focus {
    box-shadow: none;
    background-color: #fff;
}

.input-group-prepend .input-group-text {
    color: #6e707e;
}
</style>
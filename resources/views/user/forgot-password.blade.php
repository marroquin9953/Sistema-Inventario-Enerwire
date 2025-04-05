<?php
/*
*  Component  : User
*  View       : Forgot Password  
*  Engine     : UserEngine.js  
*  File       : forgot-password.blade.php  
*  Controller : UserForgotPasswordController 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="UserForgotPasswordController as forgotPasswordCtrl" class="login-container">
    <div class="login-wrapper">
        <div class="login-brand text-center mb-4">
            <!-- Logo could be added here -->
            <h2 class="brand-text">
                <i class="fa fa-question-circle"></i> 
                @section('page-title', __tr('Forgot Password'))
                <?= __tr('Olvidé mi Contraseña') ?>
            </h2>
        </div>

        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-gradient-primary text-white text-center py-3">
                <h4 class="m-0 font-weight-bold">
                    <i class="fa fa-key"></i> <?= __tr('Recuperar Contraseña') ?>
                </h4>
            </div>
            <div class="card-body p-4">
                <!--  form action  -->
                <form class="lw-form lw-ng-form" 
                    name="forgotPasswordCtrl.[[ forgotPasswordCtrl.ngFormName ]]" 
                    ng-submit="forgotPasswordCtrl.submit()" 
                    novalidate>

                    <div class="alert alert-info mb-4">
                        <i class="fa fa-info-circle"></i> <?= __tr('Ingresa tu nombre de usuario o correo electrónico para recibir instrucciones de recuperación.') ?>
                    </div>

                    <!--  Username/Email  -->
                    <div class="form-group">
                        <lw-form-field field-for="usernameOrEmail" label="<?= __tr('Usuario / Correo Electrónico') ?>" v-label="<?= __tr('Usuario / Correo Electrónico') ?>"> 
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                </div>
                                <input type="text" 
                                    class="lw-form-field form-control"
                                    name="usernameOrEmail"
                                    placeholder="<?= __tr('Ingresa tu usuario o correo') ?>"
                                    ng-required="true" 
                                    ng-model="forgotPasswordCtrl.userData.usernameOrEmail" />
                            </div>
                        </lw-form-field>
                    </div>
                    <!--  /Username/Email  -->
                    
                    <!--  submit button  -->
                    <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                        <button type="submit" class="btn btn-primary btn-block py-2" title="<?= __tr('Enviar Solicitud') ?>">
                            <i class="fa fa-paper-plane"></i> <?= __tr('Enviar Solicitud') ?>
                        </button>
                    </div>
                    <!--  /submit button  -->
                </form>
                <!--  /form action  -->
            </div>
            
            <!-- Login Link -->
            <div class="card-footer text-center py-3">
                <div class="small">
                    <span><?= __tr('¿Recordaste tu contraseña?') ?></span>
                    <a ui-sref="login" class="text-primary">
                        <i class="fa fa-sign-in"></i> <?= __tr('Volver al Login') ?>
                    </a>
                </div>
            </div>
            <!-- /Login Link -->
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
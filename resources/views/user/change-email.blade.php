<?php
/*
*  Componente  : Cambiar Correo Electrónico
*  Vista       : Vista de Cambio de Correo Electrónico  
*  Motor       : CommonUserEngine.js  
*  Archivo     : change-email.blade.php  
*  Controlador : UserChangeEmailController 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="UserChangeEmailController as changeEmailCtrl">
    
    <!-- Caja de formulario -->
    <div class="col-md-6 col-xs-12 offset-md-3 lw-login-form-box shadow-lg p-5 border rounded bg-light">
        <div class="lw-section-heading-block text-center mb-4">
            <!-- Encabezado principal -->
            <h2 class="lw-section-heading text-primary font-weight-bold"> 
                <i class="fas fa-envelope"></i> <?=  __tr('Actualizar Correo Electrónico')  ?>
            </h2>
            <p class="text-muted">Mantén tu cuenta segura con un correo electrónico actualizado.</p>
            <!-- /Encabezado principal -->
        </div>
        
        <!-- Mensajes de éxito y error -->
        <div ng-if="!changeEmailCtrl.activationRequired && changeEmailCtrl.requestSuccess" class="alert alert-success" role="alert">
            <strong><i class="fas fa-check-circle"></i> <?= __tr('¡Bien hecho!') ?></strong> 
            [[ changeEmailCtrl.successMessage ]]
        </div>

        <div ng-if="publicCtrl.isEmptyUserEmail" class="lw-form alert alert-info">
            <i class="fas fa-info-circle"></i> <?= __tr('Aún no has configurado tu dirección de correo electrónico.') ?>
        </div>
        <!-- /Mensajes de éxito y error -->

        <!-- Formulario -->
        <form class="lw-form lw-ng-form" 
            name="changeEmailCtrl.[[ changeEmailCtrl.ngFormName ]]" 
            ng-submit="changeEmailCtrl.submit()" 
            novalidate>

            <div class="form-group" ng-if="!publicCtrl.isEmptyUserEmail">
                <label class="control-label font-weight-bold text-secondary"> <i class="fas fa-at"></i> <?=  __tr('Correo Electrónico Actual')  ?></label>
                <input readonly type="text" class="form-control border-secondary" ng-model="changeEmailCtrl.current_email">
            </div>

            <div class="form-row">
                <div class="col" ng-if="!publicCtrl.isEmptyUserEmail">
                    <!-- Contraseña actual -->
                    <lw-form-field field-for="current_password" label="<?=  __tr('Contraseña Actual')  ?>"> 
                        <input type="password" 
                            class="lw-form-field form-control border-primary"
                            name="current_password"
                            min-length="6"
                            max-length="30"
                            ng-required="true"
                            autofocus 
                            ng-model="changeEmailCtrl.userData.current_password" />
                    </lw-form-field>
                    <!-- /Contraseña actual -->
                </div>
                <div class="col">
                    <!-- Nuevo correo -->
                    <lw-form-field field-for="new_email" label="<?=  __tr('Nuevo Correo Electrónico')  ?>"> 
                        <input type="email" 
                            class="lw-form-field form-control border-success"
                            name="new_email"
                            ng-required="true" 
                            ng-model="changeEmailCtrl.userData.new_email" />
                    </lw-form-field>
                    <!-- /Nuevo correo -->
                </div>
            </div>
            
            <!-- Botón de actualización -->
            <div class="form-group text-center">
                <button type="submit" class="lw-btn btn btn-lg btn-primary font-weight-bold shadow" title="<?=  __tr('Actualizar Correo')  ?>">
                    <i class="fas fa-sync-alt"></i> <?=  __tr('Actualizar Correo')  ?>
                </button>
            </div>
            <!-- /Botón de actualización -->
        </form>
        <!-- /Formulario -->
    </div>
</div>
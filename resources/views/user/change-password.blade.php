<?php
/*
*  Componente  : Cambiar Contraseña
*  Vista       : Vista de Cambio de Contraseña  
*  Motor       : CommonUserEngine  
*  Archivo     : change-password.blade.php  
*  Controlador : UserChangePasswordController 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="UserChangePasswordController as updatePasswordCtrl">
    
    <!-- Notificación de error -->
    <div class="col-md-6 col-xs-12 offset-md-3 lw-login-form-box shadow-lg p-5 border rounded bg-light">
        <div class="lw-section-heading-block text-center mb-4">
            <!-- Encabezado principal -->
            <h2 class="lw-section-heading text-primary font-weight-bold"> 
                <i class="fas fa-lock"></i> <?=  __tr('¡Cambia tu Contraseña!')  ?>
            </h2>
            <p class="text-muted">Mantén tu cuenta segura actualizando tu contraseña periódicamente.</p>
            <!-- /Encabezado principal -->
        </div>

        <form class="lw-form lw-ng-form" 
            name="updatePasswordCtrl.[[ updatePasswordCtrl.ngFormName ]]" 
            ng-submit="updatePasswordCtrl.submit()" 
            novalidate>

            <span ng-if="!publicCtrl.isEmptyUserEmail">
                <!-- Contraseña actual -->
                <lw-form-field field-for="current_password" label="<?=  __tr('Contraseña Actual')  ?>"> 
                    <input type="password" 
                        class="lw-form-field form-control border-primary"
                        name="current_password"
                        ng-minlength="6"
                        ng-maxlength="30"
                        ng-required="true" 
                        autofocus
                        ng-model="updatePasswordCtrl.userData.current_password" />
                </lw-form-field>
                <!-- /Contraseña actual -->
            </span>

            <div class="form-row">
                <div class="col">
                    <!-- Nueva contraseña -->
                    <lw-form-field field-for="new_password" label="<?=  __tr('Nueva Contraseña')  ?>"> 
                        <input type="password" 
                            class="lw-form-field form-control border-success"
                            name="new_password"
                            ng-minlength="6"
                            ng-maxlength="30"
                            ng-required="true" 
                            ng-model="updatePasswordCtrl.userData.new_password" />
                    </lw-form-field>
                    <!-- /Nueva contraseña -->
                </div>
                <div class="col">
                    <!-- Confirmar nueva contraseña -->
                    <lw-form-field field-for="new_password_confirmation" label="<?=  __tr('Confirmar Nueva Contraseña')  ?>">
                        <input type="password" 
                            class="lw-form-field form-control border-success"
                            name="new_password_confirmation"
                            ng-minlength="6"
                            ng-maxlength="30"
                            ng-required="true" 
                            ng-model="updatePasswordCtrl.userData.new_password_confirmation" />
                    </lw-form-field>
                    <!-- /Confirmar nueva contraseña -->
                </div>
            </div>

            <hr>
            <div class="form-group text-center">
                <button type="submit" class="lw-btn btn btn-lg btn-primary font-weight-bold shadow" title="<?=  __tr('Actualizar Contraseña')  ?>">
                    <i class="fas fa-sync-alt"></i> <?=  __tr('Actualizar Contraseña')  ?>
                </button>
            </div>

        </form>
    </div>
</div>
<?php
/*
*  Component  : User
*  View       : Profile  
*  Engine     : CommonUserEngine.js  
*  File       : profile-edit.blade.php  
*  Controller : UserProfileEditController 
----------------------------------------------------------------------------- */ 
?>

<div ng-controller="UserProfileEditController as profileEditCtrl" ng-cloak>
    
    <!-- Contenedor principal -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                
                <!-- Sección de cabecera -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-white border-bottom-0">
                        <h3 class="card-title m-0 font-weight-bold">
                            <?= __tr('Actualizar Perfil') ?>
                        </h3>
                    </div>
                    
                    <!-- Spinner de carga -->
                    <div class="card-body text-center" ng-if="!profileEditCtrl.request_completed">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                    </div>
                    
                    <!-- Formulario de edición -->
                    <div class="card-body" ng-if="profileEditCtrl.request_completed">
                        <form name="profileEditCtrl.[[ profileEditCtrl.ngFormName ]]" 
                              ng-submit="profileEditCtrl.submit()" 
                              novalidate>

                            <input type="hidden" id="lwUserProfileEditTxtMsg" 
                                   loading-text="<?= __tr('Subiendo...') ?>" 
                                   file-uploaded-text="<?= __tr('Archivo Subido') ?>">
                            
                            <!-- Imagen de perfil actual y carga -->
                            <div class="row mb-4">
                                <div class="col-md-4 text-center mb-3 mb-md-0">
                                    <!-- Vista previa de imagen actual -->
                                    <div class="avatar-preview mb-3">
                                        <a href="[[ profileEditCtrl.existingProfilePictureURL ]]" lw-ng-colorbox>
                                            <div class="rounded-circle overflow-hidden mx-auto shadow-sm" style="width: 150px; height: 150px; position: relative;">
                                                <img ng-src="[[ profileEditCtrl.existingProfilePictureURL ]]" 
                                                     style="width: 100%; height: 100%; object-fit: cover; image-rendering: -webkit-optimize-contrast;" 
                                                     alt="Imagen de perfil"
                                                     class="profile-image">
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="col-md-8">
                                    <!-- Selector de imagen -->
                                    <lw-form-selectize-field field-for="profile_picture" label="<?= __tr('Foto de Perfil') ?>" class="lw-selectize">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge badge-primary mr-2">[[ profileEditCtrl.profileFilesCount ]]</span>
                                            <small class="text-muted">imágenes disponibles</small>
                                        </div>
                                        
                                        <selectize config='profileEditCtrl.imagesSelectConfig' 
                                                  class="lw-form-field" 
                                                  name="profile_picture" 
                                                  ng-model="profileEditCtrl.profileData.profile_picture" 
                                                  options='profileEditCtrl.profileFiles' 
                                                  placeholder="<?= __tr('Seleccionar Imagen Subida') ?>">
                                        </selectize>
                                        
                                        <!-- Botón de carga de imagen -->
                                        <div class="mt-2">
                                            @include('media.upload-button')
                                        </div>
                                    </lw-form-selectize-field>
                                </div>
                            </div>
                            
                            <!-- Información personal -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user-circle mr-2"></i>Información Personal
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <!-- Nombre -->
                                        <div class="col-md-4 mb-3">
                                            <lw-form-field field-for="first_name" label="<?= __tr('Nombre') ?>"> 
                                                <input type="text" 
                                                      class="lw-form-field form-control"
                                                      name="first_name"
                                                      ng-required="true" 
                                                      ng-model="profileEditCtrl.profileData.first_name" />
                                            </lw-form-field>
                                        </div>
                                        
                                        <!-- Apellido -->
                                        <div class="col-md-4 mb-3">
                                            <lw-form-field field-for="last_name" label="<?= __tr('Apellido') ?>"> 
                                                <input type="text" 
                                                      class="lw-form-field form-control"
                                                      name="last_name"
                                                      ng-required="true" 
                                                      ng-model="profileEditCtrl.profileData.last_name" />
                                            </lw-form-field>
                                        </div>
                                        
                                        <!-- Rol de Usuario -->
                                        <div class="col-md-4 mb-3">
                                            <lw-form-field field-for="userRole" label="<?= __tr('Rol de Usuario') ?>"> 
                                                <input type="text" 
                                                      class="lw-form-field form-control bg-light"
                                                      name="userRole"
                                                      ng-required="true" 
                                                      readonly="true" 
                                                      ng-model="profileEditCtrl.profileData.userRole" />
                                            </lw-form-field>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Información de dirección -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-map-marker-alt mr-2"></i>Información de Dirección
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <!-- Dirección Línea 1 -->
                                        <div class="col-md-6 mb-3">
                                            <lw-form-field field-for="address_line_1" label="<?= __tr('Dirección Línea 1') ?>"> 
                                              <input type="text" class="form-control lw-form-field" name="address_line_1" ng-model="profileEditCtrl.profileData.address_line_1" />
                                            </lw-form-field>
                                        </div>
                                        
                                        <!-- Dirección Línea 2 -->
                                        <div class="col-md-6 mb-3">
                                            <lw-form-field field-for="address_line_2" label="<?= __tr('Dirección Línea 2') ?>"> 
                                              <input type="text" class="form-control lw-form-field" name="address_line_2" ng-model="profileEditCtrl.profileData.address_line_2" />
                                            </lw-form-field>
                                        </div>
                                        
                                        <!-- País -->
                                        <div class="col-md-6">
                                            <lw-form-selectize-field field-for="country" label="<?= __tr('País') ?>" class="lw-selectize">
                                                <selectize config='profileEditCtrl.countrySelectConfig' 
                                                          class="form-control lw-form-field" 
                                                          name="country" 
                                                          ng-model="profileEditCtrl.profileData.country" 
                                                          ng-required="true" 
                                                          options='profileEditCtrl.countries' 
                                                          placeholder="<?= __tr('Seleccionar País') ?>">
                                                </selectize>
                                            </lw-form-selectize-field>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botones de acción -->
                            <div class="d-flex justify-content-between mt-4">
                                <a ui-sref="profile" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i>Volver
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i>Actualizar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Plantillas para selectize -->
    <script type="text/_template" id="imageListItemTemplate">
        <div class="lw-selectize-item lw-selectize-item-selected">
            <span class="lw-selectize-item-thumb">
               <img src="<%= __tData.path %>"/> 
            </span> 
            <span class="lw-selectize-item-label"><%= __tData.name%></span>
        </div>
    </script>

    <script type="text/_template" id="imageListOptionTemplate">
        <div class="lw-selectize-item">
            <span class="lw-selectize-item-thumb">
                <img src="<%= __tData.path %>" />
            </span> 
            <span class="lw-selectize-item-label"><%= __tData.name%></span>
        </div>
    </script>
</div>

<style>
/* Estilos para mejorar la vista de edición de perfil */
.profile-image {
    transition: opacity 0.3s ease-in-out;
    transform: translateZ(0);
    backface-visibility: hidden;
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,.05);
}

.lw-selectize-item-thumb img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
}

.avatar-preview {
    position: relative;
}

.avatar-preview:hover:after {
    content: 'Vista previa';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
}
</style>
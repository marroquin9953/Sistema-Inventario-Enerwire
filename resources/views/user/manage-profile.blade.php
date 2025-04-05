<?php
/*
*  Component  : User
*  View       : Profile  
*  Engine     : CommonUserEngine.js  
*  File       : manage-profile.blade.php  
*  Controller : UserProfileController 
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="UserProfileController as profileCtrl" ng-cloak>
    
    <!-- Contenedor principal -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                
                <!-- Sección de cabecera -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-white border-bottom-0">
                        <h3 class="card-title m-0 font-weight-bold">
                            <?= __tr('Perfil') ?>
                        </h3>
                    </div>
                    
                    <!-- Spinner de carga -->
                    <div class="card-body text-center" ng-if="!profileCtrl.request_completed">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                    </div>
                    
                    <!-- Contenido del perfil -->
                    <div class="card-body" ng-if="profileCtrl.request_completed">
                        <div class="row">
                            <!-- Foto de perfil -->
                            <div class="col-md-4 text-center mb-4 mb-md-0">
                                <div class="avatar-container">
                                    <a href="[[ profileCtrl.existingProfilePictureURL ]]" lw-ng-colorbox>
                                        <div class="rounded-circle overflow-hidden mx-auto shadow-sm" style="width: 120px; height: 120px; position: relative;">
                                            <img ng-src="[[ profileCtrl.existingProfilePictureURL ]]" 
                                                style="width: 100%; height: 100%; object-fit: cover; image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;" 
                                                alt="[[ profileCtrl.profileData.first_name ]] [[ profileCtrl.profileData.last_name ]]"
                                                onload="this.style.opacity='2';" 
                                                onerror="this.src='path/to/default-avatar.jpg';" 
                                                class="profile-image">
                                        </div>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Información del perfil -->
                            <div class="col-md-8">
                                <h4 class="font-weight-bold mb-3">[[ profileCtrl.profileData.first_name ]] [[ profileCtrl.profileData.last_name ]]</h4>
                                
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-sm-4 text-muted">
                                            <i class="fas fa-user-tag mr-2"></i>Rol:
                                        </div>
                                        <div class="col-sm-8">[[ profileCtrl.profileData.userRole ]]</div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-sm-4 text-muted">
                                            <i class="fas fa-envelope mr-2"></i>Correo:
                                        </div>
                                        <div class="col-sm-8">[[ profileCtrl.profileData.email ]]</div>
                                    </div>
                                </div>
                                
                                <div class="mb-3" ng-if="profileCtrl.profileData.address_line_1">
                                    <div class="row">
                                        <div class="col-sm-4 text-muted">
                                            <i class="fas fa-map-marker-alt mr-2"></i>Dirección:
                                        </div>
                                        <div class="col-sm-8">
                                            <p class="mb-1">[[ profileCtrl.profileData.address_line_1 ]]</p>
                                            <p class="mb-1" ng-if="profileCtrl.profileData.address_line_2">[[ profileCtrl.profileData.address_line_2 ]]</p>
                                            <p class="mb-0" ng-if="profileCtrl.profileData.country">[[ profileCtrl.profileData.country ]]</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer con botones de acción -->
                    <div class="card-footer bg-white text-right" ng-if="profileCtrl.request_completed">
                        <a ui-sref="profileEdit" class="btn btn-primary">
                            <i class="fas fa-user-edit mr-1"></i>Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos para mejorar la calidad de imagen */
.profile-image {
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
    transform: translateZ(0);
    backface-visibility: hidden;
    -webkit-font-smoothing: subpixel-antialiased;
}

/* Solución para navegadores que soportan backdrop-filter */
@supports (backdrop-filter: none) {
    .avatar-container .rounded-circle {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: 2px solid #fff;
    }
}

/* Media query para pantallas de alta resolución */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .avatar-container .rounded-circle {
        transform: translateZ(0);
    }
}
</style>
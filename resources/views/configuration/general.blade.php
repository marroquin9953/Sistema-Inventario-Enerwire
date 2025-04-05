<?php
/*
*  Component  : Configuration
*  View       : General Dialog
*  Engine     : ConfigurationEngine  
*  File       : general.blade.php  
*  Controller : GeneralDialogController 
----------------------------------------------------------------------------- */ 
?>

<div>

    <!-- /error notification -->
    <div class="col-md-8 col-xs-12 offset-md-2 lw-login-form-box shadow p-4 border">
        <div class="lw-section-heading-block">
            <!--  main heading  -->
            <h3 class="lw-section-heading">
                <div class="lw-heading">
                    <?=  __tr( 'Configuración general' )  ?>
                </div>
            </h3>
            <!--  /main heading  -->
        </div>

        <!-- Loading (remove the following to stop the loading)-->
        <div class="overlay" ng-if="generalCtrl.pageStatus == false">
            <div class="loader"></div>
        </div>
        <!-- end loading -->

        <div ng-include src="'lw-settings-update-reload-button-template.html'"></div>

        <input type="hidden" id="lwGeneralSettingTxtMsg" loading-text="<?= __tr( 'Upload in Process') ?>"
            logo-empty-file-text="<?= __tr('Only PNG images are expected') ?>"
            file-uploaded-text="<?= __tr('File Uploaded') ?>">

        <!--  form action  -->
        <form class="lw-form lw-ng-form" name="generalCtrl.[[ generalCtrl.ngFormName ]]"
            ng-submit="generalCtrl.submit()" novalidate>

            <!-- Name -->
            <lw-form-field field-for="name" label="<?= __tr( 'Nombre del sitio web' ) ?>">
                <input type="text" class="lw-form-field form-control" autofocus name="name" ng-required="true" min="2"
                    max="30" ng-model="generalCtrl.editData.name" />
            </lw-form-field>
            <!-- Name -->

            <!-- Select logo_image -->
            <div class="form-group">
                <fieldset class="lw-fieldset-2">
                    <legend class="lw-fieldset-legend-font">
                        <?= __tr('Favicon & Logo') ?>
                    </legend>

                    <!-- Upload image -->
                    <div class="text-center">
                        @include('media.upload-button')
                    </div>
                    <!-- / Upload image -->

                    <div class="alert alert-info mt-2">
                        <strong>Nota : </strong>La altura recomendada para el logo es de 50 píxeles.
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <!-- New Favicon image -->
                            <lw-form-selectize-field field-for="favicon_image" label="<?= __tr( 'Nuevo Favicon' ) ?>"
                                class="lw-selectize"><span class="badge lw-badge">[[ generalCtrl.faviconFilesCount
                                    ]]</span>
                                <selectize config='generalCtrl.imagesSelectConfig' class="lw-form-field"
                                    name="favicon_image" ng-model="generalCtrl.editData.favicon_image"
                                    options='generalCtrl.faviconFiles'
                                    placeholder="<?= __tr( 'Solo se permiten imágenes en formato ICO.' ) ?>"></selectize>
                            </lw-form-selectize-field>
                            <!-- New Favicon image -->
                        </div>

                        <div class="col-md-6">
                            <!-- New Small Logo image -->
                            <lw-form-selectize-field field-for="small_logo_image"
                                label="<?= __tr( 'Nuevo logo pequeño' ) ?>" class="lw-selectize"><span
                                    class="badge badge-success lw-badge">[[ generalCtrl.logoFilesCount ]]</span>
                                <selectize config='generalCtrl.imagesSelectConfig' class="lw-form-field"
                                    name="small_logo_image" ng-change="generalCtrl.checkLogo(2)"
                                    ng-model="generalCtrl.editData.small_logo_image" options='generalCtrl.logoFiles'
                                    placeholder="<?= __tr( 'Solo se permiten imágenes en formato PNG.' ) ?>"></selectize>
                            </lw-form-selectize-field>
                            <!-- New Small Logo image -->
                        </div>

                        <div class="col-md-12">
                            <!-- New Logo image -->
                            <lw-form-selectize-field field-for="logo_image" label="<?= __tr( 'Nuevo logo' ) ?>"
                                class="lw-selectize"><span class="badge lw-badge">[[ generalCtrl.logoFilesCount
                                    ]]</span>
                                <selectize config='generalCtrl.imagesSelectConfig' class="lw-form-field"
                                    name="logo_image" ng-change="generalCtrl.checkLogo(1)"
                                    ng-model="generalCtrl.editData.logo_image" options='generalCtrl.logoFiles'
                                    placeholder="<?= __tr( 'Solo se permiten imágenes en formato PNG.' ) ?>"></selectize>

                            </lw-form-selectize-field>
                            <!-- New Logo image -->
                        </div>
                    </div>
                </fieldset>
            </div>
            <!--/ Select logo_image -->

            <!-- <fieldset class="lw-fieldset-2">
            <legend class="lw-fieldset-legend-font">
                <?= __tr('Background and Text / Link Color') ?>
            </legend>

            <div class="lw-config-theme-container">
                <a href="" class="lw-theme-color-link" ng-repeat="themeColor in generalCtrl.themeColors" ng-click="generalCtrl.selectSiteColor(themeColor)">
                    <span class="lw-theme-color-block" style="background-color:#[[ themeColor.background ]]"></span>
                    <span class="lw-theme-color-block" style="background-color:#[[ themeColor.text ]]"></span>
                </a>
            </div><br>

            <div class="form-row">
                <div class="col">

                    <lw-form-field field-for="header_background_color" class="lw-has-pre-addon" label="<?= __tr( 'Header Background Color' )  ?>">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background: #[[ generalCtrl.editData.header_background_color ]] !important">
                                    #
                                </span>
                            </div>
                            <input type="text" 
                                class="lw-form-field form-control"
                                autofocus
                                id="header_background_color"
                                name="header_background_color"
                                ng-minlength="6"
                                ng-maxlength="6"
                                lw-color-picker
                                readonly 
                                ng-model="generalCtrl.editData.header_background_color" />
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <a href class="input-group-addon btn-btn-default" ng-click="generalCtrl.clearColor()"><?= __tr('Use Default') ?></a>
                                </span>
                            </div>
                        </div>
                    </lw-form-field>
                </div>

                <div class="col">
                    <lw-form-field field-for="header_text_link_color" class="lw-has-pre-addon" label="<?= __tr( 'Header Text / Link Color' )  ?>">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background-color: #[[ generalCtrl.editData.header_text_link_color ]]">
                                    #
                                </span>
                            </div>
                            <input type="text" 
                                class="lw-form-field form-control"
                                autofocus
                                id="header_text_link_color"
                                name="header_text_link_color"
                                ng-minlength="6"
                                ng-maxlength="6"
                                lw-color-picker
                                readonly 
                                ng-model="generalCtrl.editData.header_text_link_color" />
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <a href class="input-group-addon btn-btn-default" ng-click="generalCtrl.clearPrimaryColor()"><?= __tr('Use Default') ?></a>
                                </span>
                            </div>
                        </div>
                    </lw-form-field>
                </div>
            </div>
         </fieldset> -->

            <div class="form-row">
                <div class="col">
                    <!-- timezone -->
                    <lw-form-field field-for="timezone" label="<?= __tr( 'Zona horaria' ) ?>">
                        <selectize config='generalCtrl.timezone_select_config' class="lw-form-field" name="timezone"
                            ng-model="generalCtrl.editData.timezone" options='generalCtrl.timezoneData'
                            placeholder="<?= __tr( 'Timezone' ) ?>" ng-required="true"></selectize>
                    </lw-form-field>
                    <!-- /timezone -->
                </div>

                <div class="col">
                    <!-- System Email -->
                    <lw-form-field field-for="business_email" title="<?= __tr('Will be used for the from email.') ?>"
                        label="<?= __tr( 'Correo electrónico del sistema' ) ?>">
                        <input type="email" class="lw-form-field form-control" name="business_email" ng-required="true"
                            ng-model="generalCtrl.editData.business_email" />
                    </lw-form-field>
                    <!-- System Email -->
                </div>
            </div>

            <fieldset class="lw-fieldset-2">

                <legend class="lw-fieldset-legend-font">
                    <?= __tr('Configuración de inicio de sesión') ?>
                </legend>

                <!-- Enable LivelyWorks Credit Info -->
                <lw-form-checkbox-field field-for="enable_login_attempt" label="<?= __tr( 'Habilitar intentos de inicio de sesión' ) ?>"
                    advance="true">
                    <input type="checkbox" class="lw-form-field js-switch" name="enable_login_attempt"
                        ng-model="generalCtrl.editData.enable_login_attempt" ui-switch="" />
                </lw-form-checkbox-field>
                <!-- /Enable LivelyWorks Credit Info -->

                <div class="form-inline" ng-if="generalCtrl.editData.enable_login_attempt == true">
                    <div class="form-group">
                        <label for="show_captcha">
                            <?= __tr('Show captcha after') ?>
                        </label>

                        <!-- Show Captcha after login attempt -->
                        <lw-form-field field-for="show_captcha" v-label="<?= __tr( 'Show Captcha' ) ?>">
                            <input type="number" class="lw-form-field form-control form-control mx-sm-3 mt-4" autofocus
                                name="show_captcha" ng-required="true" min="1"
                                ng-model="generalCtrl.editData.show_captcha" />
                        </lw-form-field>
                        <!-- /Show Captcha after login attempt -->

                        <?= __tr(' failed login attempts') ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col">
                        <!-- Google Recaptcha Site Key -->
                        <lw-form-field field-for="recaptcha_site_key" label="<?= __tr( 'Google Recaptcha Site Key' ) ?>"
                            ng-if="generalCtrl.editData.enable_login_attempt == true">
                            <input type="text" class="lw-form-field form-control" name="recaptcha_site_key"
                                ng-required="true" ng-model="generalCtrl.editData.recaptcha_site_key" />
                        </lw-form-field>
                        <!-- Google Recaptcha Site Key -->
                    </div>

                    <div class="col">
                        <!-- Google Recaptcha Secret Key -->
                        <lw-form-field field-for="recaptcha_secret_key"
                            label="<?= __tr( 'Google Recaptcha Secret Key' ) ?>"
                            ng-if="generalCtrl.editData.enable_login_attempt == true">
                            <input type="text" class="lw-form-field form-control" name="recaptcha_secret_key"
                                ng-required="true" ng-model="generalCtrl.editData.recaptcha_secret_key" />
                        </lw-form-field>
                        <!-- Google Recaptcha Secret Key -->
                    </div>
                </div>
            </fieldset>

            <div class="alert alert-info">
                <span ng-if="generalCtrl.editData.enable_credit_info">
                Ocultar 
                </span>
                <span ng-if="!generalCtrl.editData.enable_credit_info">
                Ocultar la información de crédito
                </span>
                de desarrollo de software.
                <span ng-if="generalCtrl.editData.enable_credit_info">
                    Thank You. <i class="fa fa-smile-o fa-2x"></i>
                </span>
            </div>

            <div class="form-row">
                <div class="col">
                    <!-- Enable LivelyWorks Credit Info -->
                    <lw-form-checkbox-field field-for="enable_credit_info" label="<?= __tr( 'Habilitar información de crédito' ) ?>"
                        advance="true">
                        <input type="checkbox" class="lw-form-field js-switch" name="enable_credit_info"
                            ng-model="generalCtrl.editData.enable_credit_info" ui-switch="" />
                    </lw-form-checkbox-field>
                    <!-- /Enable LivelyWorks Credit Info -->
                </div>

                <div class="col">
                    <lw-form-checkbox-field field-for="restrict_user_email_update"
                        label="<?= __tr( 'Restringir a usuarios de actualizar correo' ) ?>">
                        <input type="checkbox" class="lw-form-field js-switch" name="restrict_user_email_update"
                            ng-model="generalCtrl.editData.restrict_user_email_update" ui-switch="" />
                    </lw-form-checkbox-field>
                </div>
            </div>

            <!-- Addition Footer Text After Name -->
            <lw-form-field field-for="footer_text"
                label="<?= __tr( 'Texto adicional en el pie de página después del nombre y copyright' ) ?>">
                <input type="footer_text" class="lw-form-field form-control" autofocus name="footer_text"
                    ng-model="generalCtrl.editData.footer_text" />
            </lw-form-field>
            <!-- /Addition Footer Text After Name -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary" title="<?= __tr('Update') ?>">
                    <?= __tr('Actualizar') ?>
                </button>
            </div>

        </form>
        <!--  /form action  -->


        <!-- New logo drop down list item template -->
        <script type="text/_template" id="imageListItemTemplate">
            <div>
            <span class="lw-selectize-item lw-selectize-item-selected"><img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span>
        </div>
    </script>
        <!-- /New logo drop down list item template -->

        <!-- New logo drop down list options template -->
        <script type="text/_template" id="imageListOptionTemplate">
            <div class="lw-selectize-item">
            <span class="lw-selectize-item-thumb"><img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span>
        </div>
    </script>
        <!-- /New logo drop down list options template -->

    </div>
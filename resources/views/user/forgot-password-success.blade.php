<div class="login-container">
    <div class="login-wrapper">
        <div class="card shadow-lg border-0 rounded-lg success-card">
            <div class="card-body p-5 text-center">
                <div class="success-icon-container mb-4">
                    <div class="success-icon-circle">
                        <i class="fa fa-envelope fa-3x text-white"></i>
                    </div>
                </div>
                
                <h2 class="font-weight-bold text-primary mb-3">
                    @section('page-title', __tr('Reset your password'))
                    <?= __tr('Restablece tu Contraseña') ?>
                </h2>
                
                <div class="alert alert-success mb-4">
                    <i class="fa fa-check-circle"></i> <?= __tr('Hemos enviado un enlace de restablecimiento a tu correo electrónico.') ?>
                </div>
                
                <p class="text-muted mb-4">
                    <?= __tr('Por favor revisa tu bandeja de entrada y sigue las instrucciones para crear una nueva contraseña. Si no encuentras el correo, revisa tu carpeta de spam.') ?>
                </p>
                
                <div class="d-flex justify-content-center">
                    <a ui-sref="login" class="btn btn-primary px-4">
                        <i class="fa fa-sign-in"></i> <?= __tr('Volver al Inicio de Sesión') ?>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4 text-muted small">
            <p><?= __tr('¿No recibiste el correo?') ?> <a ui-sref="forgot_password" class="text-primary"><?= __tr('Intentar nuevamente') ?></a></p>
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
    max-width: 500px;
}

.success-card {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    overflow: hidden;
    transition: all 0.3s ease;
}

.success-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
}

.success-icon-container {
    display: flex;
    justify-content: center;
}

.success-icon-circle {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: linear-gradient(45deg, #28a745, #20c997);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
    transition: all 0.2s ease;
    padding: 10px 25px;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(46, 89, 217, 0.2);
}

.alert {
    border-radius: 5px;
    border-left: 4px solid #28a745;
}

.alert-success {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
}
</style>
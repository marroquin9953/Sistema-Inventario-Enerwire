<?php

if (! file_exists('./../../install.php')) {
    header('HTTP/1.0 404 Not Found');
    exit();
}

if (! $_POST['requirements_fulfilled'] and ($_POST['requirements_fulfilled'] != true)) {
    header('Location: index.php');
}

error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', 1);
require 'vendor/autoload.php';

$startSetupVerification = new LivelyWorks\Installation\Verification();
echo json_encode($startSetupVerification->verifyUserInputs($_POST));

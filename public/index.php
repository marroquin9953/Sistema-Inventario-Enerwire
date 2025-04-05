<?php

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Registrar el Autoloader
|--------------------------------------------------------------------------
|
| Composer proporciona un cargador de clases conveniente y generado 
| automáticamente para nuestra aplicación. ¡Solo necesitamos utilizarlo!
| Simplemente lo requerimos en este script para no preocuparnos por 
| cargar manualmente nuestras clases más adelante. Se siente bien relajarse.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Encender las luces
|--------------------------------------------------------------------------
|
| Necesitamos iluminar el desarrollo con PHP, así que encendamos las luces.
| Esto inicia el framework y lo prepara para su uso, luego cargará 
| esta aplicación para que podamos ejecutarla y enviar las respuestas 
| de vuelta al navegador y deleitar a nuestros usuarios.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Ejecutar la aplicación
|--------------------------------------------------------------------------
|
| Una vez que tenemos la aplicación, podemos manejar la solicitud entrante
| a través del kernel, y enviar la respuesta asociada de vuelta al
| navegador del cliente, permitiéndoles disfrutar de la creativa 
| y maravillosa aplicación que hemos preparado para ellos.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);

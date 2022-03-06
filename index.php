<?php

define('LARAVEL_START', microtime(true));


require __DIR__.'/subkecsetu/vendor/autoload.php';



$app = require_once __DIR__.'/subkecsetu/bootstrap/app.php';

// set the public path to this directory
$app->bind('path.public', function() {
    return __DIR__;
});


$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);

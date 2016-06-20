<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

$env = isset($_SERVER['SYMFONY_ENV']) ? $_SERVER['SYMFONY_ENV'] : 'prod';
$debug = isset($_SERVER['SYMFONY_DEBUG']) ? !!$_SERVER['SYMFONY_DEBUG'] : $env === 'dev';

/* HTTP-Auth Workaround */
if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
    $auth = explode(':' , base64_decode(substr($_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 6)));
    $_SERVER['PHP_AUTH_USER'] = isset($auth[0]) ? $auth[0] : '';
    $_SERVER['PHP_AUTH_PW'] = isset($auth[1]) ? $auth[1] : '';
}

if ($debug) {
    Debug::enable();
}

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel($env, $debug);
$kernel->loadClassCache();

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

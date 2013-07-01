<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

require_once 'ClassMap.php';

$classes = array(
    'Larium\\Http\\Params' => 'Larium/Http/Params.php',
    'Larium\\Http\\ServerParams' => 'Larium/Http/ServerParams.php',
    'Larium\\Http\\HeaderParams' => 'Larium/Http/HeaderParams.php',
    'Larium\\Http\\Request' => 'Larium/Http/Request.php',
    'Larium\\Http\\RequestInterface' => 'Larium/Http/RequestInterface.php',
    'Larium\\Http\\Response' => 'Larium/Http/Response.php',
    'Larium\\Http\\ResponseInterface' => 'Larium/Http/ResponseInterface.php',
    'Larium\\Http\\Cookie' => 'Larium/Http/Cookie.php',
    'Larium\\Http\\Session\\Handler\\SessionHandlerInterface' => 'Larium/Http/Session/Handler/SessionHandlerInterface.php',
    'Larium\\Http\\Session\\Handler\\FileSessionHandler' => 'Larium/Http/Session/Handler/FileSessionHandler.php',
    'Larium\\Http\\Session\\Handler\\MysqlSessionHandler' => 'Larium/Http/Session/Handler/MysqlSessionHandler.php',
    'Larium\\Http\\Session\\Session' => 'Larium/Http/Session/Session.php',
    'Larium\\Http\\Session\\SessionInterface' => 'Larium/Http/Session/SessionInterface.php',
);

ClassMap::load(__DIR__ . "/src/", $classes)->register();

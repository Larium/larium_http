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
);

ClassMap::load(__DIR__ . "/src/", $classes)->register();

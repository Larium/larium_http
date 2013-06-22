<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

require_once 'ClassMap.php';

$classes = array(
    'Larium\\Http\\Params' => 'Larium/Http/Params.php',
    'Larium\\Http\\ServerParams' => 'Larium/Http/ServerParams.php',
    'Larium\\Http\\HeaderParams' => 'Larium/Http/HeaderParams.php',
    'Larium\\Http\\Request' => 'Larium/Http/Request.php',
    'Larium\\Http\\Response' => 'Larium/Http/Response.php',
);
$loader = new ClassMap(__DIR__ . "/src/", $classes);
$loader->register();

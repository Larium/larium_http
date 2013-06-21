<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

require_once 'ClassMap.php';

$classes = array(
    'Larium\\Http\\Params' => 'Larium/Http/Params.php',
    'Larium\\Http\\ServerParams' => 'Larium/Http/ServerParams.php',
    'Larium\\Http\\Request' => 'Larium/Http/Request.php',
);
$loader = new ClassMap(__DIR__ . "/src/", $classes);
$loader->register();

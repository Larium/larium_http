<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

require_once 'ClassMap.php';

$classes = array(
    'Larium\\Http\\Params' => 'Larium/Http/Params.php',
);
$loader = new ClassMap(__DIR__ . "/src/", $classes);
$loader->register();

<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 22/09/2017
 * Time: 10:53
 */

require __DIR__ . '/../Lib/Main/SplClassLoader.php';

$mainLoader = new SplClassLoader('Main', __DIR__.'/../Lib');
$mainLoader->register();

$modelLoader = new SplClassLoader('Model', __DIR__.'/../Lib/Vendors');
$modelLoader->register();

$entityLoader = new SplClassLoader('Entity', __DIR__.'/../Lib/Vendors');
$entityLoader->register();

$controller = new \Main\Controller();
$controller->run();

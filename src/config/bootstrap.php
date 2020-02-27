<?php

use AA\Application;
use Phalcon\Config\Adapter\Yaml;
use Phalcon\Di\FactoryDefault;
use Phalcon\Http\Request;
use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View;
use Phalcon\Url;

require_once '../../vendor/autoload.php';

$config = new Yaml('../config/config.yml');

$loader = new Loader();
$loader->registerNamespaces($config->application->namespaces->toArray());

$di = new FactoryDefault();

$di->set('request', function() {
    return new Request();
});

$di->set('view', function() {
    return new View();
}, true);

$di->set('dispatcher', function() use($config) {
    $dispatcher = new Dispatcher();
    $dispatcher->setDefaultNamespace($config->application->defaultNamespace);

    return $dispatcher;
});

$app = new Application($di);

try {
    $app->registerRouter($config->application->router->toArray());
    $response = $app->handle($_SERVER['REQUEST_URI']);

    $response->send();
} catch (\Exception $e) {
    echo "ERROR occured";
    exit;
}


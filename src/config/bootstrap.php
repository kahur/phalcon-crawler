<?php

use AA\Application;
use AA\Library\Http\HttpClient;
use AA\Library\Http\Response\ResponseFactory;
use AA\Library\Http\Transfer\Adapter\Curl;
use AA\Library\Metrics\Metrics;
use AA\Library\SimpleHtml\SimpleHtml;
use AA\Services\MetricsService;
use AA\Services\SiteStatsService;
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

$di->set('httpClient', function() use($config) {
    return new HttpClient(new Curl($config->httpClient->options->toArray()), new ResponseFactory());
});

$di->set('htmlParser', function() {
    return new SimpleHtml();
});

$di->set('metricsService', function() {
    return new MetricsService(new Metrics());
});

$di->set('siteStatsService', function() use($di) {
    return new SiteStatsService(
        $di->get('httpClient'),
        $di->get('htmlParser'),
        $di->get('metricsService')
    );
});

$app = new Application($di);

try {
    $app->registerRouter($config->application->router->toArray());
    $response = $app->handle($_SERVER['REQUEST_URI']);

    $response->send();
} catch (\Exception $e) {
    throw $e;
    echo "ERROR occured";
    exit;
}


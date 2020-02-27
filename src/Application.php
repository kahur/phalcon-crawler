<?php

namespace AA;

use Phalcon\Config\Adapter\Yaml;
use Phalcon\Mvc\Router\Annotations;

class Application extends \Phalcon\Mvc\Application
{
    /**
     * Prepare and configure router service
     *
     * @param array $routerConfig
     *
     * @throws \Exception
     */
    public function registerRouter(array $routerConfig)
    {
        $adapter = $routerConfig['adapter'] ?? null;

        if (!$adapter) {
            throw new \Exception('Invalid router configuration');
        }

        $adapterName = 'Phalcon\Mvc\Router\\'.ucfirst(strtolower($adapter));

        if (!class_exists($adapterName)) {
            throw new \Exception('Unsuported router adapter');
        }

        $this->getDI()->set('router', function() use($adapterName, $routerConfig) {
            /** @var Annotations $router */
            $router = new $adapterName;

            $routes = $routerConfig['routes'];

            if (isset($routes['resource'])) {
                $routeConfig = new Yaml($routes['resource']);

                $routes = $routeConfig->routes->toArray();
            }

            foreach($routes as $resource) {
                $router->addResource($resource);
            }

            return $router;
        });
    }
}
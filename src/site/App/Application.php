<?php

namespace App;

use App\Controllers\DefaultController;
use CPE\Framework\AbstractApplication;
use CPE\Framework\Router;

class Application extends AbstractApplication
{
    public function run()
    {
        // map all routes to corresponding controllers/actions
        $this->router = new Router($this);
        $this->router->map('GET', '/', DefaultController::class, 'login');
        $this->router->map('POST', '/login', DefaultController::class, 'login');
        $this->router->map('GET', '/dashboard', DefaultController::class, 'dashboard');
        $this->router->map('GET', '/login', DefaultController::class, 'login');
        $this->router->mapDefault(DefaultController::class, 'error404');

        $route = $this->router->findRoute();
        $controller = $this->router->getController($route->controller);
        $controller->execute($route->action, $route->foundParams);
    }
}

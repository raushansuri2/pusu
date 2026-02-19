<?php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);

    // Root scope
    $routes->scope('/', function (RouteBuilder $builder): void {
        $builder->connect('/', ['controller' => 'Pages', 'action' => 'home']);
        $builder->connect('/{controller}/{action}/*', []); // Catch-all for non-prefixed routes
    });

    // Admin prefix scope
    $routes->prefix('admin', function (RouteBuilder $builder): void {
        // Define the login route
        // $builder->connect('/login', ['controller' => 'Admins', 'action' => 'login']);
        
        // Define the route for /admin to redirect to login
        $builder->connect('/', ['controller' => 'Admins', 'action' => 'login']); // This handles /admin
        
        // Other admin routes
        $builder->connect('/logout', ['controller' => 'Admins', 'action' => 'logout']);
        $builder->connect('/dashboard', ['controller' => 'Admins', 'action' => 'dashboard']);
        $builder->connect('/index', ['controller' => 'Admins', 'action' => 'index']);
        $builder->connect('/edit', ['controller' => 'Admins', 'action' => 'edit']);
        $builder->connect('/changepassword', ['controller' => 'Admins', 'action' => 'changepassword']);
        $builder->connect('/forgotpassword', ['controller' => 'Admins', 'action' => 'forgotpassword']);
        $builder->connect('/reset/{token}', ['controller' => 'Admins', 'action' => 'reset'])
            ->setPass(['token']);
        $builder->fallbacks(DashedRoute::class);
    });

    // API prefix scope (unchanged)
    $routes->prefix('api', function (RouteBuilder $builder): void {
        $builder->setExtensions(['json']);
        $builder->connect('/{controller}/{action}/*', []);
    });
};
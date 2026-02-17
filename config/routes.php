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

        // Settings routes
        $builder->connect('/settings', ['controller' => 'Settings', 'action' => 'index']);
        $builder->connect('/settings/edit', ['controller' => 'Settings', 'action' => 'edit']);

        // Fees routes
        $builder->connect('/fees', ['controller' => 'Fees', 'action' => 'index']);
        $builder->connect('/fees/add', ['controller' => 'Fees', 'action' => 'add']);
        $builder->connect('/fees/edit/{id}', ['controller' => 'Fees', 'action' => 'edit'])
            ->setPass(['id']);
        $builder->connect('/fees/delete/{id}', ['controller' => 'Fees', 'action' => 'delete'])
            ->setPass(['id']);

        // Programs routes
        $builder->connect('/programs', ['controller' => 'Programs', 'action' => 'index']);
        $builder->connect('/programs/add', ['controller' => 'Programs', 'action' => 'add']);
        $builder->connect('/programs/edit/{id}', ['controller' => 'Programs', 'action' => 'edit'])
            ->setPass(['id']);
        $builder->connect('/programs/delete/{id}', ['controller' => 'Programs', 'action' => 'delete'])
            ->setPass(['id']);

        // Quoting Partner Permissions routes
        $builder->connect('/quoting-partner-permissions', ['controller' => 'QuotingPartnerPermissions', 'action' => 'index']);
        $builder->connect('/quoting-partner-permissions/edit/{id}', ['controller' => 'QuotingPartnerPermissions', 'action' => 'edit'])
            ->setPass(['id']);
        $builder->connect('/quoting-partner-permissions/delete/{id}', ['controller' => 'QuotingPartnerPermissions', 'action' => 'delete'])
            ->setPass(['id']);

        $builder->fallbacks(DashedRoute::class);
    });

    // API prefix scope (unchanged)
    $routes->prefix('api', function (RouteBuilder $builder): void {
        $builder->setExtensions(['json']);
        $builder->connect('/{controller}/{action}/*', []);
    });
};

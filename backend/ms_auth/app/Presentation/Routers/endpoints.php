<?php

use App\Presentation\Middleware\AuthMiddleware;
use App\Presentation\Repositories\AuthRepository;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('/auth', function (RouteCollectorProxy $group) {
        $group->post('/login', [AuthRepository::class, 'login']);
        $group->post('/logout', [AuthRepository::class, 'logout'])->add(new AuthMiddleware());
        $group->get('/validar', [AuthRepository::class, 'validar_sesion'])->add(new AuthMiddleware());
    });
};
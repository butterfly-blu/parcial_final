<?php

use App\Presentation\Middleware\AuthMiddleware;
use App\Presentation\Repositories\ConductoresRepository;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('/conductores', function (RouteCollectorProxy $group) {
        $group->get('', [ConductoresRepository::class, 'get_all']);
        $group->get('/{id:[0-9]+}', [ConductoresRepository::class, 'get_one']);
    })->add(new AuthMiddleware());
};
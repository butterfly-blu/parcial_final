<?php

use App\Presentation\Repositories\AuthRepository;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('/auth', function (RouteCollectorProxy $group) {
        $group->post('/login', [AuthRepository::class, 'login']);
    });
};
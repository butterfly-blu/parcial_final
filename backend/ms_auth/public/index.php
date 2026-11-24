<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Models\User;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Base/database.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Microservicio ms-auth en ejecuciónnnn");
    return $response;
});

// Prueba temporal de conexión a la base de datos
$app->get('/prueba-db', function (Request $request, Response $response, $args) {
    $usuarios = User::all();
    $response->getBody()->write($usuarios->toJson());
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
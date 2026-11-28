<?php
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

// Base de datos propia del microservicio
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'ms_conductores',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Base de datos de autenticación (solo para validar el token)
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'ms_auth',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
], 'auth');

$capsule->setAsGlobal();
$capsule->bootEloquent();
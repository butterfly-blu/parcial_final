<?php

namespace App\Presentation\Repositories;

use App\Controllers\AuthController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthRepository
{
    function login(Request $req, Response $resp, $args): Response
    {
        $body = $req->getBody()->getContents();
        $data = json_decode($body, true);

        $login = trim($data['login'] ?? '');
        $contrasena = $data['contrasena'] ?? '';

        if ($login === '' || $contrasena === '') {
            return $this->json($resp, ["message" => "Debe ingresar usuario o correo y contraseña"], 400);
        }

        try {
            $controller = new AuthController();
            $user = $controller->login($login, $contrasena);
        } catch (Exception $e) {
            $status = in_array($e->getCode(), [401, 403]) ? $e->getCode() : 500;
 $message = $e->getMessage();            return $this->json($resp, ["message" => $message], $status);
        }

        return $this->json($resp, [
            "message" => "Inicio de sesión exitoso",
            "token"   => $user->token,
            "usuario" => $user,
        ], 200);
    }

        function logout(Request $req, Response $resp, $args): Response
    {
        $controller = new AuthController();
        $controller->logout($req->getAttribute('user'));

        return $this->json($resp, ["message" => "Sesión cerrada correctamente"], 200);
    }

    function validar_sesion(Request $req, Response $resp, $args): Response
    {
        return $this->json($resp, [
            "message" => "Sesión activa",
            "usuario" => $req->getAttribute('user'),
        ], 200);
    }
    
    private function json(Response $resp, array $data, int $status): Response
    {
        $resp->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $resp
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }
}
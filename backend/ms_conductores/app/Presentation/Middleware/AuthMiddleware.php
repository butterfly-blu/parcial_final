<?php

namespace App\Presentation\Middleware;

use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Psr7\Response as SlimResponse;

class AuthMiddleware
{
    function __invoke(Request $req, Handler $handler): Response
    {
        $header = $req->getHeaderLine('Authorization');

        if (!preg_match('/^Bearer\s+(\S+)$/i', $header, $matches)) {
            return $this->error("Token de sesión no proporcionado", 401);
        }

        $user = User::where('token', $matches[1])->first();

        if (empty($user)) {
            return $this->error("Token inválido", 401);
        }

        if (!$user->sesion_activa) {
            return $this->error("La sesión no está activa", 401);
        }

        if ($user->estado !== 'activo') {
            return $this->error("El usuario se encuentra inactivo", 403);
        }

        return $handler->handle($req->withAttribute('user', $user));
    }

    private function error(string $message, int $status): Response
    {
        $resp = new SlimResponse();
        $resp->getBody()->write(json_encode(["message" => $message], JSON_UNESCAPED_UNICODE));
        return $resp
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }
}
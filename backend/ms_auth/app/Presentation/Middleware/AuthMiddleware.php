<?php

namespace App\Presentation\Middleware;

use App\Controllers\AuthController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Psr7\Response as SlimResponse;

// Exige la cabecera "Authorization: Bearer <token>" y valida la sesión
class AuthMiddleware
{
    function __invoke(Request $req, Handler $handler): Response
    {
        $header = $req->getHeaderLine('Authorization');

        if (!preg_match('/^Bearer\s+(\S+)$/i', $header, $matches)) {
            return $this->error("Token de sesión no proporcionado", 401);
        }

        try {
            $controller = new AuthController();
            $user = $controller->validar_token($matches[1]);
        } catch (Exception $e) {
            $status = in_array($e->getCode(), [401, 403]) ? $e->getCode() : 500;
            $message = $status === 500 ? "Error interno del servidor" : $e->getMessage();
            return $this->error($message, $status);
        }

        // Guarda el usuario en la petición para usarlo en el endpoint
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
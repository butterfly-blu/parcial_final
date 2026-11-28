<?php

namespace App\Presentation\Repositories;

use App\Controllers\ConductoresController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ConductoresRepository
{
    function get_all(Request $req, Response $resp, $args): Response
    {
        $filtros = $req->getQueryParams();
        $controller = new ConductoresController();
        $rows = $controller->all($filtros);

        return $this->json($resp, $rows->toArray(), 200);
    }

    function get_one(Request $req, Response $resp, $args): Response
    {
        $controller = new ConductoresController();
        $row = $controller->find_by_id((int) $args['id']);

        if (empty($row)) {
            return $this->json($resp, ["message" => "Conductor no encontrado"], 404);
        }

        return $this->json($resp, $row->toArray(), 200);
    }

    private function json(Response $resp, array $data, int $status): Response
    {
        $resp->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $resp
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }
}
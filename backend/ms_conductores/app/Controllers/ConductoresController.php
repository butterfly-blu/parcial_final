<?php

namespace App\Controllers;

use App\Models\Conductor;
use Illuminate\Database\Eloquent\Collection;

class ConductoresController
{
    // Listar con filtros opcionales: documento, licencia, estado
    function all(array $filtros): Collection
    {
        $query = Conductor::query();

        if (!empty($filtros['documento'])) {
            $query->where('documento', 'like', '%' . $filtros['documento'] . '%');
        }

        if (!empty($filtros['licencia'])) {
            $query->where('numero_licencia', 'like', '%' . $filtros['licencia'] . '%');
        }

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        return $query->orderBy('apellidos')->get();
    }

    function find_by_id(int $id): Conductor|null
    {
        return Conductor::find($id);
    }
}
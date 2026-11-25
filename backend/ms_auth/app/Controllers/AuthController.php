<?php

namespace App\Controllers;

use App\Models\User;
use Exception;

class AuthController
{
    function login(string $login, string $contrasena): User
    {
        // Buscar por usuario o por correo
        $user = User::where('usuario', $login)
            ->orWhere('correo', $login)
            ->first();

        if (empty($user) || !$this->verificar_contrasena($user, $contrasena)) {
            throw new Exception("Usuario o contraseña incorrectos", 401);
        }

        if ($user->estado !== 'activo') {
            throw new Exception("El usuario se encuentra inactivo", 403);
        }

        // Token simple y sesión activa
        $user->token = bin2hex(random_bytes(32));
        $user->sesion_activa = true;
        $user->save();

        return $user;
    }

    // La base entregada guarda las contraseñas en texto plano (admin123).
    // También acepta contraseñas cifradas con password_hash().
    private function verificar_contrasena(User $user, string $contrasena): bool
    {
        $info = password_get_info($user->contrasena);
        if (!empty($info['algo'])) {
            return password_verify($contrasena, $user->contrasena);
        }
        return hash_equals($user->contrasena, $contrasena);
    }
}

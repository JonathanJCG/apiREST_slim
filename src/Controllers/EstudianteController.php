<?php

namespace App\Controllers;

use App\Models\Estudiante;
use App\Models\Usuario;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EstudianteController
{
    public function login(Request $request, Response $response, $args)
    {
        $data = json_decode( $request->getBody(), true);
        $user = $data['username'];
        $pass = $data['password'];

        if (!$user || !$pass) {
            $response->getBody()->write(json_encode(array('error' => 'Se requieren nombre de usuario y contraseña')));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $usuario = Estudiante::where('user', $user)
            ->where('pass', $pass)
            ->where('estado', 1)
            ->first();

        // Verificar si se encontró el usuario
        if (!$usuario) {
            $response->getBody()->write(json_encode(array('error' => 'Usuario no encontrado')));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
        else{

            $token = JWT::encode($usuario, 'educarparalavida', 'HS256');
            $response->getBody()->write(json_encode(['token' => $token], JSON_UNESCAPED_UNICODE));
            return $response->withHeader('Content-Type', 'application/json');
        }

    }

}
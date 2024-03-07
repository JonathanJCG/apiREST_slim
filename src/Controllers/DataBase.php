<?php
// src/Controllers/UsuarioController.php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Usuario;


use PDO;

class DataBase
{

    public function VerificarDataBase(Request $request, Response $response, $args)
    {
        $dsn = 'mysql:host=localhost;charset=utf8mb4';
        $username = 'root';
        $password = '';
        $basedatos = 'jejeejje';

        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SHOW DATABASES LIKE '$basedatos'";
        $statement = $pdo->query($query);

        $databaseExists = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$databaseExists) {
            // Consulta para crear la base de datos
            $createQuery = "CREATE DATABASE $basedatos";
            $pdo->exec($createQuery);
            $response->getBody()->write("Base de datos creada correctamente.");

            Usuario::crearTabla();
            echo "Tabla de usuarios creada correctamente.";
        } else {
            $response->getBody()->write("La base de datos ya existe.");
        }

        return $response->withHeader('Content-Type', 'text/plain');
    }


}

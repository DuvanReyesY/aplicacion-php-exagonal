<?php
// src/Controller/UserController.php
namespace Controller;

use Aplication\Services\GetAllUsersService;
use Aplication\Services\CreateUserService;

class UserController
{
    private GetAllUsersService $getAllUsersService;
    private CreateUserService $createUserService;

    public function __construct(GetAllUsersService $getAllUsersService, CreateUserService $createUserService)
    {
        $this->getAllUsersService = $getAllUsersService;
        $this->createUserService = $createUserService;
    }

    public function listUsers()
    {
        // Aquí deberías obtener los usuarios reales
        return [
            ['id' => 1, 'name' => 'Demo User']
        ];
    }

    public function createUser($data)
    {
        // Aquí deberías crear el usuario real
        return ['message' => 'Usuario creado', 'data' => $data];
    }
}

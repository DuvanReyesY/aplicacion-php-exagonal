<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

// --- Dominio ---
require_once __DIR__ . '/../Domain/Exceptions/InvalidUserIdException.php';
require_once __DIR__ . '/../Domain/Exceptions/InvalidUserNameException.php';
require_once __DIR__ . '/../Domain/Exceptions/InvalidUserEmailException.PHP';
require_once __DIR__ . '/../Domain/Exceptions/InvalidUserPasswordException.php';
require_once __DIR__ . '/../Domain/Exceptions/InvalidUserRoleException.php';
require_once __DIR__ . '/../Domain/Exceptions/InvalidUserStatusException.php';
require_once __DIR__ . '/../Domain/Exceptions/UserNotFoundException.php';
require_once __DIR__ . '/../Domain/Exceptions/UserAlreadyExistsException.php';
require_once __DIR__ . '/../Domain/Exceptions/InvalidCredentialsException.php';
require_once __DIR__ . '/../Domain/Enums/UserRoleEnum.php';
require_once __DIR__ . '/../Domain/Enums/UserStatusEnum.php';
require_once __DIR__ . '/../Domain/ValueObjects/UserId.php';
require_once __DIR__ . '/../Domain/ValueObjects/UserName.php';
require_once __DIR__ . '/../Domain/ValueObjects/UserEmail.php';
require_once __DIR__ . '/../Domain/ValueObjects/UserPassword.php';
require_once __DIR__ . '/../Domain/Models/UserModel.php';

// --- Puertos ---
require_once __DIR__ . '/../Aplication/Ports/Out/SaveUserPort.php';
require_once __DIR__ . '/../Aplication/Ports/Out/GetAllUsersPort.php';
require_once __DIR__ . '/../Aplication/Ports/Out/GetUserByIdPort.php';
require_once __DIR__ . '/../Aplication/Ports/Out/GetUserByEmailPort.php';
require_once __DIR__ . '/../Aplication/Ports/Out/UpdateUserPort.php';
require_once __DIR__ . '/../Aplication/Ports/Out/DeleteUserPort.php';
require_once __DIR__ . '/../Aplication/Ports/In/CreateUserUseCase.php';
require_once __DIR__ . '/../Aplication/Ports/In/GetAllUsersUseCase.php';
require_once __DIR__ . '/../Aplication/Ports/In/GetUserByIdUseCase.php';
require_once __DIR__ . '/../Aplication/Ports/In/UpdateUserUseCase.php';
require_once __DIR__ . '/../Aplication/Ports/In/DeleteUserUseCase.php';
require_once __DIR__ . '/../Aplication/Ports/In/LoginUseCase.php';

// --- Infraestructura ---
require_once __DIR__ . '/../Infrastructure/Adapters/Persistence/MySql/Entity/UserEntity.php';
require_once __DIR__ . '/../Infrastructure/Adapters/Persistence/MySql/Dto/UserPersistenceDto.php';
require_once __DIR__ . '/../Infrastructure/Adapters/Persistence/MySql/Mapper/UserPersistenceMapper.php';
require_once __DIR__ . '/../Infrastructure/Adapters/Persistence/MySql/Repository/MySqlUserRepository.php';

// --- Aplicación ---
require_once __DIR__ . '/../Aplication/Services/DTO/Commands/CreateUserCommand.php';
require_once __DIR__ . '/../Aplication/Services/DTO/Commands/UpdateUserCommand.php';
require_once __DIR__ . '/../Aplication/Services/DTO/Commands/DeleteUserCommand.php';
require_once __DIR__ . '/../Aplication/Services/DTO/Commands/LoginCommand.php';
require_once __DIR__ . '/../Aplication/Services/DTO/Queries/GetAllUsersQuery.php';
require_once __DIR__ . '/../Aplication/Services/DTO/Queries/GetUserByIdQuery.php';
require_once __DIR__ . '/../Aplication/Mappers/UserApplicationMapper.php';
require_once __DIR__ . '/../Aplication/Services/CreateUserService.php';
require_once __DIR__ . '/../Aplication/Services/GetAllUsersService.php';
require_once __DIR__ . '/../Aplication/Services/GetUserByIdService.php';
require_once __DIR__ . '/../Aplication/Services/UpdateUserService.php';
require_once __DIR__ . '/../Aplication/Services/DeleteUserService.php';
require_once __DIR__ . '/../Aplication/Services/LoginService.php';

// -----------------------------------------------------------------------
// Cabeceras JSON globales
// -----------------------------------------------------------------------
header('Content-Type: application/json; charset=utf-8');

// -----------------------------------------------------------------------
// Conexión a BD
// -----------------------------------------------------------------------
$dbConfig = require __DIR__ . '/../config/database.php';

use Infrastructure\Adapters\Persistence\MySql\Config\Conection;

try {
    $con = new Conection(
        $dbConfig['host'],
        (int) $dbConfig['port'],
        $dbConfig['database'],
        $dbConfig['username'],
        $dbConfig['password'],
        $dbConfig['charset']
    );
    $pdo = $con->createPdo();
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos', 'detalle' => $e->getMessage()]);
    exit;
}

// -----------------------------------------------------------------------
// Repositorio compartido (implementa todos los puertos)
// -----------------------------------------------------------------------
$repo = new \Infrastructure\Adapters\Persistence\MySql\Repository\MySqlUserRepository($pdo);

// -----------------------------------------------------------------------
// Servicios
// -----------------------------------------------------------------------
$createUserService    = new CreateUserService($repo, $repo);
$getAllUsersService    = new GetAllUsersService($repo);
$getUserByIdService   = new GetUserByIdService($repo);
$updateUserService    = new UpdateUserService($repo, $repo, $repo);
$deleteUserService    = new DeleteUserService($repo, $repo);
$loginService         = new LoginService($repo);

// -----------------------------------------------------------------------
// Router simple
// -----------------------------------------------------------------------
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Quitar barra final opcional
$uri = rtrim($uri, '/') ?: '/';

try {

    // GET /users
    if ($uri === '/users' && $method === 'GET') {
        $users = $getAllUsersService->execute(new GetAllUsersQuery());
        echo json_encode(UserApplicationMapper::fromModelsToArray($users));
        exit;
    }

    // POST /users
    if ($uri === '/users' && $method === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $command = new CreateUserCommand(
            $body['id']       ?? '',
            $body['name']     ?? '',
            $body['email']    ?? '',
            $body['password'] ?? '',
            $body['role']     ?? ''
        );
        $user = $createUserService->execute($command);
        http_response_code(201);
        echo json_encode(UserApplicationMapper::fromModelToArray($user));
        exit;
    }

    // GET /users/{id}
    if (preg_match('#^/users/([^/]+)$#', $uri, $m) && $method === 'GET') {
        $user = $getUserByIdService->execute(new GetUsersByIdQuery($m[1]));
        echo json_encode(UserApplicationMapper::fromModelToArray($user));
        exit;
    }

    // PUT /users/{id}
    if (preg_match('#^/users/([^/]+)$#', $uri, $m) && $method === 'PUT') {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $command = new UpdateUserCommand(
            $m[1],
            $body['name']     ?? '',
            $body['email']    ?? '',
            $body['password'] ?? '',
            $body['role']     ?? '',
            $body['status']   ?? ''
        );
        $user = $updateUserService->execute($command);
        echo json_encode(UserApplicationMapper::fromModelToArray($user));
        exit;
    }

    // DELETE /users/{id}
    if (preg_match('#^/users/([^/]+)$#', $uri, $m) && $method === 'DELETE') {
        $deleteUserService->execute(new DeleteUserCommand($m[1]));
        http_response_code(204);
        exit;
    }

    // POST /login
    if ($uri === '/login' && $method === 'POST') {
        $body    = json_decode(file_get_contents('php://input'), true) ?? [];
        $command = new LoginCommand($body['email'] ?? '', $body['password'] ?? '');
        $user    = $loginService->execute($command);
        echo json_encode(UserApplicationMapper::fromModelToArray($user));
        exit;
    }

    http_response_code(404);
    echo json_encode(['error' => 'Ruta no encontrada']);

} catch (UserNotFoundException $e) {
    http_response_code(404);
    echo json_encode(['error' => $e->getMessage()]);
} catch (UserAlreadyExistsException $e) {
    http_response_code(409);
    echo json_encode(['error' => $e->getMessage()]);
} catch (InvalidCredentialsException $e) {
    http_response_code(401);
    echo json_encode(['error' => $e->getMessage()]);
} catch (\InvalidArgumentException $e) {
    http_response_code(422);
    echo json_encode(['error' => $e->getMessage()]);
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error interno del servidor', 'detalle' => $e->getMessage()]);
}

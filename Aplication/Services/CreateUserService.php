<?php

declare(strict_types=1);

require_once __DIR__ . '/../Ports/In/CreateUserUseCase.php';
require_once __DIR__ . '/../Ports/Out/SaveUserPort.php';
require_once __DIR__ . '/../Ports/Out/GetUserByEmailPort.php';
require_once __DIR__ . '/../Mappers/UserApplicationMapper.php';
require_once __DIR__ . '/../../Domain/Exceptions/UserAlreadyExistsException.php';
require_once __DIR__ . '/../../Domain/ValueObjects/UserEmail.php';

final class CreateUserService implements CreateUserUseCase
{
    private SaveUserPort $saveUserPort;
    private GetUserByEmailPort $getUserByEmailPort;

    public function __construct(SaveUserPort $saveUserPort, GetUserByEmailPort $getUserByEmailPort)
    {
        $this->saveUserPort = $saveUserPort;
        $this->getUserByEmailPort = $getUserByEmailPort;
    }

    public function execute(CreateUserCommand $command): UserModel
    {
        $email = new UserEmail($command->getEmail());
        $existingUser = $this->getUserByEmailPort->getByEmail($email);

        if ($existingUser !== null){
            throw UserAlreadyExistsException::becauseEmailAlreadyExist($email->value());
        }

        $user = UserApplicationMapper::fromCreateCommandToModel($command);

        return $this->saveUserPort->save($user);

    }

}
/* 1. Recibe CreateUserCommand
2. Construye UserEmail desde command.getEmail()
→ Si el email es inválido, UserEmail lanza InvalidUserEmailException aquí
3. Llama a GetUserByEmailPort.getByEmail(email)
→ Si retorna un usuario → lanza UserAlreadyExistsException
4. Llama a UserApplicationMapper.fromCreateCommandToModel(command)
→ Construye el UserModel completo con todos los Value Objects
→ Si cualquier dato es inválido, el VO lanza su excepción
5. Llama a SaveUserPort.save(user)
6. Retorna el UserModel guardado */
?>
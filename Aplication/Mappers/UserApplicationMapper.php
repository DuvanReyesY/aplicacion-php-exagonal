<?php

declare(strict_types=1);

require_once __DIR__ . '/../Services/Dto/Commands/CreateUserCommand.php';
require_once __DIR__ . '/../Services/Dto/Commands/UpdateUserCommand.php';
require_once __DIR__ . '/../Services/Dto/Commands/DeleteUserCommand.php';
require_once __DIR__ . '/../Services/Dto/Queries/GetUserByIdQuery.php';
require_once __DIR__ . '/../../Domain/Models/UserModel.php';
require_once __DIR__ . '/../../Domain/ValueObjects/UserId.php';
require_once __DIR__ . '/../../Domain/ValueObjects/UserName.php';
require_once __DIR__ . '/../../Domain/ValueObjects/UserEmail.php';
require_once __DIR__ . '/../../Domain/ValueObjects/UserPassword.php';
require_once __DIR__ . '/../../Domain/Enums/UserStatusEnum.php';

final class UserApplicationMapper
{
    public static function fromCreateCommandToModel(CreateUserCommand $command): UserModel
    {
        return new UserModel(
            new UserId($command->getId()),
            new UserName($command->getName()),
            new UserEmail($command->getEmail()),
            UserPassword::fromPlainText($command->getPassword()),
            $command->getRole(),
            UserStatusEnum::PENDING
        );
    }

    public static function fromUpdateCommandToModel(UpdateUserCommand $command): UserModel
    {
        return new UserModel(
            new UserId($command->getId()),
            new UserName($command->getName()),
            new UserEmail($command->getEmail()),
            UserPassword::fromPlainText($command->getPassword()),
            $command->getRole(),
            $command->getStatus()
        );
    }

    // Corregido: la clase real se llama GetUsersByIdQuery (así está definida en el archivo)
    public static function fromGetUsersByIdQueryToUserId(GetUsersByIdQuery $query): UserId
    {
        return new UserId($query->getId());
    }

    public static function fromDeleteCommandToUserId(DeleteUserCommand $command): UserId
    {
        return new UserId($command->getId());
    }

    public static function fromModelToArray(UserModel $user): array
    {
        return [
            'id'       => $user->id()->value(),
            'name'     => $user->name()->value(),
            'email'    => $user->email()->value(),
            'role'     => $user->role(),
            'status'   => $user->status(),
            // La contraseña no se expone en las respuestas
        ];
    }

    public static function fromModelsToArray(array $users): array
    {
        $result = [];
        foreach ($users as $user) {
            $result[] = self::fromModelToArray($user);
        }
        return $result;
    }
}

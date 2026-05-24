<?php

declare(strict_types=1);

namespace Infrastructure\Adapters\Persistence\MySql\Mapper;

use Infrastructure\Adapters\Persistence\MySql\Entity\UserEntity;

// Domain classes cargadas vía require_once desde el repositorio
// (se usan las clases globales porque no tienen namespace propio)

final class UserPersistenceMapper
{
    /**
     * Convierte una fila de BD (array asociativo) en UserEntity.
     */
    public static function fromRowToEntity(array $row): UserEntity
    {
        return new UserEntity(
            $row['id'],
            $row['name'],
            $row['email'],
            $row['password'],
            $row['role'],
            $row['status'],
            $row['created_at'] ?? null,
            $row['updated_at'] ?? null
        );
    }

    /**
     * Convierte UserEntity en UserModel del dominio.
     * Requiere que las clases del dominio estén cargadas.
     */
    public static function fromEntityToModel(UserEntity $entity): \UserModel
    {
        return new \UserModel(
            new \UserId($entity->id()),
            new \UserName($entity->name()),
            new \UserEmail($entity->email()),
            \UserPassword::fromHash($entity->password()),
            $entity->role(),
            $entity->status()
        );
    }

    /**
     * Convierte UserModel en array para persistir en BD.
     */
    public static function fromModelToRow(\UserModel $user): array
    {
        return [
            'id'       => $user->id()->value(),
            'name'     => $user->name()->value(),
            'email'    => $user->email()->value(),
            'password' => $user->password()->value(),
            'role'     => $user->role(),
            'status'   => $user->status(),
        ];
    }
}

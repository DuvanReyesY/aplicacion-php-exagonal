<?php

declare(strict_types=1);

namespace Infrastructure\Adapters\Persistence\MySql\Repository;

use PDO;
use Infrastructure\Adapters\Persistence\MySql\Mapper\UserPersistenceMapper;

/**
 * Implementa todos los puertos de salida relacionados con usuarios.
 * Requiere que las interfaces y clases del dominio estén cargadas previamente.
 */
final class MySqlUserRepository implements
    \SaveUserPort,
    \GetAllUsersPort,
    \GetUserByIdPort,
    \GetUserByEmailPort,
    \UpdatedUserPort,
    \DeleteUserPort
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // -------------------------------------------------------------------------
    // SaveUserPort
    // -------------------------------------------------------------------------
    public function save(\UserModel $user): \UserModel
    {
        $sql = 'INSERT INTO users (id, name, email, password, role, status)
                VALUES (:id, :name, :email, :password, :role, :status)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(UserPersistenceMapper::fromModelToRow($user));

        return $user;
    }

    // -------------------------------------------------------------------------
    // GetAllUsersPort
    // -------------------------------------------------------------------------
    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM users ORDER BY id');
        $rows = $stmt->fetchAll();

        $users = [];
        foreach ($rows as $row) {
            $entity  = UserPersistenceMapper::fromRowToEntity($row);
            $users[] = UserPersistenceMapper::fromEntityToModel($entity);
        }

        return $users;
    }

    // -------------------------------------------------------------------------
    // GetUserByIdPort
    // -------------------------------------------------------------------------
    public function getById(\UserId $userId): ?\UserModel
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $userId->value()]);
        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        $entity = UserPersistenceMapper::fromRowToEntity($row);
        return UserPersistenceMapper::fromEntityToModel($entity);
    }

    // -------------------------------------------------------------------------
    // GetUserByEmailPort
    // -------------------------------------------------------------------------
    public function getByEmail(\UserEmail $email): ?\UserModel
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email->value()]);
        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        $entity = UserPersistenceMapper::fromRowToEntity($row);
        return UserPersistenceMapper::fromEntityToModel($entity);
    }

    // -------------------------------------------------------------------------
    // UpdatedUserPort
    // -------------------------------------------------------------------------
    public function update(\UserModel $user): \UserModel
    {
        $sql = 'UPDATE users
                SET name     = :name,
                    email    = :email,
                    password = :password,
                    role     = :role,
                    status   = :status
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(UserPersistenceMapper::fromModelToRow($user));

        return $user;
    }

    // -------------------------------------------------------------------------
    // DeleteUserPort
    // -------------------------------------------------------------------------
    public function delete(\UserId $userId): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $userId->value()]);
    }
}

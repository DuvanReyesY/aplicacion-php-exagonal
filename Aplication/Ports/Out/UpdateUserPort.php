<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../Domain/Models/UserModel.php';

// Nota: el nombre es UpdatedUserPort (con 'd') para mantener compatibilidad
// con los servicios existentes (UpdateUserService e implementación en repository)
interface UpdatedUserPort
{
    public function update(UserModel $user): UserModel;
}

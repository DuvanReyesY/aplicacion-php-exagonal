<?php

declare(strict_types=1);

require_once __DIR__ . '/../Ports/In/GetUserByIdUseCase.php';
require_once __DIR__ . '/../Ports/Out/GetUserByIdPort.php';
require_once __DIR__ . '/../Mappers/UserApplicationMapper.php';
require_once __DIR__ . '/../../Domain/Exceptions/UserNotFoundException.php';

final class GetUserByIdService implements GetUserByIdUseCase
{
    private GetUserByIdPort $getUserByIdPort;

    public function __construct(GetUserByIdPort $getUserByIdPort)
    {
        $this->getUserByIdPort = $getUserByIdPort;
    }

    public function execute(GetUsersByIdQuery $query): UserModel
    {
        $userId = UserApplicationMapper::fromGetUsersByIdQueryToUserId($query);
        $user = $this->getUserByIdPort->getById($userId);

        if ($user === null){
            throw UserNotFoundException::becauseIdWasNotFound($userId->value());
        }

        return $user;
    }
}

?>
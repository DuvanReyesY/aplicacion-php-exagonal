<?php

declare(strict_types=1);

class InvalidCredentialsException extends \RuntimeException
{
    public static function becauseCredentialsAreInvalid(): self
    {
        return new self('Las credenciales son inválidas.');
    }

    public static function becauseUserIsNotActive(): self
    {
        return new self('El usuario no está activo.');
    }
}

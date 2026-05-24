<?php

class UserAlreadyExistsException extends InvalidArgumentException
{
    public static function becauseEmailAlreadyExist($email)
    {
        return new self('ya existe un usuario con el correo: ' . $email);
    }
}
?>
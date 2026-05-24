<?php

class InvalidUserPasswordException extends InvalidArgumentException
{
    public static function becauseLenghtIsTooShort($min)
    {
        return new self('La contraseña del usuario debe tener minimo '.$min.' caracteres');
    }

    public static function becauseValueIsEmpty()
    {
        return new self('La contraseña no puede estar vacia');
    }
}
?>
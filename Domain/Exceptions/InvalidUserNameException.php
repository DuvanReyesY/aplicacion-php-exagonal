<?php

class InvalidUserNameException extends InvalidArgumentException
{
    public static function becauseValueIsEmpty()
    {
        return new self('el nombre del usuario no puede estar vacio');
    }

    public static function becauseLenghtIsTooShort($min)
    {
        return new self('El nombre delusuario debe tener minimo '. $min .' caracteres');
    }
}
?>
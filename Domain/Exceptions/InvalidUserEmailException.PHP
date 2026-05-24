<?php

class InvalidUserEmailException extends InvalidArgumentException
{
    public static function becauseValueIsEmpty()
    {
        return new self('el correo no puede estar vacio');
    }

    public static function becauseFormatIsInvalid($email)
    {
        return new self('el formato de correo no es valido:'. $email);
    }
}
?>
<?php

class InvalidUserStatusException extends InvalidArgumentException
{
    public static function becauseValueIsInvalid($status)
    {
        return new self('El estado '. $status. ' es invalido');
    }
}
?>
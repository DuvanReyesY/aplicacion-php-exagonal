<?php

class InvalidUserRoleException extends InvalidArgumentException
{
    public static function becauseValueIsInvalid($role)
    {
        return new self('el rol '. $role . ' es invalido');
    }
}
?>
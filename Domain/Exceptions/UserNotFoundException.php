<?php

class UserNotFoundException extends InvalidArgumentException
{
    public static function becauseIdWasNotFound($id)
    {
        return new self('No se encontro usuario con el id: ' . $id);
    }
}

?>
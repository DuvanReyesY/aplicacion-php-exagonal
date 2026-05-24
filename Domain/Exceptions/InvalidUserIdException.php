<?php

class InvalidUserIdException extends InvalidArgumentException
{
    public static function becauseValueIsEmpty()
    {
        return new self ('el id del usuario no puede estar vacio');
    }
}

?>
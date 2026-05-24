<?php

require_once __DIR__ . '/../Exceptions/InvalidUserStatusException.php';

class UserStatusEnum
{
    const ACTIVE = 'ACTIVE';
    const INACTIVE = 'INACTIVE';
    const PENDING = 'PENDING';
    const BLOCKED = 'BLOCKED';

    public static function values()
    {
        return array(self::ACTIVE, self::INACTIVE, self::PENDING, self::BLOCKED);
    }

    public static function isValid($value)
    {
        return in_array($value, self::values(), true);
    }

    public static function ensureIsValid($value)
    {
        if (!self::isValid($value)){
            throw InvalidUserStatusException::becauseValueIsInvalid($value);
        }
            
    }

}

?>
<?php

require_once __DIR__ .'/../Exceptions/InvalidUserRoleException.php';
class UserRoleEnum
{
    const ADMIN = 'admin';
    const MEMBER = 'member';
    const REVIEWER = 'reviewer';


    public static function values()
    {
        return array(self:: ADMIN, self::MEMBER, self::REVIEWER);
    }

    public static function isValid($value)
    {
        return in_array($value, self::values(), true);
    }

    public static function ensureIsValid($value)
    {
        if(!self::isValid($value)) {
            throw InvalidUserRoleException::becauseValueIsInvalid($value);
        }
    }

}

?>
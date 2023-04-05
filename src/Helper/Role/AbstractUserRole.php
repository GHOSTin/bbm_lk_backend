<?php


namespace App\Helper\Role;

class AbstractUserRole
{
    const ROLE_STUDENT = 1;
    const ROLE_PARENT = 2;
    const ROLE_TEACHER = 3;

    protected static $roleNames = [
        self::ROLE_STUDENT => 'Студент',
        self::ROLE_PARENT => 'Родитель',
        self::ROLE_TEACHER => 'Учитель',
    ];

    protected static $roleNamesForApiExternal = [
        self::ROLE_STUDENT => 'student',
        self::ROLE_PARENT => 'parent',
        self::ROLE_TEACHER => 'teacher',
    ];

    public static function getRoleName($key)
    {
        if (isset(static::$roleNames)) {
            if (array_key_exists($key, static::$roleNames)) {
                return static::$roleNames[$key];
            }
        }

        return null;
    }

    public static function getRoleNameForApiExternal($key)
    {
        if (isset(static::$roleNamesForApiExternal)) {
            if (array_key_exists($key, static::$roleNamesForApiExternal)) {
                return static::$roleNamesForApiExternal[$key];
            }
        }

        return null;
    }

    public static function getRoleNames()
    {
        return static::$roleNames;
    }

    public static function getRoleNamesForApiExternal()
    {
        return static::$roleNamesForApiExternal;
    }
}
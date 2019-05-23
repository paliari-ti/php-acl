<?php

namespace Acl;

use Paliari\Utils\A;

class AclWhiteList
{

    protected static $_keys = [];

    public static function setKey(string $key, bool $value)
    {
        static::$_keys[$key] = $value;
    }

    public static function isSkip($key)
    {
        return A::get(static::$_keys, $key);
    }

}

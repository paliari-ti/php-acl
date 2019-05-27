<?php

namespace Paliari\PhpAcl;

class AclOperator
{

    public static function keys($call)
    {
        return explode('.', static::transformKeys($call));
    }

    public static function transformKeys($call)
    {
        $call = static::tableize(ltrim($call, '\\'));

        return str_replace(['controllers\\', '\\', ':'], ['', '.', '.'], $call);
    }

    public static function contains($permissions, $tree)
    {
        $ret = [];
        foreach ($permissions as $key => $permission) {
            if (isset($tree[$key])) {
                $ret[$key] = true === $permission ? $tree[$key] : static::contains($permissions[$key], $tree[$key]);
            }
        }

        return $ret;
    }

    public static function allKeys($all)
    {
        return array_keys(static::deepKeys($all, []));
    }

    protected static function deepKeys($all, $words = [])
    {
        foreach ($all as $k => $v) {
            $words[$k] = true;
            if (is_array($v)) {
                $words = static::deepKeys($v, $words);
            }
        }

        return $words;
    }

    public static function tableize($word)
    {
        return strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $word));
    }

}

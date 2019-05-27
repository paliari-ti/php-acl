<?php

namespace Paliari\PhpAcl;

use Paliari\Utils\A;

class AclControl
{

    protected $tree = [];

    private static $_instance;

    /**
     * @return static
     */
    public static function instance()
    {
        return static::$_instance = static::$_instance ?: new static();
    }

    public function allPermissions($routes)
    {
        if (empty($this->tree)) {
            $this->parse($routes);
        }

        return $this->tree;
    }

    public function parse($routes)
    {
        foreach ($routes as $call) {
            if ($keys = $this->preparePermission($call)) {
                $this->add($keys, true);
            }
        }

        return $this->tree;
    }

    public function preparePermission($call)
    {
        if ($this->isRestrict($call)) {
            return AclOperator::transformKeys($call);
        }

        return null;
    }

    protected function isRestrict($call)
    {
        return AclWhiteList::isSkip($call);
    }

    protected function add($keys, $value)
    {
        A::setDeepKey($this->tree, $keys, $value);
    }

    /**
     * Obtem todas permissoes permitidas em $permissions,
     * util para a manutencao do perfil de usuario.
     *
     * @param array $permissions
     * @param array $routes
     *
     * @return array
     */
    public function contains($permissions, $routes)
    {
        return AclOperator::contains($permissions, $this->allPermissions($routes));
    }

    /**
     * Obtem todas keys, independente da profundidade, em um array linear.
     *
     * @param array $all
     *
     * @return array
     */
    public function allKeys($all = [])
    {
        return AclOperator::allKeys($all ?: $this->tree);
    }

}

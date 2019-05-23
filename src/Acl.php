<?php

namespace Acl;

class Acl
{

    protected $_permissions_tree = [];

    /**
     * Acl constructor.
     *
     * @param array $permissions_tree
     */
    public function __construct($permissions_tree)
    {
        $this->_permissions_tree = $permissions_tree;
    }

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function allowed($keys)
    {
        $tree = $this->_permissions_tree;
        foreach ($keys as $key) {
            $tree = $this->childTree($tree, $key);
            if ($this->isBreak($tree)) {
                return (bool)$tree;
            }
        }

        return false;
    }

    protected function isBreak($tree)
    {
        return true === $tree || empty($tree);
    }

    protected function childTree($tree, $key)
    {
        return isset($tree[$key]) ? $tree[$key] : null;
    }

}

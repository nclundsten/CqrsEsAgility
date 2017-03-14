<?php

namespace CqrsEsAgility\Config;

class NamespacesConfig extends \ArrayObject
{
    public function __construct(array $config)
    {
        //construct with default keys
        parent::__construct([
        ]);

        $config = array_merge(
            [
            ],
            $config
        );

        foreach ($config as $key => $val) {
            $this->offsetSet($key, $val);
        }
    }

    public function offsetSet($key, $val)
    {
        if ($val instanceOf NamespaceConfig) {
            return parent::offsetSet($key, $val);
        }

        if (!is_array($val)) {
            throw new \Exception('expected namespace as array');
        }

        return parent::offsetSet($key, new NamespaceConfig($val));
    }
}

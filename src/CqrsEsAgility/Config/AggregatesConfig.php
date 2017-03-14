<?php

namespace CqrsEsAgility\Config;

class AggregatesConfig extends \ArrayObject
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
        if ($val instanceOf AggregateConfig) {
            return parent::offsetSet($key, $val);
        }

        if (!is_array($val)) {
            throw new \InvalidArgumentException(sprintf(
                "expected aggregate as array, got %s",
                is_scalar($val) ? gettype($val) .':'. $val : gettype($val) . ":\n" . print_r($val, true)
            ));
        }

        $val = new AggregateConfig($val);

        return parent::offsetSet($key, $val);
    }
}

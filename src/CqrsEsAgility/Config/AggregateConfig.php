<?php

namespace CqrsEsAgility\Config;

class AggregateConfig extends \ArrayObject
{
    public function __construct(array $config)
    {
        //construct with default keys
        parent::__construct([
            'aggregateName' => null,
        ]);

        $config = array_merge(
            [
                //required to be replaced with string during merge
                'aggregateName' => null,
            ],
            $config
        );

        foreach ($config as $key => $val) {
            $this->offsetSet($key, $val);
        }
    }

    public function offsetSet($key, $val)
    {
        switch ($key) {
            case 'aggregateName':
                if (!is_string($val)) {
                    throw new \Exception('expected aggregateName as string');
                }
                break;
            default :
                throw new \Exception(sprintf('cannot set %s', $key));
        }
        return parent::offsetSet($key, $val);
    }
}

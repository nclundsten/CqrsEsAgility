<?php

namespace CqrsEsAgility\Config;

class ActionConfig extends \ArrayObject
{
    public function __construct(array $config)
    {
        //construct with default keys
        parent::__construct([
            'actionName' => null,
            'method' => 'GET',
            'commandName' => null,
        ]);

        $config = array_merge(
            [
                //required to be replaced with string during merge
                'actionName' => null,
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
            case 'actionName':
                if (!is_string($val)) {
                    throw new \Exception('expected actionName as string');
                }
                break;
            case 'method' :
                if (
                    $val !== 'POST'
                    && $val !== 'GET'
                ) {
                    throw new \Exception('action method must be POST or GET');
                }
                break;
            case 'commandName' :
                if (!is_string($val)) {
                    throw new \Exception('expected commandName as string');
                }
                break;
            default :
                throw new \Exception(sprintf('cannot set %s', $key));
        }
        return parent::offsetSet($key, $val);
    }
}

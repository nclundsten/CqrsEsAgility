<?php

namespace CqrsEsAgility\Config;

class CommandConfig extends \ArrayObject
{
    public function __construct(array $config)
    {
        parent::__construct([
            'commandName' => null,
            'aggregateName' => null,
            'event' => null,
            'commandProps' => [],
        ]);

        $config = array_merge(
            [
                'commandName' => null
            ],
            $config
        );

        //run the config through offsetSet checking
        foreach ($config as $key => $val) {
            $this->offsetSet($key, $val);
        }
    }

    public function offsetSet($key, $val)
    {
        switch ($key) {
            case 'commandName':
                if (!is_string($val)) {
                    throw new \Exception('expected commandName as string');
                }
                break;
            case 'aggregateName':
                if (!is_string($val)) {
                    throw new \Exception('expected aggregateName as string');
                }
                break;
            case 'commandProps':
                if (!is_array($val)) {
                    throw new \Exception('expected commandProps as array');
                }
                break;
            case 'event':
                if (!is_string($this['aggregateName'])) {
                    throw new \Exception('event configured but no aggregateName was provided');
                }
                if ($val instanceOf EventConfig) {
                    break;
                }
                if (!is_array($val)) {
                    throw new \Exception('expected event as array');
                }
                $val = new EventConfig($val, $this);
                break;
            default :
                throw new \Exception(sprintf('cannot set %s', $key));
        }
        return parent::offsetSet($key, $val);
    }
}

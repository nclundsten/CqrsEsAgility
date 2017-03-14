<?php

namespace CqrsEsAgility\Config;

class ListenersConfig extends \ArrayObject
{
    /* @var EventConfig $event */
    public $event;

    public function __construct(array $config, EventConfig $event)
    {
        $this->event = $event;

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
        if ($val instanceOf ListenerConfig) {
            return parent::offsetSet($key, $val);
        }

        if (!is_array($val)) {
            throw new \Exception('expected listener as array');
        }

        return parent::offsetSet($key, new ListenerConfig($val, $this));
    }
}

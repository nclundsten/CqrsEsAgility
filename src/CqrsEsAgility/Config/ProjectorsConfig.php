<?php

namespace CqrsEsAgility\Config;

class ProjectorsConfig extends \ArrayObject
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
        if ($val instanceOf ProjectorConfig) {
            return parent::offsetSet($key, $val);
        }

        if (!is_array($val)) {
            throw new \Exception('expected projector as array');
        }

        return parent::offsetSet($key, new ProjectorConfig($val, $this));
    }
}

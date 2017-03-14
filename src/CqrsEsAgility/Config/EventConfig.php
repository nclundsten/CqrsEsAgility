<?php

namespace CqrsEsAgility\Config;

class EventConfig extends \ArrayObject
{
    /* @var CommandConfig $command */
    public $command;

    public function __construct(array $config, CommandConfig $command)
    {
        $this->command = $command;

        //construct with default keys
        parent::__construct([
            'eventName' => null,
            'eventProps' => [],
            'listeners' => [],
            'projectors' => [],
            'command' => null,
        ]);

        $config = array_merge(
            [
                //required to be replaced with string during merge
                'eventName' => null,
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
            case 'eventName':
                if (!is_string($val)) {
                    throw new \Exception('expected eventName as string');
                }
                break;
            case 'eventProps':
                if (!is_array($val)) {
                    throw new \Exception('expected eventProps as array');
                }
                break;
            case 'projectors':
                if (!is_array($val)) {
                    throw new \Exception('expected projectors as array');
                }
                break;
            case 'listeners':
                if ($val instanceOf ListenersConfig) {
                    break;
                }
                if (!is_array($val)) {
                    throw new \Exception('expected listeners as array');
                }
                $val = new ListenersConfig($val, $this);
                break;
            default :
                throw new \Exception(sprintf('cannot set %s', $key));
        }
        return parent::offsetSet($key, $val);
    }
}

<?php

namespace CqrsEsAgility\Config;

class ListenerConfig extends \ArrayObject
{
    /* @var ListenersConfig $listenrs */
    public $listeners;

    public function __construct(array $config, ListenersConfig $listeners)
    {
        $this->listeners = $listeners;

        //construct with default keys
        parent::__construct([
            'listenerName' => null,
            'commands' => [],
        ]);

        $config = array_merge(
            [
                //required to be replaced with string during merge
                'listenerName' => null,
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
            case 'listenerName':
                if (!is_string($val)) {
                    throw new \Exception('expected listenerName as string');
                }
                break;
            case 'commands':
                if ($val instanceOf CommandsConfig) {
                    break;
                }
                if (!is_array($val)) {
                    throw new \Exception('expected commands as array');
                }
                $val = new CommandsConfig($val);
                break;
            default :
                throw new \Exception(sprintf('cannot set %s', $key));
        }
        return parent::offsetSet($key, $val);
    }
}

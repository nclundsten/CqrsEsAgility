<?php

namespace CqrsEsAgility\Config;

class NamespaceConfig extends \ArrayObject
{
    public function __construct(array $config)
    {
        //construct with default keys
        parent::__construct([
            'namespaceName' => null,
            'actions' => [],
            'aggregates' => [],
        ]);

        $config = array_merge(
            [
                //required to be replaced with string during merge
                'namespaceName' => null,
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
            case 'namespaceName':
                if (!is_string($val)) {
                    throw new \Exception('expected namespaceName as string');
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
            case 'actions':
                if ($val instanceOf ActionsConfig) {
                    break;
                }
                if (!is_array($val)) {
                    throw new \Exception('expected actions as array');
                }
                $val = new ActionsConfig($val);
                break;
            case 'aggregates':
                if ($val instanceOf AggregatesConfig) {
                    break;
                }
                if (!is_array($val)) {
                    throw new \Exception('expected aggregates as array');
                }
                $val = new AggregatesConfig($val);
                break;
            default :
                throw new \Exception(sprintf('cannot set %s', $key));
        }
        return parent::offsetSet($key, $val);
    }
}

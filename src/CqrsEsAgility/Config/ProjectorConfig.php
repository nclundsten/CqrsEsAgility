<?php

namespace CqrsEsAgility\Config;

class ProjectorConfig extends \ArrayObject
{
    /* @var ProjectorsConfig $projectors */
    public $projectors;

    public function __construct(array $config, ProjectorsConfig $projectors)
    {
        $this->projectors = $projectors;

        //construct with default keys
        parent::__construct([
            'projectorName' => null,
        ]);

        $config = array_merge(
            [
                //required to be replaced with string during merge
                'projectorName' => null,
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
            case 'projectorName':
                if (!is_string($val)) {
                    throw new \Exception('expected projectorName as string');
                }
                break;
            default :
                throw new \Exception(sprintf('cannot set %s', $key));
        }
        return parent::offsetSet($key, $val);
    }
}

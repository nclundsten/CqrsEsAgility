<?php

namespace CqrsEsAgility\Generator;

use CqrsEsAgility\Config\ActionConfig;

class Action extends GeneratorAbstract
{
    public function addAction(ActionConfig $actionConfig)
    {
        $actionName = $actionConfig['actionName'];
        /* @var ClassGenerator $class */
        $class = $this->createClass($this->getFqcn($actionName, 'action'));
    }
}

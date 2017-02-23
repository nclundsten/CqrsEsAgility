<?php

namespace CqrsEsAgility\Generator;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ParameterGenerator;

class CommandHandler extends AbstractFile
{
    public function addCommandHandler($commandName, $eventName=null)
    {
        /* @var ClassGenerator $class */
        $class = $this->getClass($this->getFqcn($commandName, 'command-handler'));
        $class->setFinal(true);
        $this->addCommandHandlerFactory($commandName, $eventName);
    }

    protected function addCommandHandlerFactory($commandName, $eventName=null)
    {
        /* @var ClassGenerator $class */
        $class = $this->getClass($this->getFqcn($commandName, 'command-handler-factory'));
        $class->setFinal(true);

    }
}
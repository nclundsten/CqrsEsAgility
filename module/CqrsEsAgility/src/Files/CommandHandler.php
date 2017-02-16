<?php

namespace CqrsEsAgility\Files;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ParameterGenerator;

class CommandHandler extends AbstractFile
{
    protected $namespaces = [
        'command-handler' => 'Infrastructure\\CommandHandler',
    ];
    protected $classNameAppend = [
        'command-handler' => 'Handler',
    ];

    public function addCommandHandler($commandName)
    {
        /* @var ClassGenerator $class */
        $class = $this->getFile($this->formatClassName($commandName, 'command-handler'));

        $class->setNamespaceName($this->getNamespace('command-handler'));
        $class->setFinal(true);

    }
}
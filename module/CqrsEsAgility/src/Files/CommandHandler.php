<?php

namespace CqrsEsAgility\Files;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Prooph\Common\Messaging\Command as ProophCommand;

class CommandHandler extends AbstractFile
{
    protected $namespaces = [
        'command-handler' => 'Infrastructure\\CommandHandler',
    ];

    public function addCommandHandler()
    {

    }
}
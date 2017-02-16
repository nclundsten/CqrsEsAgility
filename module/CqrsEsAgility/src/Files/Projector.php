<?php

namespace CqrsEsAgility\Files;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Prooph\EventSourcing\AggregateRoot as ProophAggregate;

class Projector extends AbstractFile
{
    protected $namespaces = [
        'projector' => 'Infrastructure\\Projector',
    ];
    protected $classNameAppend = [
        'projector' => 'Projector',
    ];

    public function addProjector()
    {

    }

    public function addAggregateCommand($aggregateName, $commandName)
    {
        /* @var ClassGenerator $class */
        $class = $this->getFile($this->formatClassName($aggregateName, 'aggregate'));
    }
}
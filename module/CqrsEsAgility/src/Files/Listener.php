<?php

namespace CqrsEsAgility\Files;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Prooph\EventSourcing\AggregateRoot as ProophAggregate;

class Listener extends AbstractFile
{
    protected $namespaces = [
        'listener' => 'Infrastructure\\EventListener',
        'listener-factory' => 'Listener\\Factory'
    ];
    protected $classNameAppend = [
        'listener' => 'Listener',
    ];

    public function addListener($listenerName)
    {
        /* @var ClassGenerator $class */
        $class = $this->getFile($this->formatClassName($listenerName, 'listener'));
    }
}
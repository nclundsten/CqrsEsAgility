<?php

namespace CqrsEsAgility\Files;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Prooph\EventSourcing\AggregateChanged as ProophEvent;

class Event extends AbstractFile
{
    protected $namespaces = [
        'event' => 'Domain\\DomainEvent',
    ];

    public function addEvent($eventName, array $eventProps)
    {
        /* @var ClassGenerator $class */
        $class = $this->getFile($this->formatClassName($eventName, 'event'));
        $class->setNamespaceName($this->getNamespace('event'));
        $class->addUse(ProophEvent::class);
        $class->setExtendedClass(ProophEvent::class);
        $class->setFinal(true);

        foreach ($eventProps as $propName) {
            $methodGen = new MethodGenerator(
                $propName,
                array(),
                MethodGenerator::FLAG_PUBLIC,
                "return \$this->payload['" . $propName . "'];"
            );
            $class->addMethodFromGenerator($methodGen);
        }
    }
}
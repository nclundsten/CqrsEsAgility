<?php

namespace CqrsEsAgility\Generator;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Prooph\EventSourcing\AggregateRoot as ProophAggregate;

class Aggregate extends AbstractFile
{
    public function addAggregateCommand($aggregateName, $commandName, $commandProps)
    {
        /* @var ClassGenerator $class */
        $class = $this->getFile($this->getFqcn($aggregateName, 'aggregate'));
        $class->addUse(ProophAggregate::class);
        $class->setExtendedClass(ProophAggregate::class);
        $class->setFinal(1);

        $class->addMethodFromGenerator(MethodGenerator::fromArray([
            'name' => lcfirst($commandName),
            'parameters' => $commandProps,
        ]));
    }

    public function addAggregateEvent($aggregateName, $eventName)
    {
        $class = $this->getFile($this->getFqcn($aggregateName, 'aggregate'));
        $class->addUse($this->getFqcn($eventName, 'event'));
        $class->addMethodFromGenerator(MethodGenerator::fromArray([
            'name' => 'on' . $eventName,
            'parameters' => [
                ParameterGenerator::fromArray([
                    'name' => lcfirst($eventName),
                    'type' => $this->getFqcn($eventName, 'event'),
                ]),
            ],
        ]));
    }

    public function addAggregateRepo($aggregateName)
    {
        //@todo interface in domain
        //@todo repo in infra
    }
}
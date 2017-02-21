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
    public function addAggregateCommand($aggregateName, $commandName)
    {
        /* @var ClassGenerator $class */
        $class = $this->getFile($this->getFqcn($aggregateName, 'aggregate'));
        $class->addUse(ProophAggregate::class);
        $class->setExtendedClass(ProophAggregate::class);
        $class->setFinal(1);
    }

    public function addAggregateEvent($aggregateName, $eventName)
    {

    }

    public function addAggregateRepo($aggregateName)
    {
        //@todo interface in domain
        //@todo repo in infra
    }
}
<?php

namespace CqrsEsAgility;

use CqrsEsAgility\FileGen;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ParameterGenerator;

use Prooph\Common\Messaging\Command as ProophCommand;
use Prooph\EventSourcing\AggregateRoot as ProophAggregate;
use Prooph\EventSourcing\AggregateChanged as ProophEvent; //Event

class CqrsEsFiles
{
    protected $classes = [];

    public $baseNamespace;
    public $namespaces = [
        'command' => 'Domain\\Command',
        'event' => 'Domain\\DomainEvent',
        'aggregate' => 'Domain\\Aggregate',
        'commandHandler' => 'Infrastructure\\CommandHandler',
        'listener' => 'Infrastructure\\EventListener',
        'projector' => 'Infrastructure\\Projector',
    ];
    public $classNameAppend = [
        'aggregate' => 'Aggregate',
        'listener' => 'Listener',
        'commandHandler' => 'Handler',
        'projector' => 'Projector',
    ];

    public function __construct($namespace)
    {
        $this->baseNamespace = $namespace;
    }

    public function getClass($name)
    {
        $class = new ClassGenerator();
        $class->setName($name);
        //$class->setNamespaceName

        if (!isset($this->classes[$name])) {
            $this->classes[$name] = $class;
        }

        return $this->classes[$name];
    }

    public function addEvent($eventName)
    {
        $class = $this->fileGen->getClass(
            $this->getNamespace('Event'),
            $this->formatClassName($eventName, 'Event')
        );
        $class->pre = 'final ';
        //@TODO use and extend prooph AggregateChanged
        //$class->import(AggregateChanged::class);

        $method = $class->addMethod('fromData');
        $method->addParam('array $data');
        $method->returnTypehint = 'self';
        $method->content = "return self;";

        /* @TODO generate some test files!! */
    }

    //add the Interface and the Repository
    public function addAggregateRepo($aggregateName)
    {
        //TODO add interface file


    }

    public function addAggregateCommand($aggregateName, $commandName)
    {
        $class = $this->fileGen->getClass(
            $this->getNamespace('Aggregate'),
            $this->formatClassName($aggregateName, 'Aggregate')
        );
        $class->pre = 'final ';
        // @TODO use and extend prooph AggregateRoot
        //$class->import(AggregateRoot::class);

        $class->addMethod($commandName);

        /* @TODO generate some test files!! */
    }

    public function addAggregateEvent($aggregateName, $eventName)
    {
        $class = $this->fileGen->getClass($this->namespaces['Aggregate'], $aggregateName . 'Aggregate');
        $class->pre = 'final ';
        $class->import($this->getFqcn($eventName, 'Event'));

        $eventMethod = $class->addMethod('when' . $this->formatClassName($eventName));
        $eventMethod->addParam($this->formatClassName($eventName, 'Event') . ' $event');

        /* @TODO generate some test files!! */
    }

    public function addCommandHandler($commandName, $eventName = null)
    {
        $class = $this->fileGen->getClass(
            $this->getNamespace('CommandHandler'),
            $this->formatClassName($commandName, 'CommandHandler')
        );
        $class->pre = 'final ';
        $class->import($this->getFqcn($commandName, 'Command'));

        $method = $class->addMethod('handle' . $this->formatClassName($commandName, 'Command'));
        $method->addParam($commandName . ' $command');
        if ($eventName) {
            $class->import($this->getFqcn($eventName, 'Event'));
            $method->returnTypehint = $this->formatClassName($eventName, 'Event');
            $method->content = "return new " . $this->formatClassName($eventName, 'Event') . "::fromData(/* @TODO */);";
        } else {
            $method->content = "/* handle your command */";
        }

        /* @TODO generate some test files!! */
    }

    public function addProjector($projectorName, $eventName)
    {
        $class = $this->fileGen->getClass(
            $this->getNamespace('Projector'),
            $this->formatClassName($projectorName, 'Projector')
        );
        $class->pre = 'final ';
        $class->import($this->getFqcn($eventName, 'Event'));

        $eventMethod = $class->addMethod('on' . $eventName);
        $eventMethod->addParam($eventName . ' $event');

        /* @TODO generate some test files!! */
    }

    public function addEventListener($eventName, $listenerName)
    {
        $class = $this->fileGen->getClass(
            $this->getNamespace('Listener'),
            $this->formatClassName($eventName, 'Listener')
        );
        $class->pre = 'final ';
        $class->import($this->getFqcn($eventName, 'Event'));

        $eventMethod = $class->addMethod('on' . $eventName);
        $eventMethod->addParam($this->formatClassName($eventName, 'Event') . ' $event');

        /* @TODO generate some test files!! */

        // @TODO
        // generate listeners class,
        // generate listeners factory,
        // add listener to listeners factory + class
    }

    public function getNamespace($type)
    {
        return $this->namespaces[$type];
    }

    public function getFqcn($name, $type)
    {
        return $this->baseNamespace
            . '\\' . $this->getNamespace($type)
            . '\\' . $this->formatClassName($name, $type);
    }

    public function formatClassName($name, $type)
    {
        if (isset($this->classNameAppend[$type])) {
            $name .= $this->classNameAppend[$type];
        }
        return $name;
    }

    public function generateEventListenerCollectionFactory()
    {

    }

    public function generateEventListenerCollection()
    {

    }
}
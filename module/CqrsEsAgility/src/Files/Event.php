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
        $class = $this->createFile($this->formatClassName($eventName, 'event'));
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

        //withDetails(...)
        $propList = (array) $eventProps;
        $body = "return self::occur(\n";
        $body .= "    " . "$" . array_shift($propList) . ",\n";
        $body .= "    " . "[\n";
        foreach ($propList as $propName) {
            $body .= "        " . "'" . $propName . "' => $" . $propName . ",\n";
        }
        $body .= "    " . "]\n";
        $body .= ");";
        $class->addMethodFromGenerator(MethodGenerator::fromArray([
            'name' => 'withDetails',
            'parameters' => $eventProps,
            'visibility' => MethodGenerator::FLAG_PUBLIC,
            'body' => $body,
            'docBlock' => DocBlockGenerator::fromArray(array(
                'shortDescription' => '',
                'longDescription'  => null,
                'tags'             => array(
                    //new Tag\ReturnTag(array(
                    //    'datatype'  => 'string|null',
                    //)),
                ),
            )),
            'returnType' => $this->getFqcn($eventName, 'event'),
        ]));
    }
}
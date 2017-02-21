<?php

namespace CqrsEsAgility\Generator;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ParameterGenerator;

class Projector extends AbstractFile
{
    public function addProjector($projectorName, $eventName)
    {
        /* @var ClassGenerator $class */
        $class = $this->getFile($this->getFqcn($projectorName, 'projector'));
        $class->addUse($this->getFqcn($eventName, 'event'));
        $class->setFinal(1);

        $class->addMethodFromGenerator(MethodGenerator::fromArray([
            'name' => 'on' . $this->formatClassName($eventName, 'event'),
            'parameters' => [
                ParameterGenerator::fromArray([
                    'name' => 'event',
                    'type' => $this->getFqcn($eventName, 'event'),
                ]),
            ],
            'visibility' => MethodGenerator::FLAG_PUBLIC,
            'body' => '/* project to table/etc */',
            'docBlock' => DocBlockGenerator::fromArray(array(
                'shortDescription' => '',
                'longDescription'  => null,
                'tags'             => array(
                    //new Tag\ReturnTag(array(
                    //    'datatype'  => 'string|null',
                    //)),
                ),
            )),
        ]));
    }

}
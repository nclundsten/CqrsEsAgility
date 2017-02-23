<?php

namespace CqrsEsAgility\Generator;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Interop\Container\ContainerInterface;

class Projector extends AbstractFile
{
    public function addProjector($projectorName, $eventName)
    {
        /* @var ClassGenerator $class */
        $class = $this->getClass($this->getFqcn($projectorName, 'projector'));
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
                'longDescription' => null,
                'tags' => array(
                    //new Tag\ReturnTag(array(
                    //    'datatype'  => 'string|null',
                    //)),
                ),
            )),
            // @TODO message to developer to add their code
        ]));
        $this->addProjectorToProjectorsFactory($eventName, $projectorName);
    }

    private function addProjectorToProjectorsFactory($eventName, $projectorName)
    {
        $class = $this->getClass($this->getFqcn($eventName, 'projectors-factory'));
        $class->addUse($this->getFqcn($projectorName, 'projector'));
        $class->addUse(ContainerInterface::class);

        if (false == $class->hasMethod('__invoke')) {
            //TODO add the listener
            $body = "return [\n";
            $body .= "];";
            $class->addMethodFromGenerator(MethodGenerator::fromArray([
                'name' => '__invoke',
                'parameters' => [
                    ParameterGenerator::fromArray([
                        'name' => 'container',
                        'type' => ContainerInterface::class,
                    ]),
                ],
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
            ]));
        }
        $this->addProjectorToInvokeBody($class, $projectorName);
    }

    /**
     * sorry this is hacky
     * @TODO: find a better way to modify the method body
     * @TODO: perhaps extend zend\code\generator\classgenerator
     *   return [
     *      SomeProjector(),
     *
     *      //THIS WILL BE ADDED
     *      YourNewProjector(),
     *
     *   ];
     *
     */
    private function addProjectorToInvokeBody(ClassGenerator $projectorsClass, $projectorName)
    {
        $method = $projectorsClass->getMethod('__invoke');
        $bodySplit = explode("\n", $method->getBody());
        //pop the end off (closes array)
        $end = array_pop($bodySplit);
        $bodySplit[] = "    " . "new " . $this->formatClassName($projectorName, 'projector') ."(),";
        $bodySplit[] = $end;
        //set to the new body (with the added projector)
        $method->setBody(implode("\n", $bodySplit));
    }
}
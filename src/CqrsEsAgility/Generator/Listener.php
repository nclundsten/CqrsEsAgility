<?php

namespace CqrsEsAgility\Generator;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Prooph\ServiceBus\CommandBus;

class Listener extends AbstractFile
{
    public function addEventListener($eventName, $listenerName, $listenerConfig)
    {
        /* @var ClassGenerator $class */
        $class = $this->getFile($this->getFqcn($listenerName, 'listener'));
        $class->addUse($this->getFqcn($eventName, 'event'));
        $class->setFinal(1);


        if (isset($listenerConfig['commands'])) {
            $class->addPropertyFromGenerator(PropertyGenerator::fromArray([
                'name' => 'commandBus',
            ]));
            $class->addUse(CommandBus::class);
            //__construct()
            $class->addMethodFromGenerator(MethodGenerator::fromArray([
                'name' => '__construct',
                'parameters' => [
                    ParameterGenerator::fromArray([
                        'name' => 'commandBus',
                        'type' => CommandBus::class,
                    ]),
                ],
                'body' => "\$this->commandBus = \$commandBus;",
            ]));

            $body = "// @NOTE: fetch the params needed for the command below\n";
            foreach ($listenerConfig['commands'] as $commandName => $command) {
                $class->addUse($this->getFqcn($commandName, 'command'));
                $body .= "\$this->commandBus->dispatch(" . $this->formatClassName($commandName, 'command') . "::fromDetails(\n";
                $body .= "    $" . implode(", \n    $", $command['commandProps']) . "\n";
                $body .= "));\n";
            }
        } else {
            // we dont know what they wanted to do
            $body = '/* no commands were configured - add your custom code to make it happen */';
            // @TODO : message after generation that this file needs to be completed
        }
        $class->addMethodFromGenerator(MethodGenerator::fromArray([
            'name' => 'on' . $this->formatClassName($eventName, 'event'),
            'parameters' => [
                ParameterGenerator::fromArray([
                    'name' => 'event',
                    'type' => $this->getFqcn($eventName, 'event'),
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

    protected function addListenerToListenersFactory($eventName, $listenerName)
    {
        //create listeners factory
        //create "listeners" class
        //@TODO create the "Listeners" class for the event name, create the factory, add the specific listener
    }
}
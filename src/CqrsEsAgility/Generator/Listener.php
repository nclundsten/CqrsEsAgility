<?php

namespace CqrsEsAgility\Generator;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Prooph\ServiceBus\CommandBus;
use Interop\Container\ContainerInterface;
use CqrsEsAgility\Files\Exception\ClassNotFound;
use CqrsEsAgility\Config\ListenerConfig;
use CqrsEsAgility\Config\CommandsConfig;

class Listener extends GeneratorAbstract
{
    public function addEventListener(ListenerConfig $listenerConfig)
    {
        $listenerName = $listenerConfig['listenerName'];
        $eventName = $listenerConfig->listeners->event['eventName'];

        /* @var ClassGenerator $class */
        $class = $this->createClass($this->getFqcn($listenerName, 'listener'));

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

        $this->addListenerToListenersFactory($eventName, $listenerName, $listenerConfig['commands']);
    }

    private function addListenerToListenersFactory(string $eventName, string $listenerName, CommandsConfig $commands)
    {
        try {
            /* @var ClassGenerator $class */
            $class = $this->getClass($this->getFqcn($eventName, 'listeners-factory'));
        } catch (ClassNotFound $exception) {
            /* @var ClassGenerator $class */
            $class = $this->createClass($this->getFqcn($eventName, 'listeners-factory'));
            $class->addUse($this->getFqcn($listenerName, 'listener'));
            $class->addUse(ContainerInterface::class);
        }

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

        if (count($commands)) {
            $class->addUse(CommandBus::class);
        }

        $this->addListenerToInvokeBody($class, $listenerName, count($commands));
    }


    /**
     * sorry this is hacky
     * @TODO: find a better way to modify the method body
     * @TODO: perhaps extend zend\code\generator\classgenerator
     *   return [
     *      SomeListener(
     *          $container->get(CommandBus::class)
     *      ),
     *
     *      //THIS WILL BE ADDED
     *      YourNewListener(
     *          $container->get(CommandBus::class)
     *      ),
     *
     *   ];
     *
     */
    private function addListenerToInvokeBody(ClassGenerator $listenersClass, string $listenerName, bool $commandBus = false)
    {
        $method = $listenersClass->getMethod('__invoke');
        $bodySplit = explode("\n", $method->getBody());
        //pop the end off (closes array)
        $end = array_pop($bodySplit);
        if ($commandBus) {
            $bodySplit[] = "    " . "new " . $this->formatClassName($listenerName, 'listener') ."(";
            $bodySplit[] = "    " . "    " . "\$container->get(CommandBus::class)";
            $bodySplit[] = "    " . "),";
        } else {
            $bodySplit[] = "    " . "new " . $this->formatClassName($listenerName, 'listener') ."(),";
        }
        $bodySplit[] = $end;
        //set to the new body (with the added listener)
        $method->setBody(implode("\n", $bodySplit));
    }
}

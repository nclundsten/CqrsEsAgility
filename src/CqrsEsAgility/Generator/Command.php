<?php

namespace CqrsEsAgility\Generator;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Prooph\Common\Messaging\Command as ProophCommand;

class Command extends AbstractFile
{
    public function addCommand($commandName, $commandProps)
    {
        /* @var ClassGenerator $class */
        $class = $this->getClass($this->getFqcn($commandName, 'command'));
        $class->addUse(ProophCommand::class);
        $class->setExtendedClass(ProophCommand::class);
        $class->setFinal(true);

        //class properties
        foreach ($commandProps as $propName) {
            $class->addPropertyFromGenerator(
                PropertyGenerator::fromArray([
                    'name' => $propName,
                    'visibility' => 'private',
                    'docBlock' => DocBlockGenerator::fromArray(array(
                        'shortDescription' => '',
                        'longDescription'  => null,
                        'tags'             => array(
                            //new Tag\ReturnTag(array(
                            //    'datatype'  => 'string|null',
                            //)),
                        ),
                    ))
                ])
            );
        }

        //__construct()
        $class->addMethodFromGenerator(MethodGenerator::fromArray([
            'name' => '__construct',
            'body' => "\$this->init();",
        ]));

        //fromDetails(...)
        $body = "\$instance = new self();\n";
        $body .= "\n";
        foreach ($commandProps as $propName) {
            $body .= "\$instance->" . $propName . " = $" . $propName . ";\n";
        }
        $body .= "\n";
        $body .= "return \$instance;";
        $class->addMethodFromGenerator(MethodGenerator::fromArray([
            'name' => 'fromDetails',
            'parameters' => $commandProps,
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
            'returnType' => $this->getFqcn($commandName, 'command'),
        ]));

        //getters
        foreach ($commandProps as $propName) {
            $methodGen = new MethodGenerator(
                $propName,
                array(),
                MethodGenerator::FLAG_PUBLIC,
                "return \$this->" . $propName . ";"
            );
            $class->addMethodFromGenerator($methodGen);
        }

        //payload()
        $body = "return [\n";
        foreach ($commandProps as $propName) {
            $body .= "    '" . $propName .  "' => \$this->" . $propName . "(),\n";
        }
        $body .= "];\n";
        $class->addMethodFromGenerator(MethodGenerator::fromArray([
            'name' => 'payload',
            'parameters' => array(),
            'visibility' =>MethodGenerator::FLAG_PUBLIC,
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
            'returnType' => 'array',
        ]));

        //setPayload(array $payload)
        $body = '';
        foreach ($commandProps as $propName) {
            $body .= "\$this->" . $propName . " = \$payload['" . $propName . "'];\n";
        }
        $class->addMethodFromGenerator(MethodGenerator::fromArray([
            'name' => 'setPayload',
            'body' => $body,
            'visibility' => MethodGenerator::VISIBILITY_PROTECTED,
            'parameters' => array(
                ParameterGenerator::fromArray([
                    'name' => 'payload',
                    'type' => 'array',
                ])
            ),
            'docblock' => DocBlockGenerator::fromArray(array(
                'shortDescription' => '',
                'longDescription'  => null,
                'tags'             => array(
                    //new Tag\ReturnTag(array(
                    //    'datatype'  => 'string|null',
                    //)),
                ),
            ))
        ]));

        /* @TODO generate some test files!! */
    }
}
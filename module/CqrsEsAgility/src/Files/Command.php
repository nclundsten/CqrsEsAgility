<?php

namespace CqrsEsAgility\Files;

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
        $class = $this->getFile($this->formatClassName($commandName, 'command'));

        $class->setNamespaceName($this->getNamespace('command'));
        $class->addUse(ProophCommand::class);
        $class->setExtendedClass(ProophCommand::class);
        $class->setFinal(1);

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

        $class->addMethodFromGenerator(MethodGenerator::fromArray([
            'name' => '__construct',
            'body' => "\$this->init();",
        ]));

        $body = "\$instance = new self();\n";
        $body .= "\n";
        foreach ($commandProps as $propName) {
            $body .= "\$instance->" . $propName . " = $" . $propName . ";\n";
        }
        $body .= "\n";
        $body .= "return \$instance;";

        $fromDetails = new MethodGenerator(
            'fromDetails',
            $commandProps,
            MethodGenerator::FLAG_PUBLIC,
            $body,
            DocBlockGenerator::fromArray(array(
                'shortDescription' => '',
                'longDescription'  => null,
                'tags'             => array(
                    //new Tag\ReturnTag(array(
                    //    'datatype'  => 'string|null',
                    //)),
                ),
            ))
        );
        $fromDetails->setReturnType($this->getFqcn($commandName, 'command'));
        $class->addMethodFromGenerator($fromDetails);

        foreach ($commandProps as $propName) {
            $methodGen = new MethodGenerator(
                $propName,
                array(),
                MethodGenerator::FLAG_PUBLIC,
                "return \$this->" . $propName . ";"
            );
            $class->addMethodFromGenerator($methodGen);
        }

        $body = "return [\n";
        foreach ($commandProps as $propName) {
            $body .= "    '" . $propName .  "' => \$this->" . $propName . "(),\n";
        }
        $body .= "];\n";
        $payload = new MethodGenerator(
            'payload',
            array(),
            MethodGenerator::FLAG_PUBLIC,
            $body,
            DocBlockGenerator::fromArray(array(
                'shortDescription' => '',
                'longDescription'  => null,
                'tags'             => array(
                    //new Tag\ReturnTag(array(
                    //    'datatype'  => 'string|null',
                    //)),
                ),
            ))
        );
        $payload->setReturnType('array');
        $class->addMethodFromGenerator($payload);

        $body = '';
        foreach ($commandProps as $propName) {
            $body .= "\$this->" . $propName . " = \$payload['" . $propName . "'];\n";
        }
        $setPayload = MethodGenerator::fromArray([
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
        ]);
        $class->addMethodFromGenerator($setPayload);

        /* @TODO generate some test files!! */
    }
}
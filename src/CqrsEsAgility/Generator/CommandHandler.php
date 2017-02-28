<?php

namespace CqrsEsAgility\Generator;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Interop\Container\ContainerInterface;

class CommandHandler extends AbstractFile
{
    public function addCommandHandler(
        string $commandName,
        array $commandProps,
        string $aggregateName = null,
        string $eventName = null
    ) {
        /* @var ClassGenerator $class */
        $class = $this->createClass($this->getFqcn($commandName, 'command-handler'));

        $class->setFinal(true);

        if ($aggregateName) {
            $class->addUse($this->getFqcn($aggregateName, 'repository'));
            $repoName = lcfirst($this->formatClassName($aggregateName, 'repository'));
            $class->addPropertyFromGenerator(PropertyGenerator::fromArray([
                'name' => $repoName,
                'docBlock' => DocBlockGenerator::fromArray([]),
            ]));
            $class->addMethodFromGenerator(MethodGenerator::fromArray([
                'name' => '__construct',
                'parameters' => [
                    ParameterGenerator::fromArray([
                        'name' => $repoName,
                        'type' => $this->getFqcn($aggregateName, 'repository'),
                    ]),
                ],
                'body' => "\$this->$repoName = \$$repoName;",
            ]));
        } else {
            $class->addMethodFromGenerator(MethodGenerator::fromArray([
                'name' => '__construct',
                'body' => "/**/",
            ]));
        }

        if ($aggregateName) {
            $aggregate = lcfirst($aggregateName);
            $body = "\$$aggregate = \$this->repository->get(\$command->$aggregate" . "Id());\n";
            if (count($commandProps)) {
                $body .= "\$$aggregate->" . lcfirst($commandName) ."(\n";
                $params = [];
                foreach ($commandProps as $propName) {
                    $params[] = '    ' . '$command->' . $propName . "()";
                }
                $body .= implode(",\n", $params) . "\n";
                $body .= ");\n";
            } else {
                $body .= "\$$aggregate->" . lcfirst($commandName) ."();\n";
            }
            $body .= "\$this->repository->add(\$$aggregate);\n";
        } else {
            $body = "/* an aggregate was not configured - add your custom handling logic*/";
        }

        $class->addUse($this->getFqcn($commandName, 'command'));
        $class->addMethodFromGenerator(MethodGenerator::fromArray([
            'name' => '__invoke',
            'parameters' => [
                ParameterGenerator::fromArray([
                    'name' => 'command',
                    'type' => $this->getFqcn($commandName, 'command'),
                ]),
            ],
            'body' => $body,
        ]));

        $this->addCommandHandlerFactory($commandName, $aggregateName, $eventName);
    }

    protected function addCommandHandlerFactory(
        string $commandName,
        string $aggregateName = null,
        string $eventName = null
    ) {
        /* @var ClassGenerator $class */
        $class = $this->createClass($this->getFqcn($commandName, 'command-handler-factory'));

        $class->setFinal(true);
        $class->addUse(ContainerInterface::class);
        $class->addUse($this->getFqcn($commandName, 'command'));
        $class->addUse($this->getFqcn($aggregateName, 'repository-interface'));

        $body = "return new " . $this->formatClassName($commandName, 'command-handler') . "(\n";
        if ($aggregateName) {
            $body .= '    ' . "\$container->get(" . $this->formatClassName($aggregateName, 'repository-interface') . "::class)\n";
            $body .= '    ' . "/* inject any other dependencies */\n";
        } else {
            $body .= '    ' . "/* inject the command handler dependencies */\n";
        }
        $body .= ');';
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
            'returnType' => $this->getFqcn($commandName, 'command-handler'),
        ]));
    }
}

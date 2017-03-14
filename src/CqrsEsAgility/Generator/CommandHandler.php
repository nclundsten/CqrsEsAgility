<?php

namespace CqrsEsAgility\Generator;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Interop\Container\ContainerInterface;
use CqrsEsAgility\Config\CommandConfig;

class CommandHandler extends GeneratorAbstract
{
    public function addCommandHandler(CommandConfig $commandConfig)
    {
        $commandName = $commandConfig['commandName'];
        $aggregateName = $commandConfig['aggregateName'];
        $commandProps = $commandConfig['commandProps'];

        /* @var ClassGenerator $class */
        $class = $this->createClass($this->getFqcn($commandName, 'command-handler'));

        $class->setFinal(true);

        //construct
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

        //invoke
        if ($aggregateName) {
            $lcAggregateName = lcfirst($aggregateName);
            $lcCommandName = lcfirst($commandName);
            $body = "\$$lcAggregateName = \$this->repository->get(\$command->$lcAggregateName" . "Id());\n";
            if (count($commandProps)) {
                $body .= "\$$lcAggregateName->$commandName(\n";
                $params = [];
                foreach ($commandProps as $propName) {
                    $params[] = '    ' . '$command->' . $propName . "()";
                }
                $body .= implode(",\n", $params) . "\n";
                $body .= ");\n";
            } else {
                $body .= "\$$lcAggregateName->$commandName();\n";
            }
            $body .= "\$this->repository->add(\$$lcAggregateName);\n";
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

        $this->addCommandHandlerFactory($commandName, $aggregateName);
    }

    protected function addCommandHandlerFactory(string $commandName, string $aggregateName = null)
    {
        /* @var ClassGenerator $class */
        $class = $this->createClass($this->getFqcn($commandName, 'command-handler-factory'));

        $class->setFinal(true);
        $class->addUse(ContainerInterface::class);
        $class->addUse($this->getFqcn($commandName, 'command'));
        if ($aggregateName) {
            $class->addUse($this->getFqcn($aggregateName, 'repository-interface'));
        }

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

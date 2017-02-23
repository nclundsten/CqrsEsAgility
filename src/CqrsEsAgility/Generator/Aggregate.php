<?php

namespace CqrsEsAgility\Generator;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\InterfaceGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\DocBlock\Tag;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Prooph\EventSourcing\AggregateRoot as ProophAggregate;
use Prooph\EventStore\Aggregate\AggregateRepository;

class Aggregate extends AbstractFile
{
    public function addAggregateCommand(string $aggregateName, string $commandName, array $commandProps)
    {
        /* @var ClassGenerator $class */
        $class = $this->getClass($this->getFqcn($aggregateName, 'aggregate'));
        $class->addUse(ProophAggregate::class);
        $class->setExtendedClass(ProophAggregate::class);
        $class->setFinal(1);

        $class->addMethodFromGenerator(MethodGenerator::fromArray([
            'name' => lcfirst($commandName),
            'parameters' => $commandProps,
        ]));

        $this->addRepository($aggregateName);
    }

    public function addAggregateEvent(string $aggregateName, string $eventName)
    {
        /* @var ClassGenerator $class */
        $class = $this->getClass($this->getFqcn($aggregateName, 'aggregate'));

        $class->addUse($this->getFqcn($eventName, 'event'));
        $class->addMethodFromGenerator(MethodGenerator::fromArray([
            'name' => 'on' . $eventName,
            'parameters' => [
                ParameterGenerator::fromArray([
                    'name' => lcfirst($eventName),
                    'type' => $this->getFqcn($eventName, 'event'),
                ]),
            ],
        ]));
    }

    public function addRepository(string $aggregateName)
    {
        /* @var ClassGenerator $class */
        $class = $this->getClass($this->getFqcn($aggregateName, 'repository'));

        $class->addUse(AggregateRepository::class);
        $class->addUse($this->getFqcn($aggregateName, 'aggregate'));

        if (false == $class->hasProperty('aggregateRepository')) {
            $class->addPropertyFromGenerator(PropertyGenerator::fromArray([
                'name' => 'aggregateRepository',
                'docBlock' => DocBlockGenerator::fromArray([]),
            ]));
        }

        if (false == $class->hasMethod('__construct')) {
            $class->addMethodFromGenerator(MethodGenerator::fromArray([
                'name' => '__construct',
                'body' => "\$this->aggregateRepository = \$aggregateRepository",
                'parameters' => [
                    ParameterGenerator::fromArray([
                        'name' => 'aggregateRepository',
                        'type' => AggregateRepository::class,
                    ]),
                ],
                'docBlock' => DocBlockGenerator::fromArray([]),
            ]));

        }
        if (false == $class->hasMethod('add')) {
            $class->addMethodFromGenerator(MethodGenerator::fromArray([
                'name' => 'add',
                'body' => "\$this->aggregateRepository->addAggregateRoot(\$" . lcfirst($aggregateName) . ");",
                'parameters' => [
                    ParameterGenerator::fromArray([
                        'name' => lcfirst($this->formatClassName($aggregateName, 'aggregate')),
                        'type' => $this->getFqcn($aggregateName, 'aggregate')
                    ]),
                ],
                'docBlock' => DocBlockGenerator::fromArray([]),
            ]));
        }

        if (false == $class->hasMethod('get')) {
            $body = '/** @var '. $this->getFqcn($aggregateName, 'aggregate') .'|null $aggregateRoot */' . "\n";
            $body .= '$aggregateRoot = $this->aggregateRepository->getAggregateRoot($' . lcfirst($aggregateName) . 'Id)' . "\n";
            $body .= "\n";
            $body .= 'if (!$aggregateRoot instanceof ' . $this->getFqcn($aggregateName, 'aggregate') . ') {' . "\n";
            $body .= '    ' . '/*exception*/' . "\n";
            $body .= '}' . "\n";
            $body .= "\n";
            $body .= 'return $aggregateRoot;' . "\n";
            $class->addMethodFromGenerator(MethodGenerator::fromArray([
                'name' => 'get',
                'body' => $body,
                'parameters' => [
                    ParameterGenerator::fromArray([
                        'name' => lcfirst($aggregateName) . 'Id',
                    ]),
                ],
                'returnType' => $this->getFqcn($aggregateName, 'aggregate'),
                'docBlock' => DocBlockGenerator::fromArray([]),
            ]));
        }

        $this->addRepositoryInterface($aggregateName);
        $this->addRepositoryFactory($aggregateName);
    }

    protected function addRepositoryInterface(string $aggregateName)
    {
        /* @var InterfaceGenerator $interface */
        $interface = $this->getInterface($this->getFqcn($aggregateName, 'repository-interface'));

        if (false == $interface->hasMethod('add')) {
            $interface->addMethodFromGenerator(MethodGenerator::fromArray([
                'name' => 'add',
                'parameters' => [
                    ParameterGenerator::fromArray([
                        'name' => lcfirst($this->formatClassName($aggregateName, 'aggregate')),
                        'type' => $this->getFqcn($aggregateName, 'aggregate')
                    ]),
                ],
            ]));
        }
        if (false == $interface->hasMethod('get')) {
            $interface->addMethodFromGenerator(MethodGenerator::fromArray([
                'name' => 'get',
                'parameters' => [
                    ParameterGenerator::fromArray([
                        'name' => lcfirst($aggregateName) . 'Id',
                    ]),
                ],
                'returnType' => $this->getFqcn($aggregateName, 'aggregate'),
            ]));
        }
    }

    protected function addRepositoryFactory(string $aggregateName)
    {
        $class = $this->getClass($this->getFqcn($aggregateName, 'repository-factory'));
    }

    protected function addAggregateNotFoundException(string $aggregateName)
    {
        //TODO
    }
}
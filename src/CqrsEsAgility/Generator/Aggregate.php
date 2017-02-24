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
    public function addAggregate(string $aggregateName, array $aggregateConfig)
    {
        /* @var ClassGenerator $class */
        $class = $this->createClass($this->getFqcn($aggregateName, 'aggregate'));

        $class->addUse(ProophAggregate::class);
        $class->setExtendedClass(ProophAggregate::class);
        $class->setFinal(1);

        $this->addRepository($aggregateName);
    }

    public function addAggregateCommand(string $aggregateName, string $commandName, array $commandProps)
    {
        /* @var ClassGenerator $class */
        $class = $this->getClass($this->getFqcn($aggregateName, 'aggregate'));

        $class->addMethodFromGenerator(MethodGenerator::fromArray([
            'name' => lcfirst($commandName),
            'parameters' => $commandProps,
        ]));
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

    private function addRepository(string $aggregateName)
    {
        /* @var ClassGenerator $class */
        $class = $this->createClass($this->getFqcn($aggregateName, 'repository'));

        $class->addUse(AggregateRepository::class);
        $class->addUse($this->getFqcn($aggregateName, 'aggregate'));

        $class->addPropertyFromGenerator(PropertyGenerator::fromArray([
            'name' => 'aggregateRepository',
            'docBlock' => DocBlockGenerator::fromArray([]),
        ]));

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

        $body = '/** @var '. $this->getFqcn($aggregateName, 'aggregate') .'|null $aggregateRoot */' . "\n";
        $body .= '$aggregateRoot = $this->aggregateRepository->getAggregateRoot($' . lcfirst($aggregateName) . 'Id)' . "\n";
        $body .= "\n";
        $body .= 'if (!$aggregateRoot instanceof ' . $this->getFqcn($aggregateName, 'aggregate') . ') {' . "\n";
        $body .= '    ' . '/*TODO aggregate not found exception*/' . "\n";
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

        $this->addRepositoryInterface($aggregateName);
        $this->addRepositoryFactory($aggregateName);
    }

    private function addRepositoryInterface(string $aggregateName)
    {
        /* @var InterfaceGenerator $interface */
        $interface = $this->createInterface($this->getFqcn($aggregateName, 'repository-interface'));

        $interface->addMethodFromGenerator(MethodGenerator::fromArray([
            'name' => 'add',
            'parameters' => [
                ParameterGenerator::fromArray([
                    'name' => lcfirst($this->formatClassName($aggregateName, 'aggregate')),
                    'type' => $this->getFqcn($aggregateName, 'aggregate')
                ]),
            ],
        ]));

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

    protected function addRepositoryFactory(string $aggregateName)
    {
        $class = $this->createClass($this->getFqcn($aggregateName, 'repository-factory'));
    }

    protected function addAggregateNotFoundException(string $aggregateName)
    {
        //TODO
    }
}
<?php

namespace CqrsEsAgility;

use CqrsEsAgility\Config\CommandConfig;
use CqrsEsAgility\Config\AggregateConfig;
use CqrsEsAgility\Config\EventConfig;
use CqrsEsAgility\Config\NamespacesConfig;
use CqrsEsAgility\Config\NamespaceConfig;
use CqrsEsAgility\Config\ActionConfig;
use CqrsEsAgility\Config\ListenerConfig;

class Generate extends AbstractGenerate
{
    public function generateNamespaces(NamespacesConfig $namespaces)
    {
        foreach ($namespaces as $namespace) {
            $this->generateNamespace($namespace);
        }
    }

    protected function generateNamespace(NamespaceConfig $config)
    {
        $this->setNamespace($config['namespaceName']);

        foreach ($config['aggregates'] as $aggregateConfig) {
            $this->addAggregate($aggregateConfig);
        }

        foreach ($config['commands'] as $commandConfig) {
            $this->addCommand($commandConfig);
        }

        foreach ($config['actions'] as $actionConfig) {
            $this->addAction($actionConfig);
        }

        $this->generateFiles();
    }

    protected function addAction(ActionConfig $actionConfig)
    {
        $this->action->addAction($actionConfig);
    }

    protected function addAggregate(AggregateConfig $aggregateConfig)
    {
        $this->aggregate->addAggregate($aggregateConfig);
    }

    protected function addCommand(CommandConfig $commandConfig)
    {
        $this->command->addCommand($commandConfig);

        //a command *MAY* have an associated aggregate
        if ($commandConfig['aggregateName']) {
            $this->aggregate->addAggregateCommand($commandConfig);
        }

        $this->commandHandler->addCommandHandler($commandConfig);

        //a command *MAY* have an associated event
        if ($commandConfig['event']) {
            $this->addEvent($commandConfig['event']);
        }
    }

    protected function addEvent(EventConfig $eventConfig)
    {
        $aggregateName = $eventConfig->command['aggregateName'];
        $eventName = $eventConfig['eventName'];
        $this->event->addEvent($eventName, $eventConfig['eventProps']);
        $this->aggregate->addAggregateEvent($aggregateName, $eventName);

        //an event *MAY* have listeners
        if (count($eventConfig['listeners'])) {
            foreach ($eventConfig['listeners'] as $listenerConfig) {
                $this->addEventListener($listenerConfig);
            }
        }

        //an event *MAY* have projectors
        if (count($eventConfig['projectors'])) {
            foreach ($eventConfig['projectors'] as $projectorName) {
                $this->addEventProjector($projectorName, $eventName);
            }
        }
    }

    protected function addEventListener(ListenerConfig $listenerConfig)
    {
        $this->listener->addEventListener($listenerConfig);

        //a listener *MAY* use the command bus to dispatch commands
        foreach ($listenerConfig['commands'] as $commandConfig) {
            $this->addCommand($commandConfig);
        }
    }

    protected function addEventProjector(string $projectorName, string $eventName)
    {
        $this->projector->addProjector($projectorName, $eventName);
    }
}



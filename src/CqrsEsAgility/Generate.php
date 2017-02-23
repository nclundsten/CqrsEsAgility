<?php

namespace CqrsEsAgility;

class Generate extends AbstractGenerate
{
    public function __invoke(array $config)
    {
        if (
            isset($config['actions'])
            && is_array($config['actions'])
            && count($config['actions'])
        ) {
            foreach ($config['actions'] as $actionName => $actionConfig) {
                $this->addAction($actionName, $actionConfig);
            }
        }

        if (
            isset($config['aggregates'])
            && is_array($config['aggregates'])
            && count($config['aggregates'])
        ) {
            foreach ($config['aggregates'] as $aggregateName => $aggregateConfig) {
                $this->addAggregate($aggregateName, $aggregateConfig);
            }
        }

        if (
            isset($config['commands'])
            && is_array($config['commands'])
            && count($config['commands'])
        ) {
            foreach ($config['commands'] as $commandName => $commandConfig) {
                $this->addCommand($commandName, $commandConfig);
            }
        }


        parent::generate();
    }

    protected function addAction(string $actionName, array $actionConfig)
    {
        //TODO
    }

    protected function addAggregate(string $aggregateName, array $aggregateConfig)
    {
        $this->aggregate->addAggregate($aggregateName, $aggregateConfig);
    }

    protected function addCommand(string $commandName, array $commandConfig)
    {
        $this->command->addCommand($commandName, $commandConfig['commandProps']);

        //a command *MAY* have an associated aggregate (*SHOULD* ?? optional for now)
        if (isset($commandConfig['aggregateName'])) {
            $this->aggregate->addAggregateCommand($commandConfig['aggregateName'], $commandName, $commandConfig['commandProps']);
        }

        //a command *MAY* have an associated event
        if (
            isset($commandConfig['event'])
            && is_array($commandConfig['event'])
        ) {
            $eventName = $commandConfig['event']['eventName'];
            $this->addEvent($eventName, $commandConfig['aggregateName'], $commandConfig['event']);
            $this->commandHandler->addCommandHandler($commandName, $eventName);
        } else {
            $this->commandHandler->addCommandHandler($commandName);
        }
    }

    protected function addEvent(string $eventName, string $aggregateName, array $eventConfig)
    {
        $this->event->addEvent($eventName, $eventConfig['eventProps']);
        $this->aggregate->addAggregateEvent($aggregateName, $eventName);

        //an event *MAY* have listeners
        if (
            isset($eventConfig['listeners'])
            && is_array($eventConfig['listeners'])
            && count($eventConfig['listeners'])
        ) {
            foreach ($eventConfig['listeners'] as $listenerName => $listenerConfig) {
                $this->addEventListener($eventName, $listenerName, $listenerConfig);
            }
        }

        //an event *MAY* have projectors
        if (
            isset($eventConfig['projectors'])
            && is_array($eventConfig['projectors'])
            && count($eventConfig['projectors'])
        ) {
            foreach ($eventConfig['projectors'] as $projectorName) {
                $this->addEventProjector($projectorName, $eventName);
            }
        }
    }

    protected function addEventListener(string $eventName, string $listenerName, $listenerConfig)
    {
        $this->listener->addEventListener($eventName, $listenerName, $listenerConfig);

        //a listener *MAY* use the command bus to dispatch commands
        if (
            isset($listenerConfig['commands'])
            && is_array($listenerConfig['commands'])
            && count($listenerConfig['commands'])
        ) {
            foreach ($listenerConfig['commands'] as $commandName => $commandConfig) {
                $this->addCommand($commandName, $commandConfig);
            }
        }
    }

    protected function addEventProjector(string $projectorName, string $eventName)
    {
        $this->projector->addProjector($projectorName, $eventName);
    }
}


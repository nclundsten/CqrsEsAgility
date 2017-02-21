<?php

namespace CqrsEsAgility;


class Generate extends AbstractGenerate
{
    public function __invoke(array $config)
    {
        //TODO actions

        if (isset($config['commands']) && count($config['commands'])) {
            foreach ($config['commands'] as $commandName => $commandConfig) {
                $this->addCommand($commandName, $commandConfig);
            }
        } else {
            throw new \Exception('no files to generate');
        }

        parent::generate();
    }

    protected function addCommand($commandName, array $commandConfig)
    {
        $this->command->addCommand($commandName, $commandConfig['commandProps']);
        if (isset($commandConfig['aggregateName'])) {
            $this->aggregate->addAggregateCommand($commandConfig['aggregateName'], $commandName, $commandConfig['commandProps']);
        }

        if (isset($commandConfig['event']) && is_array($commandConfig['event'])) {
            $eventName = $commandConfig['event']['eventName'];
            $this->addEvent($eventName, $commandConfig['aggregateName'], $commandConfig['event']);
            $this->commandHandler->addCommandHandler($commandName, $eventName);
        } else {
            $this->commandHandler->addCommandHandler($commandName);
        }
    }

    protected function addEvent($eventName, $aggregateName, array $eventConfig)
    {
        $this->event->addEvent($eventName, $eventConfig['eventProps']);
        $this->aggregate->addAggregateEvent($aggregateName, $eventName);

        if (isset($eventConfig['listeners']) && count($eventConfig['listeners'])) {
            foreach ($eventConfig['listeners'] as $listenerName => $listenerConfig) {
                $this->addEventListener($eventName, $listenerName, $listenerConfig);
            }
        }
        if (isset($eventConfig['projectors']) && count($eventConfig['projectors'])) {
            foreach ($eventConfig['projectors'] as $projectorName) {
                $this->addEventProjector($projectorName, $eventName);
            }
        }
    }

    protected function addEventListener($eventName, $listenerName, $listenerConfig)
    {
        $this->listener->addEventListener($eventName, $listenerName, $listenerConfig);

        if (isset($listenerConfig['commands']) && count($listenerConfig['commands'])) {
            foreach ($listenerConfig['commands'] as $commandName => $commandConfig) {
                $this->addCommand($commandName, $commandConfig);
            }
        }
    }

    protected function addEventProjector($projectorName, $eventName)
    {
        $this->projector->addProjector($projectorName, $eventName);
    }
}


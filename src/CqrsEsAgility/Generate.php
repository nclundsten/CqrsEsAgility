<?php

namespace CqrsEsAgility;

use CqrsEsAgility\Generator\Command;
use CqrsEsAgility\Generator\CommandHandler;
use CqrsEsAgility\Generator\Aggregate;
use CqrsEsAgility\Generator\Event;
use CqrsEsAgility\Generator\Listener;
use CqrsEsAgility\Generator\Projector;
use CqrsEsAgility\Generator\AbstractFile;
use Zend\Code\Generator\ClassGenerator;

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

        $files = $this->files->getFiles();
        foreach ($files as $file) {
            /* @var ClassGenerator $file */
            $dir = 'generated' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $file->getNamespaceName());
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $content = "<?php\n";
            $content .= "declare(strict_types=1);\n";
            $content .= "\n";
            $content .= $file->generate();

            file_put_contents($dir . DIRECTORY_SEPARATOR . $file->getName() . '.php', $content);
        }
        echo count($files) . ' Files Generated';
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


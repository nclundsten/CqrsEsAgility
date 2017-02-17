<?php

namespace CqrsEsAgility;

use CqrsEsAgility\Files\FilesCollection;
use CqrsEsAgility\Files\Command;
use CqrsEsAgility\Files\CommandHandler;
use CqrsEsAgility\Files\Aggregate;
use CqrsEsAgility\Files\Event;
use CqrsEsAgility\Files\Listener;
use CqrsEsAgility\Files\Projector;
use CqrsEsAgility\Files\AbstractFile;
use Zend\Code\Generator\ClassGenerator;

class Generate
{
    protected $generators = [
        'command' => Command::class,
        'command-handler' => CommandHandler::class,
        'aggregate' => Aggregate::class,
        'event' => Event::class,
        'listener' => Listener::class,
        'projector' => Projector::class,
    ];

    private $files;

    private $config;
    private $namespace;

    private function getGenerator($name) : AbstractFile
    {
        if (!isset($this->generators[$name])) {
            throw new \RuntimeException('generator not configured : ' . $name);
        }
        if (is_string($this->generators[$name])) {
            $generatorClass = $this->generators[$name];
            $this->generators[$name] = new $generatorClass($this->namespace, $this->files);
        }
        return $this->generators[$name];
    }

    public function __construct($namespace, array $config)
    {
        $this->files = new FilesCollection;
        $this->namespace = $namespace;
        $this->config = $config;
    }

    public function generate()
    {
        $config = $this->config;

        //TODO actions

        if (isset($config['commands']) && count($config['commands'])) {
            foreach ($config['commands'] as $commandName => $commandConfig) {
                $this->generateCommand($commandName, $commandConfig);
            }
        } else {
            echo "no commands for namespace : " . $this->namespace . "\n";
        }

        $files = $this->files->getFiles();
        foreach ($files as $file) {
            /* @var ClassGenerator $file */
            $dir = 'generated' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $file->getNamespaceName());
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents($dir . DIRECTORY_SEPARATOR . $file->getName() . '.php', $file->generate());
        }
        echo count($files) . ' Files Generated';
    }

    protected function generateCommand($commandName, array $commandConfig)
    {
        $this->getGenerator('command')->addCommand($commandName, $commandConfig['commandProps']);
        $this->getGenerator('aggregate')->addAggregateCommand($commandConfig['aggregateName'], $commandName);

        /* @var \CqrsEsAgility\Files\CommandHandler $commandHandlerGen */
        $commandHandlerGen = $this->getGenerator('command-handler');
        if (isset($commandConfig['event']) && is_array($commandConfig['event'])) {
            $eventName = $commandConfig['event']['eventName'];
            $this->generateEvent($eventName, $commandConfig['aggregateName'], $commandConfig['event']);
            $commandHandlerGen->addCommandHandler($commandName, $eventName);
        } else {
            $commandHandlerGen->addCommandHandler($commandName);
        }
    }

    protected function generateEvent($eventName, $aggregateName, array $eventConfig)
    {
        $this->getGenerator('event')->addEvent($eventName, $eventConfig['eventProps']);
        $this->getGenerator('aggregate')->addAggregateEvent($aggregateName, $eventName);

        if (isset($eventConfig['listeners']) && count($eventConfig['listeners'])) {
            foreach ($eventConfig['listeners'] as $listenerName => $listenerConfig) {
                $this->generateEventListener($eventName, $listenerName, $listenerConfig);
            }
        }
        if (isset($eventConfig['projectors']) && count($eventConfig['projectors'])) {
            foreach ($eventConfig['projectors'] as $projectorName) {
                $this->generateEventProjector($projectorName, $eventName);
            }
        }
    }

    protected function generateEventListener($eventName, $listenerName, $listenerConfig)
    {
        $this->getGenerator('listener')->addListener($eventName, $listenerName);

        if (isset($listenerConfig['commands']) && count($listenerConfig['commands'])) {
            foreach ($listenerConfig['commands'] as $commandName => $commandConfig) {
                $this->generateCommand($commandName, $commandConfig);
            }
        }
    }

    function generateEventProjector($projectorName, $eventName)
    {
        $this->getGenerator('projector')->addProjector($projectorName, $eventName);
    }
}


<?php

namespace CqrsEsAgility;

use CqrsEsAgility\Files\FilesCollection;
use CqrsEsAgility\Files\Command;
use CqrsEsAgility\Files\Aggregate;
use CqrsEsAgility\Files\AbstractFile;

class Generate
{
    protected $generators = [
        'command' => Command::class,
        'aggregate' => Aggregate::class,
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
            echo $file->generate();
        }
        echo count($files) . ' Files Generated';
    }

    protected function generateCommand($commandName, array $commandConfig)
    {
        $this->getGenerator('command')->addCommand($commandName, $commandConfig['commandProps']);

        $this->getGenerator('aggregate')->addAggregateCommand($this->namespace, $commandName);
        return;

        $this->files->addAggregateCommand($this->namespace, $commandName);

        if (isset($commandConfig['commandProps']) && count($commandConfig['commandProps'])) {
            /* @TODO */
        }

        if (isset($commandConfig['event']) && is_array($commandConfig['event'])) {
            $eventName = $commandConfig['event']['eventName'];
            $this->generateEvent($eventName, $commandConfig['event']);
            $this->files->addCommandHandler($commandName, $eventName);
        } else {
            $this->files->addCommandHandler($commandName);
        }
    }

    protected function generateEvent($eventName, array $eventConfig)
    {
        $this->files->addEvent($eventName);
        $this->files->addAggregateEvent($this->namespace, $eventName);

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
        $this->files->addEventListener($eventName, $listenerName);

        if (isset($listenerConfig['commands']) && count($listenerConfig['commands'])) {
            foreach ($listenerConfig['commands'] as $commandName => $commandConfig) {
                $this->generateCommand($commandName, $commandConfig);
            }
        }
    }

    function generateEventProjector($projectorName, $eventName)
    {
        $this->files->addProjector($projectorName, $eventName);
    }
}


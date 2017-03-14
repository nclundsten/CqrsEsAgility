<?php

namespace CqrsEsAgility;

use CqrsEsAgility\Generator\Command;
use CqrsEsAgility\Generator\CommandHandler;
use CqrsEsAgility\Generator\Aggregate;
use CqrsEsAgility\Generator\Event;
use CqrsEsAgility\Generator\Listener;
use CqrsEsAgility\Generator\Projector;
use CqrsEsAgility\Files\FilesCollection;
use CqrsEsAgility\Generator\Action;

use Zend\Code\Generator\ClassGenerator;

abstract class AbstractGenerate
{
    /* @var FilesCollection */
    public $files;

    /* @var Command */
    protected $command;

    /* @var CommandHandler */
    protected $commandHandler;

    /* @var Aggregate */
    protected $aggregate;

    /* @var Event */
    protected $event;

    /* @var Listener */
    protected $listener;

    /* @var Projector */
    protected $projector;

    /* @var Action */
    protected $action;

    public function __construct(
        FilesCollection $files,
        Command $command,
        CommandHandler $commandHandler,
        Aggregate $aggregate,
        Event $event,
        Listener $listener,
        Projector $projector,
        Action $action
    ) {
        $this->files = $files;
        $this->command = $command;
        $this->commandHandler = $commandHandler;
        $this->aggregate = $aggregate;
        $this->event = $event;
        $this->listener = $listener;
        $this->projector = $projector;
        $this->action = $action;
    }

    protected function setNamespace(string $namespaceName)
    {
        $this->command->setBaseNamespace($namespaceName);
        $this->commandHandler->setBaseNamespace($namespaceName);
        $this->aggregate->setBaseNamespace($namespaceName);
        $this->event->setBaseNamespace($namespaceName);
        $this->listener->setBaseNamespace($namespaceName);
        $this->projector->setBaseNamespace($namespaceName);
        $this->action->setBaseNamespace($namespaceName);
    }

    protected function generateFiles()
    {
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
}

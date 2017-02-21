<?php

namespace CqrsEsAgility;

use CqrsEsAgility\Generator\Command;
use CqrsEsAgility\Generator\CommandHandler;
use CqrsEsAgility\Generator\Aggregate;
use CqrsEsAgility\Generator\Event;
use CqrsEsAgility\Generator\Listener;
use CqrsEsAgility\Generator\Projector;
use CqrsEsAgility\Files\FilesCollection;

abstract class AbstractGenerate
{
    /* @var FilesCollection */
    protected $files;

    /* @var Command */
    protected $command;

    /* @var CommandHandler*/
    protected $commandHandler;

    /* @var Aggregate*/
    protected $aggregate;

    /* @var Event */
    protected $event;

    /* @var Listener*/
    protected $listener;

    /* @var Projector*/
    protected $projector;

    public function __construct(
        FilesCollection $files,
        Command $command,
        CommandHandler $commandHandler,
        Aggregate $aggregate,
        Event $event,
        Listener $listener,
        Projector $projector
    ) {
        $this->files = $files;
        $this->command = $command;
        $this->commandHandler = $commandHandler;
        $this->aggregate = $aggregate;
        $this->event = $event;
        $this->listener = $listener;
        $this->projector = $projector;
    }
}

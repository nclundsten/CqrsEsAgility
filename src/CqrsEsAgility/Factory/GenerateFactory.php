<?php

namespace CqrsEsAgility\Factory;
use CqrsEsAgility\Generate;

class GenerateFactory
{
    public function create($generatorsConfig)
    {
        $fileCollection = new \CqrsEsAgility\Files\FilesCollection();

        return new \CqrsEsAgility\Generate(
            $fileCollection,
            new \CqrsEsAgility\Generator\Command($generatorsConfig, $fileCollection),
            new \CqrsEsAgility\Generator\CommandHandler($generatorsConfig, $fileCollection),
            new \CqrsEsAgility\Generator\Aggregate($generatorsConfig, $fileCollection),
            new \CqrsEsAgility\Generator\Event($generatorsConfig, $fileCollection),
            new \CqrsEsAgility\Generator\Listener($generatorsConfig, $fileCollection),
            new \CqrsEsAgility\Generator\Projector($generatorsConfig, $fileCollection),
            new \CqrsEsAgility\Generator\Action($generatorsConfig, $fileCollection)
        );
    }
}

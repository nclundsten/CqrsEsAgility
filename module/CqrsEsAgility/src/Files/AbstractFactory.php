<?php

namespace CqrsEsAgility\Files;

use CqrsEsAgility\Files\FilesCollection;

class AbstractFactory extends AbstractFile
{
    public function __construct($namespace, FilesCollection $files)
    {
        parent::__construct($namespace, $files);

        //add all the stuff needed to represent an empty factory
    }
}
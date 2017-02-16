<?php

namespace CqrsEsAgility\Files;

use Zend\Code\Generator\ClassGenerator;

class FilesCollection
{
    protected $classes = [];

    public function getFile($name) : ClassGenerator
    {
        $class = new ClassGenerator();
        $class->setName($name);

        if (!isset($this->classes[$name])) {
            $this->classes[$name] = $class;
        }

        return $this->classes[$name];
    }

    public function getFiles()
    {
        return $this->classes;
    }
}
<?php

namespace CqrsEsAgility\Files;

use Zend\Code\Generator\ClassGenerator;

class FilesCollection
{
    protected $classes = [];

    public function getFile($name) : ClassGenerator
    {
        if (!isset($this->classes[$name])) {
            $class = new ClassGenerator();
            $class->setName($name);
            $this->classes[$name] = $class;
        }

        return $this->classes[$name];
    }

    public function getFiles()
    {
        return $this->classes;
    }
}
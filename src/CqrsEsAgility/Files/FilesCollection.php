<?php

namespace CqrsEsAgility\Files;

use Zend\Code\Generator\ClassGenerator;

class FilesCollection
{
    protected $classes = [];

    public function getFile($name) : ClassGenerator
    {
        if (!isset($this->classes[$name])) {
            $this->createFile($name);
        }

        return $this->classes[$name];
    }

    public function createFile($name) : ClassGenerator
    {
        if (isset($this->classes[$name])) {
            throw new \Exception('file already created: ' . $name);
        }

        $class = new ClassGenerator();
        $class->setName($name);
        $this->classes[$name] = $class;

        return $this->classes[$name];
    }

    public function getFiles()
    {
        return $this->classes;
    }
}
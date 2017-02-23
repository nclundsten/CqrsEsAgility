<?php

namespace CqrsEsAgility\Files;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\InterfaceGenerator;

class FilesCollection
{
    /* @var array */
    protected $files = [];

    public function getClass(string $name) : ClassGenerator
    {
        if (!isset($this->files[$name])) {
            $this->files[$name] = new ClassGenerator($name);
        }

        return $this->files[$name];
    }

    public function getInterface(string $name) : InterfaceGenerator
    {
        if (!isset($this->files[$name])) {
            $this->files[$name] = new InterfaceGenerator($name);
        }

        return $this->files[$name];
    }

    public function getFiles() : array
    {
        return $this->files;
    }
}
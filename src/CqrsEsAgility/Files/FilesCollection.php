<?php

namespace CqrsEsAgility\Files;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\InterfaceGenerator;
use CqrsEsAgility\Files\Exception\ClassAlreadyCreated;
use CqrsEsAgility\Files\Exception\ClassNotFound;
use CqrsEsAgility\Files\Exception\InterfaceAlreadyCreated;
use CqrsEsAgility\Files\Exception\InterfaceNotFound;

class FilesCollection
{
    /* @var array */
    protected $files = [];

    public function createClass(string $name) : ClassGenerator
    {
        if (isset($this->files[$name])) {
            throw new ClassAlreadyCreated(sprintf(
                'class already exists: %s',
                $name
            ));
        }

        $this->files[$name] = new ClassGenerator($name);
        return $this->files[$name];
    }

    public function getClass(string $name) : ClassGenerator
    {
        if (!isset($this->files[$name])) {
            throw new ClassNotFound(sprintf(
                'class does not exist: %s',
                $name
            ));
        }

        return $this->files[$name];
    }

    public function createInterface(string $name) : InterfaceGenerator
    {
        if (isset($this->files[$name])) {
            throw new InterfaceAlreadyCreated(sprintf(
                'interface already exists: %s',
                $name
            ));
        }

        $this->files[$name] = new InterfaceGenerator($name);
        return $this->files[$name];
    }

    public function getInterface(string $name) : InterfaceGenerator
    {
        if (!isset($this->files[$name])) {
            throw new InterfaceNotFound(sprintf(
                'interface does not exist: %s',
                $name
            ));
        }

        $this->files[$name] = new InterfaceGenerator($name);
        return $this->files[$name];
    }

    public function getFiles() : array
    {
        return $this->files;
    }
}
<?php

namespace CqrsEsAgility\Files;
use CqrsEsAgility\Files\FilesCollection;

abstract class AbstractFile
{
    private $baseNamespace;

    /* @var FilesCollection $files */
    protected $files;

    protected $namespaces = [
    ];

    protected $classNameAppend = [
    ];

    public function __construct($namespace, FilesCollection $files)
    {
        $this->baseNamespace = $namespace;
        $this->files = $files;
    }

    protected function getFile($name)
    {
        return $this->files->getFile($name);
    }

    public function getNamespace($type)
    {
        return $this->baseNamespace . '\\' . $this->namespaces[$type];
    }

    public function getFqcn($name, $type)
    {
        return $this->getNamespace($type)
            . '\\' . $this->formatClassName($name, $type);
    }

    public function formatClassName($name, $type)
    {
        if (isset($this->classNameAppend[$type])) {
            $name .= $this->classNameAppend[$type];
        }
        return $name;
    }
}
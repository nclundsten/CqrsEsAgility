<?php

namespace CqrsEsAgility\Generator;

use CqrsEsAgility\Files\FilesCollection;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\InterfaceGenerator;

abstract class AbstractFile
{
    /* @var string */
    private $baseNamespace;

    /* @var FilesCollection $files */
    protected $files;

    /* @var array */
    protected $namespaces = [ /* set during construct */ ];

    /* @var array */
    protected $classNameAppend = [ /* set during construct */];

    public function __construct(array $config, string $namespace, FilesCollection $files)
    {
        $this->namespaces = $config['namespaces'];
        $this->classNameAppend = $config['class-name-append'];
        $this->baseNamespace = $namespace;
        $this->files = $files;
    }

    protected function createClass($name) : ClassGenerator
    {
        return $this->files->createClass($name);
    }

    protected function getClass($name) : ClassGenerator
    {
        return $this->files->getClass($name);
    }

    protected function createInterface($name) : InterfaceGenerator
    {
        return $this->files->createInterface($name);
    }

    protected function getInterface($name) : InterfaceGenerator
    {
        return $this->files->getInterface($name);
    }

    public function getNamespace($type) : string
    {
        return $this->baseNamespace . '\\' . $this->namespaces[$type];
    }

    public function getFqcn($name, $type) : string
    {
        return $this->getNamespace($type)
            . '\\' . $this->formatClassName($name, $type);
    }

    public function formatClassName($name, $type) : string
    {
        if (isset($this->classNameAppend[$type])) {
            $name .= $this->classNameAppend[$type];
        }
        return $name;
    }
}
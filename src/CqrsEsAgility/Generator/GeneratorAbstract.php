<?php

namespace CqrsEsAgility\Generator;

use CqrsEsAgility\Files\FilesCollection;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\InterfaceGenerator;

abstract class GeneratorAbstract
{
    /* @var string */
    private $baseNamespace;

    /* @var FilesCollection $files */
    protected $files;

    /* @var array */
    protected $namespaces = [ /* set during construct */ ];

    /* @var array */
    protected $classNameAppend = [ /* set during construct */];

    public function __construct(array $config, FilesCollection $files)
    {
        $this->namespaces = $config['namespaces'];
        $this->classNameAppend = $config['class-name-append'];
        $this->files = $files;
    }

    protected function createClass(string $name) : ClassGenerator
    {
        return $this->files->createClass($name);
    }

    protected function getClass(string $name) : ClassGenerator
    {
        return $this->files->getClass($name);
    }

    protected function createInterface(string $name) : InterfaceGenerator
    {
        return $this->files->createInterface($name);
    }

    protected function getInterface(string $name) : InterfaceGenerator
    {
        return $this->files->getInterface($name);
    }

    public function setBaseNamespace(string $namespaceName)
    {
        $this->baseNamespace = $namespaceName;
    }

    public function getNamespace(string $type) : string
    {
        return $this->baseNamespace . '\\' . $this->namespaces[$type];
    }

    public function getFqcn(string $name, string $type) : string
    {
        return $this->getNamespace($type)
            . '\\' . $this->formatClassName($name, $type);
    }

    public function formatClassName(string $name, string $type) : string
    {
        if (isset($this->classNameAppend[$type])) {
            $name .= $this->classNameAppend[$type];
        }
        return $name;
    }
}

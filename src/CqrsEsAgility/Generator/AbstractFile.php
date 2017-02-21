<?php

namespace CqrsEsAgility\Generator;

use CqrsEsAgility\Files\FilesCollection;

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

    public function __construct(array $config, $namespace, FilesCollection $files)
    {
        $this->namespaces = $config['namespaces'];
        $this->classNameAppend = $config['class-name-append'];
        $this->baseNamespace = $namespace;
        $this->files = $files;
    }

    protected function getFile($name)
    {
        return $this->files->getFile($name);
    }

    protected function createFile($name)
    {
        return $this->files->createFile($name);
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
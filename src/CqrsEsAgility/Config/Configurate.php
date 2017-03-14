<?php

namespace CqrsEsAgility\Config;

class Configurate
{
    public static function configure(array $config)
    {
        return new NamespacesConfig($config);
    }
}

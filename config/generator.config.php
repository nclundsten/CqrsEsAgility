<?php return [
    'namespaces' => [
        'action' => 'Action',
        'aggregate' => 'Domain\\Aggregate',
        'aggregate-repo-interface' => 'Domain\\Repository',
        'aggregate-repo' => 'Infrastructure\\Repository',
        'command' => 'Domain\\Command',
        'command-handler' => 'Infrastructure\\CommandHandler',
        'command-handler-factory' => 'Infrastructure\\CommandHandler\\Factory',
        'event' => 'Domain\\DomainEvent',
        'listener' => 'Infrastructure\\EventListener',
        'listeners-factory' => 'Factory\\Listener',
        'projector' => 'Infrastructure\\Projector',
        'projectors-factory' => 'Factory\\Projector',
        'repository' => 'Infrastructure\\Repository',
        'repository-factory' => 'Infrastructure\\Repository\\Factory',
        'repository-interface' => 'Domain\\Repository',
    ],
    'class-name-append' => [
        //'aggregate' => 'Aggregate',
        'action' => 'Action',
        'command-handler' => 'Handler',
        'command-handler-factory' => 'HandlerFactory',
        'listener' => 'Listener',
        'listeners-factory' => 'ListenersFactory',
        'projector' => 'Projector',
        'projectors-factory' => 'ProjectorsFactory',
        'repository' => 'Repository',
        'repository-factory' => 'RepositoryFactory',
        'repository-interface' => 'RepositoryInterface',
    ],
];

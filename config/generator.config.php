<?php return [
    'namespaces' => [
        'command' => 'Domain\\Command',
        'event' => 'Domain\\DomainEvent',
        'command-handler' => 'Infrastructure\\CommandHandler',
        'aggregate' => 'Domain\\Aggregate',
        'aggregate-repo-interface' => 'Domain\\Repository',
        'aggregate-repo' => 'Infrastructure\\Repository',
        'listener' => 'Infrastructure\\EventListener',
        'listeners-factory' => 'Factory\\Listener',
        'projector' => 'Infrastructure\\Projector',
        'projectors-factory' => 'Factory\\Projector',
    ],
    'class-name-append' => [
        'aggregate' => 'Aggregate',
        'projector' => 'Projector',
        'command-handler' => 'Handler',
        'listener' => 'Listener',
        'listeners-factory' => 'ListenersFactory',
        'projectors-factory' => 'ProjectorsFactory',
    ],
];

<?php return [
    'namespaces' => [
        'command' => 'Domain\\Command',
        'event' => 'Domain\\DomainEvent',
        'command-handler' => 'Infrastructure\\CommandHandler',
        'aggregate' => 'Domain\\Aggregate',
        'aggregate-repo-interface' => 'Domain\\Repository',
        'aggregate-repo' => 'Infrastructure\\Repository',
        'listener' => 'Infrastructure\\EventListener',
        'listener-factory' => 'Listener\\Factory',
        'projector' => 'Infrastructure\\Projector',
    ],
    'class-name-append' => [
        'aggregate' => 'Aggregate',
        'projector' => 'Projector',
        'command-handler' => 'Handler',
        'listener' => 'Listener',
    ],
];

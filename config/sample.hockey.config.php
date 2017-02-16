<?php

return [
    'Hockey' => [
        'actions' => [
            'AddPlayer',
            'AddTeam',
            'AddPlayerToTeam',
            'ScheduleGame',
            'StartGame',
            'AddPointsForTeamInGame',
            'EndGame',

            'RemovePlayerFromTeam',
            'RescheduleGame',
            'CancelScheduledGame',
        ],
        'commands' => [
            'CreatePlayer' => [
                'aggregateName' => 'Player',
                'commandProps' => ['playerName'],
                'event' => [
                    'eventName' => 'PlayerWasCreated',
                    'eventProps' => ['playerName'],
                    'projectors' => [
                        'Player',
                    ],
                    'listeners' => [
                    ],
                ],
            ],
            'CreateTeam' => [
                'commandProps' => ['teamName'],
                'event' => [
                    'eventName' => 'TeamWasCreated',
                    'eventProps' => ['teamName', 'createdDateTime'],
                    'projectors' => [
                        'Team',
                    ],
                    'listeners' => [
                    ],
                ],
            ],
            'AddPlayerToTeam' => [
                'commandProps' => ['playerId', 'teamId'],
                'event' => [
                    'eventName' => 'PlayerWasAddedToTeam',
                    'eventProps' => ['playerId', 'addedDateTime'],
                    'projectors' => [
                        'TeamPlayer',
                    ],
                    'listeners' => [
                    ],
                ],
            ],
            'ScheduleGame' => [
                'commandProps' => ['homeTeamId', 'awayTeamId', 'scheduledDateTime'],
                'event' => [
                    'eventName' => 'GameWasScheduled',
                    'eventProps' => ['homeTeamId', 'awayTeamId', 'scheduledDateTime', 'dateTime'],
                    'projectors' => [
                        'GameSchedule',
                    ],
                    'listeners' => [
                    ],
                ],
            ],
            'StartGame' => [
                'commandProps' => ['scheduledGameId', 'startedDateTime'],
                'event' => [
                    'eventName' => 'GameWasStarted',
                    'eventProps' => [],
                    'projectors' => [
                        'Game',
                    ],
                    'listeners' => [
                    ],
                ],
            ],
            'AddPointsForTeamInGame' => [
                'commandProps' => ['gameId', 'teamId', 'points'],
                'event' => [
                    'eventName' => 'PointsForTeamInGameWereAdded',
                    'eventProps' => ['gameId', 'teamId', 'points', 'dateTime'],
                    'projectors' => [
                        'Game',
                    ],
                    'listeners' => [
                    ],
                ],
            ],
            'EndGame' => [
                'commandProps' => ['gameId'],
                'event' => [
                    'eventName' => 'PointsForTeamInGameWereAdded',
                    'eventProps' => ['gameId', 'dateTime'],
                    'projectors' => [
                        'Game',
                    ],
                    'listeners' => [
                    ],
                ],
            ],

            /* modifications */
            'RemovePlayerFromTeam' => [
                'commandProps' => ['playerId', 'teamId'],
                'event' => [
                    'eventName' => 'PlayerWasRemovedFromTeam',
                    'eventProps' => ['playerId', 'removedDateTime'],
                    'projectors' => [
                        'TeamPlayer',
                    ],
                    'listeners' => [
                    ],
                ],
            ],
            'RescheduleGame' => [
                'commandProps' => ['scheduledGameId', 'homeTeamId', 'awayTeamId', 'newScheduledDateTime'],
                'event' => [
                    'eventName' => 'GameWasRescheduled',
                    'eventProps' => [],
                    'projectors' => [
                        'GameSchedule',
                    ],
                    'listeners' => [
                    ],
                ],
            ],
            'CancelScheduledGame' => [
                'commandProps' => ['scheduledGameId', 'reason'],
                'event' => [
                    'eventName' => 'GameWasScheduled',
                    'eventProps' => ['homeTeamId', 'awayTeamId', 'scheduledDateTime', 'dateTime'],
                    'projectors' => [
                        'GameSchedule',
                    ],
                    'listeners' => [
                    ],
                ],
            ],
        ],
    ],
];

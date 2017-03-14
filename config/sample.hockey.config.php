<?php

return [
    [
        'namespaceName' => 'HockeyTracker',
        'actions' => [
            [
                'actionName' => 'AddPlayer',
                'method' => 'POST',
                'commandName' => 'AddPlayer',
            ],
            //'AddTeam' => [],
            //'AddPlayerToTeam' => [],
            //'ScheduleGame' => [],
            //'StartGame' => [],
            //'AddPointsForTeamInGame' => [],
            //'EndGame' => [],

            //'RemovePlayerFromTeam' => [],
            //'RescheduleGame' => [],
            //'CancelScheduledGame' => [],
        ],
        'aggregates' => [
            [
                'aggregateName' => 'Player',
                /*things specific to a player*/
            ],
            [
                'aggregateName' => 'Team',
                /*things specific to a team*/
            ],
            [
                'aggregateName' => 'TeamPlayer',
                /*things specific to a player on a team*/
            ],
            [
                'aggregateName' => 'ScheduledGame',
                /*things specific to a scheduled game between teams*/
            ],
            [
                'aggregateName' => 'Game',
                /*things specific to a game played between teams*/
            ],
        ],
        'commands' => [
            [
                'commandName' => 'CreatePlayer',
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
            [
                'commandName' => 'CreateTeam',
                'aggregateName' => 'Team',
                'commandProps' => ['teamName'],
                'event' => [
                    'eventName' => 'TeamWasCreated',
                    'eventProps' => ['teamName'],
                    'projectors' => [
                        'Team',
                    ],
                    'listeners' => [
                    ],
                ],
            ],
            [
                'commandName' => 'AddPlayerToTeam',
                'aggregateName' => 'TeamPlayer',
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
            [
                'commandName' => 'ScheduleGame',
                'aggregateName' => 'ScheduledGame',
                'commandProps' => ['homeTeamId', 'awayTeamId', 'scheduledDateTime'],
                'event' => [
                    'eventName' => 'GameWasScheduled',
                    'eventProps' => ['homeTeamId', 'awayTeamId', 'scheduledDateTime'],
                    'projectors' => [
                        'GameSchedule',
                    ],
                    'listeners' => [
                        //team has a game scheduled at "home"
                        [
                            'listenerName' => 'NotifyHomeTeamFollowersOfScheduledGame',
                            'commands' => [
                                [
                                    'commandName' => 'NotifyHomeTeamFollowersOfScheduledGame',
                                    'commandProps' => [
                                        'homeTeamId',
                                        'awayTeamId',
                                        'scheduledDateTime'
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'commandName' => 'StartGame',
                'aggregateName' => 'Game',
                'commandProps' => ['scheduledGameId'],
                'event' => [
                    'eventName' => 'GameWasStarted',
                    'eventProps' => ['scheduledGameId'],
                    'projectors' => [
                        'Game',
                    ],
                    'listeners' => [
                    ],
                ],
            ],
            [
                'commandName' => 'EndGame',
                'aggregateName' => 'Game',
                'commandProps' => [],
                'event' => [
                    'eventName' => 'GameWasEnded',
                    'eventProps' => ['gameId', 'dateTime'],
                    'projectors' => [
                        'Game',
                    ],
                    'listeners' => [
                    ],
                ],
            ],
            [
                'commandName' => 'AddPointsInTeamForGame',
                'aggregateName' => 'Game',
                'commandProps' => ['gameId', 'teamId', 'points'],
                'event' => [
                    'eventName' => 'PointsForTeamInGameWereAdded',
                    'eventProps' => ['gameId', 'teamId', 'points'],
                    'projectors' => [
                        'Game',
                    ],
                    'listeners' => [
                    ],
                ],
            ],

            /* modifications */
            [
                'commandName' => 'RemovePlayerFromTeam',
                'aggregateName' => 'TeamPlayer',
                'commandProps' => ['playerId', 'teamId'],
                'event' => [
                    'eventName' => 'PlayerWasRemovedFromTeam',
                    'eventProps' => ['playerId'],
                    'projectors' => [
                        'TeamPlayer',
                    ],
                    'listeners' => [
                    ],
                ],
            ],
            [
                'commandName' => 'RescheduleGame',
                'aggregateName' => 'ScheduledGame',
                'commandProps' => ['scheduledGameId', 'homeTeamId', 'awayTeamId', 'newScheduledDateTime'],
                'event' => [
                    'eventName' => 'GameWasRescheduled',
                    'eventProps' => ['scheduledGameId', 'homeTeamId', 'awayTeamId', 'newScheduledDateTime'],
                    'projectors' => [
                        'GameSchedule',
                    ],
                    'listeners' => [
                    ],
                ],
            ],
            [
                'commandName' => 'CancelScheduledGame',
                'aggregateName' => 'ScheduledGame',
                'commandProps' => ['scheduledGameId', 'reason'],
                'event' => [
                    'eventName' => 'GameWasCancelled',
                    'eventProps' => ['scheduledGameId', 'reason'],
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

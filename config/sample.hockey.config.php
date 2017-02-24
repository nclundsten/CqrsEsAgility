<?php

return [
    'HockeyTracker' => [
        'actions' => [
            'AddPlayer' => [],
            'AddTeam' => [],
            'AddPlayerToTeam' => [],
            'ScheduleGame' => [],
            'StartGame' => [],
            'AddPointsForTeamInGame' => [],
            'EndGame' => [],

            'RemovePlayerFromTeam' => [],
            'RescheduleGame' => [],
            'CancelScheduledGame' => [],
        ],
        'aggregates' => [
            'Player' => [ /*things specific to a player*/ ],
            'Team' => [ /*things specific to a team*/ ],
            'TeamPlayer' => [ /*things specific to a player on a team*/ ],
            'ScheduledGame' => [ /*things specific to a scheduled game between teams*/ ],
            'Game' => [ /*things specific to a game played between teams*/ ],
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
            'AddPlayerToTeam' => [
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
            'ScheduleGame' => [
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
                        'NotifyHomeTeamFollowersOfScheduledGame' => [
                            'commands' => [
                                'NotifyHomeTeamFollowersOfScheduledGame' => [
                                    'commandProps' => ['homeTeamId', 'awayTeamId', 'scheduledDateTime'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'StartGame' => [
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
            'EndGame' => [
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
            'AddPointsForTeamInGame' => [
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
            'RemovePlayerFromTeam' => [
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
            'RescheduleGame' => [
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
            'CancelScheduledGame' => [
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

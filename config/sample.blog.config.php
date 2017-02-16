<?php

return [
    'Blog' => [
        'actions' => [
            'CreateBlogPost' => [
                'method' => 'post',
                'command' => "CreateBlogPost",
            ],
            'CommentOnBlogPost' => [
                'method' => 'post',
                'command' => "CommentOnBlogPost",
            ],
        ],
        'commands' => [
            'CreateBlogPost' => [
                'commandProps' => [ /* TODO */ ],
                'event' => [
                    'eventName' => 'BlogPostWasCreated',
                    'eventProps' => [ /* TODO */ ],
                    'projectors' => [
                        'BlogPost',
                    ],
                    'listeners' => [
                        'SendEmailsWhenBlogPostWasCreated' => [
                            'commands' => [
                                'SendEmailToPosterWhenBlogPostWasCreated' => [
                                    'commandProps' => [ /* TODO */ ],
                                    'event' => null,
                                    'projectors' => [/*none*/],
                                    'listeners' => [/* none */],
                                ],
                                'SendEmailToFollowersWhenBlogPostWasCreated' => [
                                    'commandProps' => [ /* TODO */ ],
                                    'event' => null,
                                    'projectors' => [/*none*/],
                                    'listeners' => [/* none */],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'AddCommentToBlogPost' => [
                'commandProps' => [ /* TODO */ ],
                'event' => [
                    'eventName' => 'CommentWasAddedToBlogPost',
                    'eventProps' => [ /* TODO */ ],
                    'projectors' => [
                        'BlogPost',
                    ],
                    'listeners' => [
                        'SendEmailsWhenCommentWasAddedToBlogPost' => [
                            'commands' => [
                                'SendEmailToCommenterWhenCommentWasAddedToBlogPost' => [
                                    'commandProps' => [ /* TODO */ ],
                                    'event' => null,
                                    'projectors' => [/*none*/],
                                    'listeners' => [/* none */],
                                ],
                                'SendEmailToOriginalPosterWhenCommentWasAddedToBlogPost' => [
                                    'commandProps' => [ /* TODO */ ],
                                    'event' => null,
                                    'projectors' => [/*none*/],
                                    'listeners' => [/* none */],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ]
];

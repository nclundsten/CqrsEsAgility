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
                'commandProps' => ['blogPost', 'postedByUserId'],
                'event' => [
                    'eventName' => 'BlogPostWasCreated',
                    'eventProps' => [],
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
                'commandProps' => ['blogPostId', 'comment', 'commentedByUserId'],
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

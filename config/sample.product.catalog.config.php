<?php

return [
    'ProductCatalog' => [
        'commands' => [
            'AddProductToCatalog' => [
                'commandProps' => [ /* TODO */ ],
                'event' => [
                    'eventName' => 'ProductWasAddedToCatalog',
                    'eventProps' => [ /* TODO */ ],
                    'projectors' => [
                        'Products',
                    ],
                    'listeners' => [
                    ],
                ],
            ],
        ],
    ],
    'Cart' => [
        'commands' => [
            'AddProductToCart' => [
                'commandProps' => [ /* TODO */ ],
                'event' => [
                    'eventName' => 'ProductWasAddedToCart',
                    'eventProps' => [ /* TODO */ ],
                    'projectors' => [
                        'Products',
                    ],
                    'listeners' => [
                    ],
                ],
            ],
        ],
    ],
    'WarehouseProducts' => [
        'commands' => [
        ],
    ],
];

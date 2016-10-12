<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
        ],
        'factories' => [
            Zeus\Action\Artemis\CollectAction::class => Zeus\Action\Artemis\Factory\CollectActionFactory::class,
            Zeus\Action\Hermes\CollectAction::class => Zeus\Action\Hermes\Factory\CollectActionFactory::class,
            Zeus\Action\Hermes\ViewAction::class => Zeus\Action\Hermes\Factory\ViewActionFactory::class,
            Zeus\Action\Hermes\GraphAction::class => Zeus\Action\Hermes\Factory\GraphActionFactory::class,
        ],
    ],

    'routes' => [
        [
            'name' => 'hermes.collect',
            'path' => '/v1/hermes/collect',
            'middleware' => Zeus\Action\Hermes\CollectAction::class,
            'allowed_methods' => ['POST'],
        ],
        [
            'name' => 'hermes.view',
            'path' => '/v1/hermes/view/{id}[/{project}]',
            'middleware' => Zeus\Action\Hermes\ViewAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'hermes.graph',
            'path' => '/v1/hermes/{graph}[/{project}]',
            'middleware' => Zeus\Action\Hermes\GraphAction::class,
            'allowed_methods' => ['GET'],
        ],
        [
            'name' => 'artemis.collect',
            'path' => '/v1/artemis/collect',
            'middleware' => Zeus\Action\Artemis\CollectAction::class,
            'allowed_methods' => ['POST'],
        ],
    ],
];

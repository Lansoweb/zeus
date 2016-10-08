<?php

return [
    'dependencies' => [
        'factories' => [
            'Zend\Expressive\FinalHandler' =>
                Zend\Expressive\Container\TemplatedErrorHandlerFactory::class,

            Zend\Expressive\Template\TemplateRendererInterface::class =>
                Zend\Expressive\ZendView\ZendViewRendererFactory::class,
        ],
    ],

    'templates' => [
        'layout' => 'layout/default',
        'map' => [
            'layout/default' => 'templates/layout/default.phtml',
            'error/error'    => 'templates/error/error.phtml',
            'error/404'      => 'templates/error/404.phtml',
        ],
        'paths' => [
            'hermes'    => ['templates/hermes'],
            'layout' => ['templates/layout'],
            'error'  => ['templates/error'],
        ],
    ],

    'view_helpers' => [
        'invokables' => [
            'losFormRow' => Portal\Form\View\Helper\LosFormRow::class,
            'losFormElementErrors' => Portal\Form\View\Helper\LosFormElementErrors::class,
            'showDate' => Portal\View\Helper\ShowDate::class,
        ],
        'factories' => [
            'staticLink' => Portal\View\Helper\Factory\StaticLinkFactory::class,
            'zooxS3Link' => Portal\View\Helper\Factory\ZooxS3LinkFactory::class,
            'authService' => Portal\View\Helper\Factory\AuthServiceFactory::class,
            'languageSelect' => Portal\View\Helper\Factory\LanguageSelectFactory::class,
        ],
    ],
];

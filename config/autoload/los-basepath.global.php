<?php

return [

    'los_basepath' => '/zeus',

    'dependencies' => [
        'factories' => [
            LosMiddleware\BasePath\BasePath::class => LosMiddleware\BasePath\BasePathFactory::class,
        ],
    ],

    'middleware_pipeline' => [
        'routing' => [
            'middleware' => [
                LosMiddleware\BasePath\BasePath::class,
            ],
        ],
    ],

];

<?php
return array(
    'router' => array(
        'routes' => array(
            'hermes' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/hermes',
                    'defaults' => array(
                        'controller' => 'Hermes\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => [
                    'cose' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/cose',
                            'defaults' => array(
                                'controller' => 'Hermes\Controller\Index',
                                'action'     => 'cose',
                            ),
                        ),
                    ),
                    'circle' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/circle',
                            'defaults' => array(
                                'controller' => 'Hermes\Controller\Index',
                                'action'     => 'circle',
                            ),
                        ),
                    ),
                    'request-view' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/request/:id',
                            'defaults' => array(
                                'controller' => 'Hermes\Controller\Index',
                                'action'     => 'request',
                            ),
                        ),
                    ),
                ],
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Hermes\Controller\Index' => 'Hermes\Controller\IndexController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Hermes' => __DIR__.'/../view',
        ),
    ),
);

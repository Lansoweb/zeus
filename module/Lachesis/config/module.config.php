<?php
return array(
    'router' => array(
        'routes' => array(
            'lachesis' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/lachesis[/:project]',
                    'defaults' => array(
                        'controller' => 'Lachesis\Controller\Index',
                        'action'     => 'index',
                        'project' => 0,
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => [
                    'detail' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/detail/:id',
                            'defaults' => array(
                                'controller' => 'Lachesis\Controller\Index',
                                'action'     => 'detail',
                                'id' => 0,
                            ),
                        ),
                    ),
                    'occurrence' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/occurrence/:id',
                            'defaults' => array(
                                'controller' => 'Lachesis\Controller\Index',
                                'action'     => 'occurrence',
                                'id' => 0,
                            ),
                        ),
                    ),
                ],
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Lachesis\Controller\Index' => 'Lachesis\Controller\IndexController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Lachesis' => __DIR__.'/../view',
        ),
    ),
);

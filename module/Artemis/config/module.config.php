<?php
return array(
    'router' => array(
        'routes' => array(
            'artemis' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/artemis[/:project]',
                    'defaults' => array(
                        'controller' => 'Artemis\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => [
                    'detail' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/detail/:id',
                            'defaults' => array(
                                'controller' => 'Artemis\Controller\Index',
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
                                'controller' => 'Artemis\Controller\Index',
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
            'Artemis\Controller\Index' => 'Artemis\Controller\IndexController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Artemis' => __DIR__.'/../view',
        ),
    ),
);

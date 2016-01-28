<?php
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'cose' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/cose',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'cose',
                    ),
                ),
            ),
            'circle' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/circle',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'circle',
                    ),
                ),
            ),
            'request-view' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/view/request/:id',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'request',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);

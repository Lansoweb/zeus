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
            'zeus' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/:project',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                        'project' => 0,
                    ),
                ),
                'may_terminate' => true,
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
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
    'view_helpers' => [
        'invokables' => [
            'select_project' => 'Application\View\Helper\SelectProject',
        ],
    ],
    'navigation' => array(
        'default' => array(
            'hermes' => array(
                'label' => 'Requests',
                'route' => 'zeus/hermes',
                'use_route_match' => true,
            ),
            'artemis' => array(
                'label' => 'Errors',
                'route' => 'zeus/artemis',
                'use_route_match' => true,
            ),
            'lachesis' => array(
                'label' => 'Database',
                'route' => 'zeus/lachesis',
                'use_route_match' => true,
            )
        )
    )
);

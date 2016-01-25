<?php
return array(
    'service_manager' => array(
        'factories' => array(),
    ),
    'router' => array(
        'routes' => array(
            'api.rpc.request' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/request',
                    'defaults' => array(
                        'controller' => 'Api\\V1\\Rpc\\Request\\Controller',
                        'action' => 'request',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            1 => 'api.rpc.request',
        ),
    ),
    'zf-rest' => array(),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Api\\V1\\Rpc\\Request\\Controller' => 'Json',
        ),
        'accept_whitelist' => array(
            'Api\\V1\\Rpc\\Request\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
        ),
        'content_type_whitelist' => array(
            'Api\\V1\\Rpc\\Request\\Controller' => array(
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(),
    ),
    'zf-content-validation' => array(
        'Api\\V1\\Rpc\\Request\\Controller' => array(
            'input_filter' => 'Api\\V1\\Rpc\\Request\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'Api\\V1\\Rest\\Service\\Validator' => array(
            0 => array(
                'name' => 'id',
                'required' => false,
                'filters' => array(),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Regex',
                        'options' => array(
                            'pattern' => '/[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-4[a-fA-F0-9]{3}-[89aAbB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}/',
                        ),
                    ),
                ),
            ),
            1 => array(
                'name' => 'name',
                'required' => true,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => '64',
                        ),
                    ),
                ),
                'description' => 'Service name',
            ),
            2 => array(
                'name' => 'address',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => '128',
                        ),
                    ),
                ),
                'description' => 'Service address (ip or dns name)',
            ),
            3 => array(
                'name' => 'port',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\Digits',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\Between',
                        'options' => array(
                            'min' => '1',
                            'max' => '65535',
                        ),
                    ),
                ),
                'description' => 'Service port',
            ),
            4 => array(
                'name' => 'code',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => '16',
                        ),
                    ),
                ),
                'description' => 'Service code (id)',
            ),
            5 => array(
                'name' => 'version',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => '10',
                        ),
                    ),
                ),
                'description' => 'Service version (eg. v1)',
            ),
            6 => array(
                'name' => 'datacenter',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => '16',
                        ),
                    ),
                ),
                'description' => 'Datacenter ID (eg. sa-east)',
            ),
            7 => array(
                'name' => 'ping',
                'required' => false,
                'filters' => array(),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\I18n\\Validator\\DateTime',
                        'options' => array(
                            'timezone' => 'UTC',
                        ),
                    ),
                ),
                'description' => 'Last time this service was reported online',
            ),
            8 => array(
                'name' => 'uri',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => '128',
                        ),
                    ),
                ),
                'description' => 'Service uri',
            ),
        ),
        'Api\\V1\\Rpc\\Request\\Validator' => array(
            0 => array(
                'required' => false,
                'validators' => array(),
                'filters' => array(),
                'name' => 'id',
                'description' => 'Request ID',
                'continue_if_empty' => true,
                'allow_empty' => true,
            ),
            1 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'path',
                'description' => 'Request path',
            ),
            2 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'method',
                'description' => 'Request method (get, post, etc)',
            ),
            3 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'service',
                'description' => 'Service name',
            ),
            4 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'server',
                'description' => 'Server identification (ip for example)',
            ),
            5 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'source',
                'description' => 'Source identification of the request (other service name, www, etc)',
            ),
            6 => array(
                'required' => true,
                'validators' => array(),
                'filters' => array(),
                'name' => 'date',
                'description' => 'Request datetime',
            ),
        ),
    ),
    'zf-mvc-auth' => array(
        'authorization' => array(),
    ),
    'zf-apigility' => array(
        'db-connected' => array(
            'Api\\V1\\Rest\\Service2\\ServiceResource' => array(
                'adapter_name' => 'ApiDB',
                'table_name' => 'service',
                'hydrator_name' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
                'controller_service_name' => 'Api\\V1\\Rest\\Service2\\Controller',
                'entity_identifier_name' => 'id',
            ),
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Api\\V1\\Rpc\\Request\\Controller' => 'Api\\V1\\Rpc\\Request\\RequestControllerFactory',
        ),
    ),
    'zf-rpc' => array(
        'Api\\V1\\Rpc\\Request\\Controller' => array(
            'service_name' => 'Request',
            'http_methods' => array(
                0 => 'POST',
            ),
            'route_name' => 'api.rpc.request',
        ),
    ),
);

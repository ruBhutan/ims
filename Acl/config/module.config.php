<?php

Namespace Acl;

return array(
    'controllers' => array(
        'factories' => array(
            'Acl\Controller\Acl' => 'Acl\Factory\AclControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'acl' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/acl[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Acl\Controller\Acl',
                        'action' => 'index'
                    )
                )
            ),
            'unauthorized' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => 'unauthorized',
                    'defaults' => array(
                        'controller' => 'Acl\Controller\Acl',
                        'action' => 'unauthorized',
                    ),
                ),
            ),
        )
    ),
    'service_manager' => array(
        'invokables' => array(
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'acl' => __DIR__ . '/../view'
        )
    ),
    'controller_plugins' => array(
        'factories' => array(
        )
    ),
    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);

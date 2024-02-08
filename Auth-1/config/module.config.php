<?php

Namespace Auth;

return array(
    'controllers' => array(
        'factories' => array(
            'Auth\Controller\Auth' => 'Auth\Factory\AuthControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'auth' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/auth[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-z0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Auth',
                        'action' => 'login'
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        'invokables' => array(
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'auth' => __DIR__ . '/../view'
        )
    ),
    'controller_plugins' => array(
        'factories' => array(
            'authPlugin' => 'Auth\Factory\AuthPluginFactory'
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
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'User\Entity\User',
                'identity_property' => 'username',
                'credential_property' => 'password',
            ),
        )
    )
);

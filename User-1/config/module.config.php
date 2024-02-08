<?php

Namespace User;

return array(
    'controllers' => array(
        'factories' => array(
            'User\Controller\User' => 'User\Factory\UserControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'user' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/user[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\User',
                        'action' => 'welcome'
                    )
                )
            ),
        )
    ),
    'service_manager' => array(
        'invokables' => array(
            'User\Entity\User' => 'User\Entity\User',
            //'User\Entity\Role' => 'User\Entity\Role'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'user' => __DIR__ . '/../view'
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

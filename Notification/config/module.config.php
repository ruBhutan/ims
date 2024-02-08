<?php

return array(
    'service_manager'=>array(
        'factories'=> array(
            'Notification\Mapper\NotificationMapperInterface' => 'Notification\Factory\ZendDbSqlMapperFactory',
            'Notification\Service\NotificationServiceInterface'=> 'Notification\Factory\NotificationServiceFactory',
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Notification' => 'Notification\Factory\NotificationControllerFactory',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'notificationPlugin' => 'Notification\Factory\NotificationServiceFactory'
        )
    ),
    'router' => array(
        'routes' => array(
            'notification' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/notification',
                    'defaults' => array(
                        'controller' => 'Notification',
                        'action' => 'addNotification',
                    ),
                ),
            )
        ),
    ),
);
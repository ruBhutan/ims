<?php

return array(
    'service_manager'=>array(
        'factories'=> array(
            'AuditTrail\Mapper\AuditTrailMapperInterface' => 'AuditTrail\Factory\ZendDbSqlMapperFactory',
            'AuditTrail\Service\AuditTrailServiceInterface'=> 'AuditTrail\Factory\AuditTrailServiceFactory',
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'AuditTrail' => 'AuditTrail\Factory\AuditTrailControllerFactory',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'audittrailPlugin' => 'AuditTrail\Factory\AuditTrailServiceFactory'
        )
    ),
    'router' => array(
        'routes' => array(
            'audit-trail' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/audit-trail',
                    'defaults' => array(
                        'controller' => 'AuditTrail',
                        'action' => 'addAuditTrail',
                    ),
                ),
            ),
	
	'audit-trail' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/audit-trail',
                    'defaults' => array(
                        'controller' => 'AuditTrail',
                        'action' => 'addLastLogin',
                    ),
                ),
            ),

        ),
    ),
);
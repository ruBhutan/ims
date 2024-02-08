<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AuditTrail;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;


class Module implements ConfigProviderInterface, AutoloaderProviderInterface
{
    public function getAutoloaderConfig() 
          {
         return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'AuditTrail' => __DIR__ . '/src/' . 'AuditTrail',
                ),
            ),
        );
    }
    
    public function getConfig() {
        return include __DIR__. '/config/module.config.php';
    }
    
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'AuditTrail\Model\AuditTrailTable' => function($sm) {
                    $tableGateway = $sm->get('AuditTrailTableGateway');
                    $table = new AuditTrailTable($tableGateway);
                    return $table;
                },
                'AuditTrailTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new AuditTrail());
                    return new TableGateway('AuditTrail', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
    
}
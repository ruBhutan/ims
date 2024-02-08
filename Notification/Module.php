<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Notification;

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
                    'Notification' => __DIR__ . '/src/' . 'Notification',
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
                'Notification\Model\NotificationTable' => function($sm) {
                    $tableGateway = $sm->get('NotificationTableGateway');
                    $table = new NotificationTable($tableGateway);
                    return $table;
                },
                'NotificationTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Notification());
                    return new TableGateway('notification', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
    
}
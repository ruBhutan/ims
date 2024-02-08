<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GuestHouseManagement;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
//use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;


class Module implements ConfigProviderInterface, AutoloaderProviderInterface
{
    public function getAutoloaderConfig() 
          {
         return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                   'AddGuestHouse' => __DIR__ . '/src/' . 'AddGuestHouse',
                   'GuestHouseRoom' => __DIR__ . '/src/' . 'GuestHouseRoom',
                   'BookGuestHouse' => __DIR__ . '/src/' . 'BookGuestHouse',
                   'GuestRoomBookApproval' => __DIR__ . '/src/' . 'GuestRoomBookApproval',
                ),
            ),
        );
    }
    
    public function getConfig() {
        return include __DIR__. '/config/module.config.php';
    }
    
   /*  public function getServiceConfig() {
        return include __DIR__. '/config/service.config.php';
    }*/
   
}
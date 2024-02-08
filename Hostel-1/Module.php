<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Hostel;

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
                    'Hostel' => __DIR__ . '/src/' . 'Hostel',
					'HostelDetail' => __DIR__ . '/src/' . 'HostelDetail',
                    'HostelRoom' => __DIR__ . '/src/' . 'HostelRoom',
                    'HostelAllocation' => __DIR__ . '/src/' . 'HostelAllocation',
                    'HostelChange' => __DIR__ . '/src/' . 'HostelChange',
                    
                ),
            ),
        );
    }
    
    public function getConfig() {
        return include __DIR__. '/config/module.config.php';
    }
       
}
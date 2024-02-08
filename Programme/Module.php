<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Programme;

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
                    'NewProgramme' => __DIR__ . '/src/' . 'NewProgramme',
					'Programme' => __DIR__ . '/src/' . 'Programme',
					'ExternalExaminer' => __DIR__ . '/src/' . 'ExternalExaminer',
                ),
            ),
        );
    }
    
    public function getConfig() {
        return include __DIR__. '/config/module.config.php';
    }

   
}
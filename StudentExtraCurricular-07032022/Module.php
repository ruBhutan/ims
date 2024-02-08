<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace StudentExtraCurricular;

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
                    'ExtraCurricularAttendance' => __DIR__ . '/src/' . 'ExtraCurricularAttendance',
                    'StudentParticipation' => __DIR__ . '/src/' . 'StudentParticipation',
                    'StudentContribution' => __DIR__ . '/src/' . 'StudentContribution',
					'Clubs' => __DIR__ . '/src/' . 'Clubs',
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
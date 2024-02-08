<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace HrPlanning;

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
                    'EmpWorkForceProposal' => __DIR__ . '/src/' . 'EmpWorkForceProposal',
                    'EmpWorkForceApproval' => __DIR__ . '/src/' . 'EmpWorkForceApproval',
					'HrdPlan' => __DIR__ . '/src/' . 'HrdPlan',
                    'HrmPlan' => __DIR__ . '/src/' . 'HrmPlan',
                    'HrActivation' => __DIR__ . '/src/' . 'HrActivation',
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
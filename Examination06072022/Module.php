<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Examination;

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
                    'Examinations' => __DIR__ . '/src/' . 'Examinations',
					'Reassessment' => __DIR__ . '/src/' . 'Reassessment',
					'RecheckMarks' => __DIR__ . '/src/' . 'RecheckMarks',
					'RepeatModules' => __DIR__ . '/src/' . 'RepeatModules',
					'ExamHall' => __DIR__ . '/src/' . 'ExamHall',
                    'ExamHallArrangement' => __DIR__ . '/src/' . 'ExamHallArrangement',
                    'EligibleStudent' => __DIR__ . '/src/' . 'EligibleStudent',
                    'ExamInvigilator' => __DIR__ . '/src/' . 'ExamInvigilator',
                    'CodeGeneration' => __DIR__ . '/src/' . 'CodeGeneration',
                    'EntryCard' => __DIR__ . '/src/' . 'EntryCard',
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
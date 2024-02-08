<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pms;

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
                    'Appraisal' => __DIR__ . '/src/' . 'Appraisal',
                    'Review' => __DIR__ . '/src/' . 'Review',
					'Nominations' => __DIR__ . '/src/' . 'Nominations',
					'PmsRatings' => __DIR__ . '/src/' . 'PmsRatings',
					'PmsDates' => __DIR__ . '/src/' . 'PmsDates',
                ),
            ),
        );
    }
    
    public function getConfig() {
        return include __DIR__. '/config/module.config.php';
    }
       
}
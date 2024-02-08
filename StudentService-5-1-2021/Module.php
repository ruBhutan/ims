<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 * This module is for the following Student Services
 * 1. Student Responsibiity
 * 2. Discipline 
 * 3. Student Chartacter Certificate
 * 4. Student Achievements
 * 5. Student Leave
 */

namespace StudentService;

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
                    'Responsibilities' => __DIR__ . '/src/' . 'Responsibilities',
					'Discipline' => __DIR__ . '/src/' . 'Discipline',
					'CharacterCertificate' => __DIR__ . '/src/' . 'CharacterCertificate',
					'Achievements' => __DIR__ . '/src/' . 'Achievements',
                    'StudentImage' => __DIR__ . '/src/' . 'StudentImage',
					'MedicalRecord' => __DIR__ . '/src/' . 'MedicalRecord',
					'StudentLeave' => __DIR__ . '/src/' . 'StudentLeave',
					'StudentSuggestions' => __DIR__ . '/src/' . 'StudentSuggestions',
                    
                ),
            ),
        );
    }
    
    public function getConfig() {
        return include __DIR__. '/config/module.config.php';
    }
   
}
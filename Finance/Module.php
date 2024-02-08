<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance;

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
                    'Masters' => __DIR__ . '/src/' . 'Masters',
					'PayrollManagement' => __DIR__ . '/src/' . 'PayrollManagement',
					'ChequeManagement' => __DIR__ . '/src/' . 'ChequeManagement',
					'Voucher' => __DIR__ . '/src/' . 'Voucher',
					'FeesCategory' => __DIR__ . '/src/' . 'FeesCategory',
                    'FeeSubCategory' => __DIR__ . '/src/' . 'FeeSubCategory',
                    'FeeAllocation' => __DIR__ . '/src/' . 'FeeAllocation',
                    'FeeImport' => __DIR__ . '/src/' . 'FeeImport',
                    'FeeCollection' => __DIR__ . '/src/' . 'FeeCollection',
                    'FeesReport' => __DIR__ . '/src/' . 'FeesReport',
                    'AccountGroup' => __DIR__ . '/src/' . 'AccountGroup',
                    'VoucherMaster' => __DIR__ . '/src/' . 'VoucherMaster',
                    'VoucherHead' => __DIR__ . '/src/' . 'VoucherHead',
                    'CreateVoucher' => __DIR__ . '/src/' . 'CreateVoucher',
                    'Finance' => __DIR__ . '/src/' . 'Finance',
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
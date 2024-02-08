<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Budgeting;

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
                    'FinanceCodes' => __DIR__ . '/src/' . 'FinanceCodes',
					'BudgetTransactions' => __DIR__ . '/src/' . 'BudgetTransactions',
					'Budgeting' => __DIR__ . '/src/' . 'Budgeting',
					'BudgetingCategory' => __DIR__ . '/src/' . 'BudgetingCategory',
                    'BudgetProposal' => __DIR__ . '/src/' . 'BudgetProposal',
                    'BudgetApproval' => __DIR__ . '/src/' . 'BudgetApproval',
                    'IncomeSource' => __DIR__ . '/src/' . 'IncomeSource',
                    'IncomeDetails' => __DIR__ . '/src/' . 'IncomeDetails',
                    'IncomeApproval' => __DIR__ . '/src/' . 'IncomeApproval',
                    'ExpenditureCategory' => __DIR__ . '/src/' . 'ExpenditureCategory',
                    'ExpenditureDetails' => __DIR__ . '/src/' . 'ExpenditureDetails',
                    'ExpenditureApproval' => __DIR__ . '/src/' . 'ExpenditureApproval',
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
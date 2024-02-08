<?php
namespace Accounts; 

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;


class Module
{
	public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
	
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'initializers' => array(
            		function ($instance, $sm) {
            			if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
            				$instance->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
            			}
            		}
            ),
            'invokables' => array(
            		'Accounts\ClassTable' =>  'Accounts\Model\ClassTable',
            		'Accounts\GroupTable' =>  'Accounts\Model\GroupTable',
            		'Accounts\HeadTable' =>  'Accounts\Model\HeadTable',
            		'Accounts\TypeTable' =>  'Accounts\Model\TypeTable',
            		'Accounts\SubheadTable' =>  'Accounts\Model\SubheadTable',
            		'Accounts\FundTable' =>  'Accounts\Model\FundTable',
            		'Accounts\AssetsTable' =>  'Accounts\Model\AssetsTable',
                    'Accounts\BankaccountTable' =>  'Accounts\Model\BankaccountTable',
                    'Accounts\BankreftypeTable' =>  'Accounts\Model\BankreftypeTable',
                    'Accounts\TransactionTable' =>  'Accounts\Model\TransactionTable',
                    'Accounts\TransactiondetailTable' =>  'Accounts\Model\TransactiondetailTable',
                    'Accounts\JournalTable' =>  'Accounts\Model\JournalTable',
                    'Accounts\CurrencyTable' =>  'Accounts\Model\CurrencyTable',
                    'Accounts\CashaccountTable' =>  'Accounts\Model\CashaccountTable',
                    'Accounts\EmployeeDetailsTable' =>  'Accounts\Model\EmployeeDetailsTable',
                    'Accounts\PartyTable' =>  'Accounts\Model\PartyTable',
                    'Accounts\AppointmentTypeTable' =>  'Accounts\Model\AppointmentTypeTable',
                    'Accounts\PayrollEmployeeTable' =>  'Accounts\Model\PayrollEmployeeTable',
            		'Accounts\PaygroupTable' =>  'Accounts\Model\PaygroupTable',
            		'Accounts\PayheadTable' =>  'Accounts\Model\PayheadTable',
            		'Accounts\PaystructureTable' =>  'Accounts\Model\PaystructureTable',            	
            		'Accounts\PayrollTable' =>  'Accounts\Model\PayrollTable',
            		'Accounts\TempPayrollTable' =>  'Accounts\Model\TempPayrollTable',
            		'Accounts\PaydetailTable' => 'Accounts\Model\PaydetailTable',
            		'Accounts\PaySlabTable' => 'Accounts\Model\PaySlabTable',
            		'Accounts\SalarybookingTable' => 'Accounts\Model\SalarybookingTable',
					'Accounts\ChequeTable' => 'Accounts\Model\ChequeTable',
					'Accounts\ChequeDetailsTable' => 'Accounts\Model\ChequeDetailsTable',
					'Accounts\ClosingbalanceTable' => 'Accounts\Model\ClosingbalanceTable',
					'Accounts\PayscaleTable' => 'Accounts\Model\PayscaleTable',
					'Accounts\TDSTable' => 'Accounts\Model\TDSTable',
					'Accounts\BankreftypeTable' => 'Accounts\Model\BankreftypeTable',
					'Accounts\MasterDetailsTable' => 'Accounts\Model\MasterDetailsTable',
					'Accounts\PartyRoleTable' => 'Accounts\Model\PartyRoleTable',
					'Accounts\StatusTable' => 'Accounts\Model\StatusTable',
            ),
        );
    }
}

<?php

namespace BudgetTransactions\Factory;

use BudgetTransactions\Controller\BudgetTransactionsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BudgetTransactionsControllerFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$realServiceLocator = $serviceLocator->getServiceLocator();
		$transactionService = $realServiceLocator->get('BudgetTransactions\Service\BudgetTransactionsServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new BudgetTransactionsController($transactionService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}
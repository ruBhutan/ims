<?php

namespace Accounts\Factory;

use Accounts\Controller\PayrollController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PayrollControllerFactory implements FactoryInterface {

    /**
     * create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return PayrollController|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $service = $realServiceLocator->get('Accounts\Service\PayrollServiceInterface');
        $notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');

        return new PayrollController($service, $notificationService, $auditTrailService, $realServiceLocator);
    }

}

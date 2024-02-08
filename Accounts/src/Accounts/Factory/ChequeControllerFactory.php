<?php

namespace Accounts\Factory;

use Accounts\Controller\ChequeController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ChequeControllerFactory implements FactoryInterface {

    /**
     * create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ChequeController|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $service = $realServiceLocator->get('Accounts\Service\ChequeServiceInterface');
        $notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');

        return new ChequeController($service, $notificationService, $auditTrailService, $realServiceLocator);
    }

}

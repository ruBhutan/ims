<?php

namespace Accounts\Factory;

use Accounts\Controller\FeeStructureController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FeeStructureControllerFactory implements FactoryInterface {

    /**
     * create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return FeeStructureController|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $realServiceLocator = $serviceLocator->getServiceLocator();
		$service = $realServiceLocator->get('Accounts\Service\FeeStructureServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');

        return new FeeStructureController($service, $notificationService, $auditTrailService, $realServiceLocator);
    }

}

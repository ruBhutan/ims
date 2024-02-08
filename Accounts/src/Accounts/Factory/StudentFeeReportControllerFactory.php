<?php

namespace Accounts\Factory;

use Accounts\Controller\StudentFeeReportController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentFeeReportControllerFactory implements FactoryInterface {

    /**
     * create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return StudentFeeCategoryController|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {

        $realServiceLocator = $serviceLocator->getServiceLocator();
        $feeStructurService = $realServiceLocator->get('Accounts\Service\FeeStructureServiceInterface');
        $notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');

        return new StudentFeeReportController($feeStructurService, $notificationService, $auditTrailService, $realServiceLocator);
    }

}

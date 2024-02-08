<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Notification\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Notification\Service\NotificationServiceInterface;

/**
 * Description of NotificationController
 *
 * @author Mendrel
 */
class NotificationController extends AbstractActionController {

    protected $notificationService;

    public function __construct(NotificationServiceInterface $notificationService) {
        $this->notificationService = $notificationService;
    }

    public function addNotificationAction() {
        try {
            $this->notificationService->saveNotification('Leave', 'DAA', 'DAA', 'Leave Application');
            exit("Notification Added");
            $this->redirect()->toRoute('registrantemploymentrecord');
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

}

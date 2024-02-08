<?php

namespace Notification\Service;

use Notification\Mapper\NotificationMapperInterface;
use Notification\Model\Notification;

class NotificationService implements NotificationServiceInterface
{
    /**
     * @var \Notification\Mapper\NotificationMapperInterface
    */

    protected $notificationMapper;
    protected $authService;

    public function __construct(NotificationMapperInterface $notificationMapper, $authService) {
        $this->notificationMapper = $notificationMapper;
        $this->authService = $authService;
    }
    
    public function saveNotification($notification_type, $submission_to, $submission_to_department, $notification_status)
    {
        $currentUser = $this->authService->getIdentity();
        $notification = new Notification();
        $notification->setNotification_Type($notification_type);
        $notification->setSubmitted_By($currentUser->id);
        $notification->setSubmission_To($submission_to);
        $notification->setSubmission_To_Department($submission_to_department);
        $notification->setNotification_Status($notification_status);
        $notification->setNotification_Date(gmdate("Y-m-d h:i:s"));
        $notification->setView_Status('Pending');
        return $this->notificationMapper->saveNotification($notification);
    }
	
}
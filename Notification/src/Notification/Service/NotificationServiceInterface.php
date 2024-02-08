<?php

namespace Notification\Service;

use Notification\Model\Notification;

interface NotificationServiceInterface
{        
    /*
    * Save Notification
    */
    public function saveNotification($notification_type, $submission_to, $submission_to_department, $notification_status);
		
}
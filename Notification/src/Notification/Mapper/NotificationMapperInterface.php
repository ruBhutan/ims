<?php

namespace Notification\Mapper;

use Notification\Model\Notification;

interface NotificationMapperInterface
{
    /*
    * Save Notification
    */

    public function saveNotification(Notification $notification);
	
}
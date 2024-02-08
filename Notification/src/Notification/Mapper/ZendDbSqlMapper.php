<?php

namespace Notification\Mapper;

use Notification\Model\Notification;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements NotificationMapperInterface {

    /**
     * @var \Zend\Db\Adapter\AdapterInterface
     *
     */
    protected $dbAdapter;

    /*
     * @var \Zend\Stdlib\Hydrator\HydratorInterface
     */
    protected $hydrator;

    /*
     * @var \Notification\Model\NotificationInterface
     */
    protected $notificationPrototype;

    /**
     * @param AdapterInterface $dbAdapter
     */
    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, Notification $notificationPrototype) {
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->notificationPrototype = $notificationPrototype;
    }

    /*
     * Save Notification
     */

    public function saveNotification(Notification $notificationObject) {
        $notificationData = $this->hydrator->extract($notificationObject);

        $action = new Insert('notifications');
        $action->values($notificationData);
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface) {
            /*if ($newId = $result->getGeneratedValue()) {
                //when a value has been generated, set it on the object
                echo $notificationObject->setId($newId);
            }*/
            return $notificationObject;
        }

        throw new \Exception("Error occured on sending notification");
    }

}

<?php

namespace AuditTrail\Mapper;

use AuditTrail\Model\AuditTrail;
use AuditTrail\Model\User;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements AuditTrailMapperInterface {

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
     * @var \AuditTrail\Model\AuditTrailInterface
     */
    protected $auditTrailPrototype;

    /**
     * @param AdapterInterface $dbAdapter
     */
    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, AuditTrail $auditTrailPrototype) {
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->auditTrailPrototype = $auditTrailPrototype;
    }

    /*
     * Save Audit Trail
     */

    public function saveAuditTrail(AuditTrail $auditTrailObject) {
        $auditTrailData = $this->hydrator->extract($auditTrailObject);
        
        $action = new Insert('audit_trail');
        
        $action->values($auditTrailData);
        
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface) {
            /*if ($newId = $result->getGeneratedValue()) {
                //when a value has been generated, set it on the object
                echo $auditTrailObject->setId($newId);
            }*/
            return $auditTrailObject;
        }

        throw new \Exception("Error occured on adding AuditTrail");
    }


    public function saveLastLogin($username)
    {
	date_default_timezone_set("Asia/Thimphu");
        $last_login = date("Y-m-d h:i:s");

        $action = new Update('users');
        $action->set(array('last_login' => $last_login));
        $action->where(array('username = ?' => $username));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    }

}

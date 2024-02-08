<?php

namespace AuditTrail\Service;

use Zend\Http\PhpEnvironment\RemoteAddress;
use AuditTrail\Mapper\AuditTrailMapperInterface;
use AuditTrail\Model\AuditTrail;
use AuditTrail\Model\User;

class AuditTrailService implements AuditTrailServiceInterface
{
    /**
     * @var \AuditTrail\Mapper\AuditTrailMapperInterface
    */

    protected $auditTrailMapper;
    protected $authService;

    public function __construct(AuditTrailMapperInterface $auditTrailMapper, $authService) {
        $this->auditTrailMapper = $auditTrailMapper;
        $this->authService = $authService;
    }
    
    public function saveAuditTrail($action, $table, $column = null, $status = null, $values = null)
    {
        date_default_timezone_set('Asia/Thimphu');
        $currentUser = $this->authService->getIdentity();
        $remoteAddress = new RemoteAddress();
        $auditTrail = new AuditTrail();
        $auditTrail->setUser_Name($currentUser->id);
        $auditTrail->setIpAddress($remoteAddress->getIpAddress());
        $auditTrail->setDate(date("Y-m-d h:i:s"));
        $auditTrail->setAction($action);
        $auditTrail->setTable($table);
        $auditTrail->setColumn($column);
        $auditTrail->setStatus($status);
        $auditTrail->setValues($values);
        return $this->auditTrailMapper->saveAuditTrail($auditTrail);
    }


    public function saveLastLogin($username)
    {
        return $this->auditTrailMapper->saveLastLogin($username);
    }
	
}
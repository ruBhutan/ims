<?php

namespace AuditTrail\Mapper;

use AuditTrail\Model\AuditTrail;
use AuditTrail\Model\User;

interface AuditTrailMapperInterface
{
    /*
    * Save AuditTrail
    */

    public function saveAuditTrail(AuditTrail $auditTrail);


    public function saveLastLogin($username);
	
}
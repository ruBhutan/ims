<?php

namespace AuditTrail\Mapper;

use AuditTrail\Model\AuditTrail;

interface AuditTrailMapperInterface
{
    /*
    * Save AuditTrail
    */

    public function saveAuditTrail(AuditTrail $auditTrail);

     public function saveLastLogin($username);
	
}
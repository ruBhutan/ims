<?php

namespace AuditTrail\Service;

interface AuditTrailServiceInterface
{        
    /*
    * Save AuditTrail
    */
    public function saveAuditTrail($action, $table, $column = null, $status = null, $values = null);

    public function saveLastLogin($username);
		
}
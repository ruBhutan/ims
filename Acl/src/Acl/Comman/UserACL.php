<?php

namespace Acl\Comman;

use Zend\Permissions\Acl\Acl;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Permissions\Acl\Role\GenericRole as Role;

class UserACL {

  protected $acl;
  protected $dbAdapter;
  protected $userAttributes;
  protected $whitelistRoutes = array('auth', 'home', 'unauthorized');

  public function __construct($serviceManager) {
    $this->acl = new Acl();
    $this->userAttributes = $serviceManager->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
    $this->dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
    
    // Set Whitelist Routes First
    foreach ($this->whitelistRoutes as $route) {
      $this->acl->addResource(new Resource($route));
    }
  }

  public function setRole() {
    $this->acl->addRole(new Role($this->userAttributes['role']));
  }

  public function setResources() {
    $sql = new Sql($this->dbAdapter);
    $select = $sql->select();
    $select->from(array('t1' => 'route_list'))->columns(array('route_details'));
    $stmt = $sql->prepareStatementForSqlObject($select);
    $resultSet = new ResultSet();
    $resultSet->initialize($stmt->execute());
    while ($resultSet->valid()) {
      $this->acl->addResource(new Resource($resultSet->current()->route_details));
      $resultSet->next();
    }
  }

  public function setPrivileges() {
    $accessibleResources = $this->whitelistRoutes;
    $sql = new Sql($this->dbAdapter);
    $select = $sql->select();
    //This statement will check for the side menu privilege
    $select->from(array('route' => 'user_routes'))->columns(array('route_details'))
          ->join(array('linker' => 'user_role_routes'), 'linker.user_routes_id = route.id', array())
          ->join(array('role' => 'user_role'), 'linker.user_role_id = role.id', array())
          ->where(array('role.rolename = ?' => $this->userAttributes['role']));
    $stmt = $sql->prepareStatementForSqlObject($select);
    $resultSet = new ResultSet();
    $resultSet->initialize($stmt->execute());
    while ($resultSet->valid()) {
      array_push($accessibleResources, $resultSet->current()->route_details);
      $resultSet->next();
    }
    //This statement will check for the sub routes based on the side menu privilege
    $select1 = $sql->select();
    $select1->from(array('route' => 'user_routes'))->columns(array('route_details'))
          ->join(array('linker' => 'user_role_routes'), 'linker.user_routes_id = route.id', array())
          ->join(array('role' => 'user_role'), 'linker.user_role_id = role.id', array())
          ->join(array('subroute' => 'user_sub_route_list'), 'subroute.user_route_details = route.route_details', array('user_sub_routes'))
          ->where(array('role.rolename = ?' => $this->userAttributes['role']));
    $stmt = $sql->prepareStatementForSqlObject($select1);
    $resultSet = new ResultSet();
    $resultSet->initialize($stmt->execute());
    while ($resultSet->valid()) {
      array_push($accessibleResources, $resultSet->current()->user_sub_routes);
      $resultSet->next();
    }
    $this->acl->allow($this->userAttributes['role'], $accessibleResources);
  }

  public function setAllRules() {
    $this->setRole();
    $this->setResources();
    $this->setPrivileges();
  }

  public function isRouteAccessible($route) {
    try {
      if ($this->acl->isAllowed($this->userAttributes['role'], $route)) {
        return true;
      } else {
        return false;
      }
    } catch (Exception $e) {
      echo $e;
    }
  }

}

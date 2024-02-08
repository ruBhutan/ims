<?php

namespace Job\Model;

use Zend\Db\TableGateway\TableGateway;

class JobTable
 {
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
	 	$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		 $resultSet = $this->tableGateway->select();
		 return $resultSet;
	}

	public function getEmpWorkForceApproval($id)
	{
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
			}
		return $row;
	}

	public function saveEmpWorkForceApproval(EmpWorkForceApproval $empWorkForceApproval)
	{
		
		/* $data = array(
		'emp_id' => $employeedetail->emp_id,
		'first_name' => $employeedetail->first_name,
                'middle_name' => $employeedetail->middle_name,  
                 'last_name' => $employeedetail->last_name,
                    'cid' => $employeedetail->cid,
                    'nationality' => $employeedetail->nationality,
                    'date_of_birth' => $employeedetail->date_of_birth,
                    'emp_house_no' => $employeedetail->emp_house_no,
                    'emp_thram_no' => $employeedetail->emp_thram_no,
                    'emp_dzongkhag' => $employeedetail->emp_dzongkhag,
                    'emp_gewog' => $employeedetail->emp_gewog,
                    'emp_village' => $employeedetail->emp_village,
                    'emp_category' => $employeedetail->emp_category,
                    'gender' => $employeedetail->gender,
                    'marital_status' => $employeedetail->marital_status,
		);
	
		$id = (int) $employeedetail->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
		if ($this->getEmployeeDetail($id)) {
			$this->tableGateway->update($data, array('id' => $id));
		} else {
			throw new \Exception('Album id does not exist');
			}
		}
		*/
	}

	public function deleteEmpWorkForceApproval($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
}
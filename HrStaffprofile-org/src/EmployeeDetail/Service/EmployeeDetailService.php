<?php

namespace EmployeeDetail\Service;

use EmployeeDetail\Mapper\EmployeeMapperInterface;
use EmployeeDetail\Model\EmployeeDetail;
use EmployeeDetail\Model\NewEmployeeDetail;
use EmployeeDetail\Model\NewEmployee;
use EmployeeDetail\Model\NewEmployeeDocuments;
use EmployeeDetail\Model\EmployeeAward;
use EmployeeDetail\Model\EmployeeContribution;
use EmployeeDetail\Model\EmployeeResponsibilities;
use EmployeeDetail\Model\EmployeeCommunityService;
use EmployeeDetail\Model\EmployeeRelationDetail;
use EmployeeDetail\Model\EmployeeEducation;
use EmployeeDetail\Model\EmployeePublications;
use EmployeeDetail\Model\EmployeeTrainings;
use EmployeeDetail\Model\EmployeeWorkExperience;
use EmployeeDetail\Model\EmployeeLevel;
use EmployeeDetail\Model\EmployeeTitle;
use EmployeeDetail\Model\EmployeeProfilePicture;
use EmployeeDetail\Model\EmployeePersonalDetails;
use EmployeeDetail\Model\EmployeePermanentAddress;
use EmployeeDetail\Model\EmployeeDisciplineRecord;
use EmployeeDetail\Model\EmployeeOnProbation;
use EmployeeDetail\Model\EmployeePayDetails;
use EmployeeDetail\Model\UpdateNewEmpDoc;

class EmployeeDetailService implements EmployeeDetailServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $employeeDetailMapper;
	
	public function __construct(EmployeeMapperInterface $employeeDetailMapper) {
		$this->employeeDetailMapper = $employeeDetailMapper;
	}
	
	public function listAllEmployees($organisation_id, $staff_id)
	{
		return $this->employeeDetailMapper->findAll($organisation_id, $staff_id);
	}


	public function listAllNewEmployees($organisation_id)
	{
		return $this->employeeDetailMapper->listAllNewEmployees($organisation_id);
	}

	public function listAllEmployeesOnProbation($organisation_id)
	{
		return $this->employeeDetailMapper->listAllEmployeesOnProbation($organisation_id);
	}


	public function getNewEmployeeDetails($id)
	{
		return $this->employeeDetailMapper->getNewEmployeeDetails($id);
	}

	public function getNewEmployeeGeneratedId($id)
	{
		return $this->employeeDetailMapper->getNewEmployeeGeneratedId($id);
	}


	public function getFileName($new_employee_id)
	{
		return $this->employeeDetailMapper->getFileName($new_employee_id);
	}


	public function getOVCHroEmailId($role)
	{
		return $this->employeeDetailMapper->getOVCHroEmailId($role);
	}


	public function getOrganisationDetails($organisation_id)
	{
		return $this->employeeDetailMapper->getOrganisationDetails($organisation_id);
	}


	public function getNewEmpFileName($new_employee_doc_id, $document_type)
	{
		return $this->employeeDetailMapper->getNewEmpFileName($new_employee_doc_id, $document_type);
	}

	public function getNewEmpFileUploaded($id)
	{
		return $this->employeeDetailMapper->getNewEmpFileUploaded($id);
	}
	
	 
	public function findEmployee($id, $type)
	{
		return $this->employeeDetailMapper->find($id, $type);
	}

	public function getEmpPermanentAddress($id)
	{
		return $this->employeeDetailMapper->getEmpPermanentAddress($id);
	}

	public function getEmployeeDetailsId($tableName, $id)
	{
		return $this->employeeDetailMapper->getEmployeeDetailsId($tableName, $id);
	}

	public function getEmployeeProfilePicture($id)
	{
		return $this->employeeDetailMapper->getEmployeeProfilePicture($id);
	}
	
	public function getUserDetailsId($username, $tableName)
	{
		return $this->employeeDetailMapper->getUserDetailsId($username, $tableName);
	}
	
	public function getUserDetails($username, $usertype)
	{
		return $this->employeeDetailMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->employeeDetailMapper->getUserImage($username, $usertype);
	}
	
	public function getOrganisationId($username)
	{
		return $this->employeeDetailMapper->getOrganisationId($username);
	}
        
	public function listEducationDetails($id) 
	{
		return $this->employeeDetailMapper->findEducationDetails($id);
	}
	
	public function listParentDetails($id) 
	{
		return $this->employeeDetailMapper->findParentDetails($id);
	}
	
	public function listSpouseDetails($id) 
	{
		return $this->employeeDetailMapper->findSpouseDetails($id);
	}
	
	public function listAwardDetails($id) 
	{
		return $this->employeeDetailMapper->findAwardDetails($id);
	}
	
	public function getExtraCurricularDetails($tableName, $organisation_id, $self_id)
	{
		return $this->employeeDetailMapper->getExtraCurricularDetails($tableName, $organisation_id, $self_id);
	}
	
	public function getEmployeeList($empName, $empId, $department, $organisation_id, $self_id, $role)
	{
		return $this->employeeDetailMapper->getEmployeeList($empName, $empId, $department, $organisation_id, $self_id, $role);
	}

	public function getEmployeeDetails($employee_details_id)
	{
		return $this->employeeDetailMapper->getEmployeeDetails($employee_details_id);
	}

	public function getEmpJobProfile($employee_details_id)
	{
		return $this->employeeDetailMapper->getEmpJobProfile($employee_details_id);
	}

	public function getEmpPositionTitleDetail($employee_details_id)
	{
		return $this->employeeDetailMapper->getEmpPositionTitleDetail($employee_details_id);
	}


	public function getEmpPositionLevelDetail($employee_details_id)
	{
		return $this->employeeDetailMapper->getEmpPositionLevelDetail($employee_details_id);
	}

	public function getEmpJobProfileDetails($id)
	{
		return $this->employeeDetailMapper->getEmpJobProfileDetails($id);
	}


	public function getDepartmentEmployeeList($empName, $empId, $department, $organisation_id)
	{
		return $this->employeeDetailMapper->getDepartmentEmployeeList($empName, $empId, $department, $organisation_id);
	}

	public function getPersonalDetails($employee_id, $type)
	{
		return $this->employeeDetailMapper->getPersonalDetails($employee_id, $type);
	}

	public function getEmploymentDetails($employee_id)
	{
		return $this->employeeDetailMapper->getEmploymentDetails($employee_id);
	}

	public function getDepartmentDetails($employee_id)
	{
		return $this->employeeDetailMapper->getDepartmentDetails($employee_id);
	}


	public function getStaffPositionDetails($employee_id)
	{
		return $this->employeeDetailMapper->getStaffPositionDetails($employee_id);
	}

	public function getEmployeeOnProbationList($empName, $empId, $department, $organisation_id)
	{
		return $this->employeeDetailMapper->getEmployeeOnProbationList($empName, $empId, $department, $organisation_id);
	}


	public function getEmployeePayDetails($id)
	{
		return $this->employeeDetailMapper->getEmployeePayDetails($id);
	}
        
        public function getEmployeeListByLevel($empName, $empId, $department, $organisation_id)
	{
		return $this->employeeDetailMapper->getEmployeeListByLevel($empName, $empId, $department, $organisation_id);
	}

	public function getEmployeeExtraDetail($id, $tableName, $type)
	{
		return $this->employeeDetailMapper->getEmployeeExtraDetail($id, $tableName, $type);
	}

	public function generateEmployeeId()
	{
		return $this->employeeDetailMapper->generateEmployeeId();
	}


	public function getEvidenceFileName($id, $tableName)
	{
		return $this->employeeDetailMapper->getEvidenceFileName($id, $tableName);
	}
		
	public function saveNewEmployee(NewEmployeeDetail $employeeModel, $update_by_employee_id)
	{
		return $this->employeeDetailMapper->saveNewEmployee($employeeModel, $update_by_employee_id);
	}

	public function updateNewEmpDoc(UpdateNewEmpDoc $employeeModel)
	{
		return $this->employeeDetailMapper->updateNewEmpDoc($employeeModel);
	}

	public function updateNewEmployee(NewEmployee $employeeModel, $update_by_employee_id)
	{
		return $this->employeeDetailMapper->updateNewEmployee($employeeModel, $update_by_employee_id);
	}

	public function uploadNewEmployeeOrder(NewEmployee $employeeModel, $update_by_employee_id)
	{
		return $this->employeeDetailMapper->uploadNewEmployeeOrder($employeeModel, $update_by_employee_id);
	}


	public function saveNewEmployeeDetails(NewEmployee $employeeModel)
	{
		return $this->employeeDetailMapper->saveNewEmployeeDetails($employeeModel);
	}


	public function updateEmpPayDetails(EmployeePayDetails $employeeModel)
	{
		return $this->employeeDetailMapper->updateEmpPayDetails($employeeModel);
	}


	public function updateEmployeeDetails(EmployeeDetail $employeeModel, $dzongkhag, $gewog, $village)
	{
		return $this->employeeDetailMapper->updateEmployeeDetails($employeeModel, $dzongkhag, $gewog, $village);
	}

	public function updateEmployeePersonalDetails(EmployeeDetail $employeeModel, $previous_emp_id)
	{
		return $this->employeeDetailMapper->updateEmployeePersonalDetails($employeeModel, $previous_emp_id);
	}
	
	public function saveNewEmployeeRelation($employee_details_id, $data)
	{
		return $this->employeeDetailMapper->saveNewEmployeeRelation($employee_details_id, $data);
	}
	
	public function saveNewEmployeeEducation($employee_details_id, $data)
	{
		return $this->employeeDetailMapper->saveNewEmployeeEducation($employee_details_id, $data);
	}
	
	public function saveNewEmployeeTraining($employee_details_id, $data)
	{
		return $this->employeeDetailMapper->saveNewEmployeeTraining($employee_details_id, $data);
	}
	
	public function saveNewEmployeeEmployment($employee_details_id, $data)
	{
		return $this->employeeDetailMapper->saveNewEmployeeEmployment($employee_details_id, $data);
	}
	
	public function saveNewEmployeeResearch($employee_details_id, $data)
	{
		return $this->employeeDetailMapper->saveNewEmployeeResearch($employee_details_id, $data);
	}
	
	public function saveNewEmployeeDocuments(NewEmployeeDocuments $employeeModel)
	{
		return $this->employeeDetailMapper->saveNewEmployeeDocuments($employeeModel);
	}
	
	public function saveEmployeeTitle(EmployeeTitle $employeeModel)
	{
		return $this->employeeDetailMapper->saveEmployeeTitle($employeeModel);
	}
	
	public function saveEmployeeLevel(EmployeeLevel $employeeModel)
	{
		return $this->employeeDetailMapper->saveEmployeeLevel($employeeModel);
	}
	
	public function saveEmployeeProfilePicture(EmployeeProfilePicture $employeeModel)
	{
		return $this->employeeDetailMapper->saveEmployeeProfilePicture($employeeModel);
	}
        
        public function saveEmployeePersonalDetails(EmployeePersonalDetails $employeeModel)
        {
                return $this->employeeDetailMapper->saveEmployeePersonalDetails($employeeModel);
        }
        
        public function saveEmployeePermanentAddress(EmployeePermanentAddress $employeeModel)
        {
                return $this->employeeDetailMapper->saveEmployeePermanentAddress($employeeModel);
        }
	
	public function saveEmployeeRelation(EmployeeRelationDetail $employeeModel)
	{
		return $this->employeeDetailMapper->saveEmployeeRelation($employeeModel);
	}


	public function saveEmpJobProfile($data)
	{
		return $this->employeeDetailMapper->saveEmpJobProfile($data);
	}
	

	public function deleteEmployeeRelationDetail($id)
	{
		return $this->employeeDetailMapper->deleteEmployeeRelationDetail($id);
	}

	public function deleteEmployeeWorkExperience($id)
	{
		return $this->employeeDetailMapper->deleteEmployeeWorkExperience($id);
	}


	public function deleteEmployeeEducation($id)
	{
		return $this->employeeDetailMapper->deleteEmployeeEducation($id);
	}

	public function deleteEmployeeTrainingDetail($id)
	{
		return $this->employeeDetailMapper->deleteEmployeeTrainingDetail($id);
	}

	public function deleteEmployeePublication($id)
	{
		return $this->employeeDetailMapper->deleteEmployeePublication($id);
	}


	public function deleteEmployeeResponsibility($id)
	{
		return $this->employeeDetailMapper->deleteEmployeeResponsibility($id);
	}

	public function deleteEmployeeContribution($id)
	{
		return $this->employeeDetailMapper->deleteEmployeeContribution($id);
	}
	

	public function deleteEmployeeAward($id)
	{
		return $this->employeeDetailMapper->deleteEmployeeAward($id);
	}


	public function deleteEmployeeCommunityService($id)
	{
		return $this->employeeDetailMapper->deleteEmployeeCommunityService($id);
	}


	public function saveEmployeeWorkExperience(EmployeeWorkExperience $employeeModel)
	{
		return $this->employeeDetailMapper->saveEmployeeWorkExperience($employeeModel);
	}


	public function updateEmployeeWorkExperience(EmployeeWorkExperience $employeeModel)
	{
		return $this->employeeDetailMapper->updateEmployeeWorkExperience($employeeModel);
	}

	public function saveRubEmployeeWorkExperience(EmployeeWorkExperience $employeeModel, $occupationalGroup, $positionLevel, $positionTitle, $positionCategory)
	{
		return $this->employeeDetailMapper->saveRubEmployeeWorkExperience($employeeModel, $occupationalGroup, $positionLevel, $positionTitle, $positionCategory);
	}
	
	public function saveEmployeeEducation(EmployeeEducation $employeeModel)
	{
		return $this->employeeDetailMapper->saveEmployeeEducation($employeeModel);
	}
	
	public function saveEmployeeTraining(EmployeeTrainings $employeeModel)
	{
		return $this->employeeDetailMapper->saveEmployeeTraining($employeeModel);
	}
	
	public function saveEmployeePublication(EmployeePublications $employeeModel)
	{
		return $this->employeeDetailMapper->saveEmployeePublication($employeeModel);
	}
	
	public function saveEmployeeAward(EmployeeAward $employeeModel)
	{
		return $this->employeeDetailMapper->saveEmployeeAward($employeeModel);
	}
	
	public function saveEmployeeContribution(EmployeeContribution $employeeModel)
	{
		return $this->employeeDetailMapper->saveEmployeeContribution($employeeModel);
	}
	
	public function saveEmployeeResponsibility(EmployeeResponsibilities $employeeModel)
	{
		return $this->employeeDetailMapper->saveEmployeeResponsibility($employeeModel);
	}
	
	public function saveEmployeeCommunityService(EmployeeCommunityService $employeeModel)
	{
		return $this->employeeDetailMapper->saveEmployeeCommunityService($employeeModel);
	}


	public function updateEmployeeDepartment($data)
	{
		return $this->employeeDetailMapper->updateEmployeeDepartment($data);
	}

	public function updateEditEmpDepartment($data)
	{
		return $this->employeeDetailMapper->updateEditEmpDepartment($data);
	}


	public function updateEditedPositionTitleLevel($data)
	{
		return $this->employeeDetailMapper->updateEditedPositionTitleLevel($data);
	}
        
        public function saveEmployeeDiscipline(EmployeeDisciplineRecord $employeeModel)
	{
		return $this->employeeDetailMapper->saveEmployeeDiscipline($employeeModel);
	}


	public function updateEmployeeOnProbation(EmployeeOnProbation $employeeModel)
	{
		return $this->employeeDetailMapper->updateEmployeeOnProbation($employeeModel);
	}
	
	public function findEmployeeTitleDetails($id)
	{
		return $this->employeeDetailMapper->findEmployeeTitleDetails($id);
	}
	
	public function findEmployeeLevelDetails($id)
	{
		return $this->employeeDetailMapper->findEmployeeLevelDetails($id);
	}
		
	public function findEmployeeExtraDetails($tableName, $id)
	{
		return $this->employeeDetailMapper->findEmployeeExtraDetails($tableName, $id);
	}

	public function findEmployeeRUBExtraDetails($tableName, $id)
	{
		return $this->employeeDetailMapper->findEmployeeRUBExtraDetails($tableName, $id);
	}


	public function findEmployeeNonRUBExtraDetails($tableName, $id)
	{
		return $this->employeeDetailMapper->findEmployeeNonRUBExtraDetails($tableName, $id);
	}
	
	public function getHrReport($report_type)
	{
		return $this->employeeDetailMapper->getHrReport($report_type);
	}

	public function listSelectCategoryData($tableName, $columnName, $organisation_id)
	{
		return $this->employeeDetailMapper->listSelectCategoryData($tableName, $columnName, $organisation_id);
	}
	
	public function listSelectData($tableName, $columnName)
	{
		return $this->employeeDetailMapper->listSelectData($tableName, $columnName);
	}

	public function rollBackNewEmpId($id)
        {
                return $this->employeeDetailMapper->rollBackEmployeeId($id);
        }	
}

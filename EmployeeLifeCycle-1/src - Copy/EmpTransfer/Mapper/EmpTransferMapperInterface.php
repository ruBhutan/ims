<?php

namespace EmpTransfer\Mapper;

use EmpTransfer\Model\EmpTransfer;
use EmpTransfer\Model\OvcTransferApproval;
use EmpTransfer\Model\JoiningReport;

interface EmpTransferMapperInterface
{
	/**
	* @param int/String $id
	* @return array Employee Details
	* @throws \InvalidArgumentException
	*/
	
	public function getEmployeeDetailsId($id);
	
	/*
	 * Get the Organisation Id
	 */
	 
	public function getOrganisationId($username);
	
	/*
	* take username and returns Name and any other detail required
	*/
	
	public function getUserDetails($username, $tableName);
	  
	public function getUserImage($username, $usertype);

	public function crossCheckEmpTransfer($employee_details_id, $fromStatus, $toStatus);

	public function getEmployeeSpouseDetails($relationType, $employee_details_id);

	public function getTransferedEmpSpouseDetails($relationType, $id);

	public function getEmpTransferFileName($application_id, $column_name);
	
	/*
	* the details of employees that have applied for transfer
	*/
	
	public function getTransferEmployee();
	
	/**
	 * 
	 * @return array/ EmpTransfer[]
	 */
	 
	public function findAll($tableName);
        
	/**
	 * 
	 * @param type $id
	 * 
	 * to find details related to the Staff Requesting for transfer
	 */
	
	public function findTransferDetails($id);
	
	/**
	 * 
	 * @param type $EmpTransferInterface
	 * 
	 * to save budgetings
	 */
	
	public function save(EmpTransfer $EmpTransferInterface);
	
	/*
	 * Transfer Applicant Details
	 * These are the details after the transfer has been approved
	 */
	 
	 public function saveTransferApplicantDetails($data);
	
	/*
	 * to get the transfer TO/FROM based on organisations
	 * for e.g. HRO should only see applicants that have applied TO/FROM respective organisations
	 */
	 
	 public function getTransferList($type, $organisation_id);

	 public function getTransferApprovalList($type, $organisation_id, $userrole);
	 
	/*
	* Get Personal Details
	*/
	
	public function getPersonalDetails($employee_id);
	
	/*
	* Get Employment Details such as Position Title, Position Level etc. of the employee
	*/
	
	public function getEmploymentDetails($employee_id);
	
	/*
	* Get the details of the applicant for transfer
	* Used when updating the details of the applicant
	* Takes the id of the transfer details
	*/
	
	public function getTransferApplicantDetail($id);
	
	/*
	* Update the status of the transfer FROM/ TO organisation
	*/
	
	public function updateTransferStatus($id, $status, $type);

	public function getTransferRequestAgency($id);
	
	/*
	* Transfer Approval from OVC
	*/
	
	public function saveOvcTransferApproval(OvcTransferApproval $transferObject);
	
	/*
	* Save the joining report after the transfer has been approved by OVC
	*/
	
	public function saveJoiningReport(JoiningReport $reportObject);
	
	/*
	* Get the notification details, i.e. submission to and submission to department
	*/
	
	public function getNotificationDetails($organisation_id);
		
	/**
	 * 
	 * @return array/ EmpTransfer[]
	 */
	 
	public function listSelectData($tableName, $columnName);
	
}
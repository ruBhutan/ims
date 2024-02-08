<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace EmployeeLeave\Controller;

use EmployeeLeave\Service\EmployeeLeaveServiceInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Helper\AbstractHelper;
use EmployeeLeave\Form\EmployeeLeaveForm;
use EmployeeLeave\Form\OfficiatingSupervisorForm;
use EmployeeLeave\Form\EmployeeLeaveDetailsForm;
use EmployeeLeave\Form\OnbehalfEmployeeLeaveForm;
use EmployeeLeave\Form\CancelledLeaveForm;
use EmployeeLeave\Model\EmployeeLeave;
use EmployeeLeave\Model\OfficiatingSupervisor;
use EmployeeLeave\Model\OnbehalfEmployeeLeave;
use EmployeeLeave\Model\CancelledLeave;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
use Zend\Paginator\Adapter\DbSelect;
use Zend\View\Model\JsonModel;
use Zend\Form\Element\Select;

class EmployeeLeaveController extends AbstractActionController
{
	protected $leaveService;
	protected $notificationService;
	protected $auditTrailService;
	protected $serviceLocator;
	protected $emailService;
	protected $username;
	protected $userrole;
	protected $usertype;
	protected $userregion;
	protected $userDetails;
	protected $userImage;
	protected $employee_details_id;
	protected $organisation_id;
	protected $departments_id;
	protected $departments_units_id;
	protected $role;
	protected $keyphrase = "RUB_IMS";

	public function __construct(EmployeeLeaveServiceInterface $leaveService, NotificationServiceInterface $notificationService, AuditTrailServiceInterface $auditTrailService, $serviceLocator)
	{
		$this->leaveService = $leaveService;
		$this->notificationService = $notificationService;
		$this->auditTrailService = $auditTrailService;
		$this->emailService = $serviceLocator->get('Application\Service\EmailService');

		/*
		 * To retrieve the user name from the session
		*/
		$authPlugin = $serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
		$this->username = $authPlugin['username'];
		$this->userrole = $authPlugin['role'];
		$this->userregion = $authPlugin['region'];
		$this->usertype = $authPlugin['user_type_id'];

		/*
		* Getting the employee_details_id related to username
		*/

		$empData = $this->leaveService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach ($empData as $emp) {
			$this->employee_details_id = $emp['id'];
			$this->departments_units_id = $emp['departments_units_id'];
			$this->departments_id = $emp['departments_id'];
		}

		//get the organisation id
		$organisationID = $this->leaveService->getOrganisationId($this->username);
		foreach ($organisationID as $organisation) {
			$this->organisation_id = $organisation['organisation_id'];
		}

		//get the user details such as name
		$this->userDetails = $this->leaveService->getUserDetails($this->username, $this->usertype);
		$this->userImage = $this->leaveService->getUserImage($this->username, $this->usertype);
	}

	public function loginDetails()
	{
		$this->layout()->setVariable('userRole', $this->userrole);
		$this->layout()->setVariable('userRegion', $this->userregion);
		$this->layout()->setVariable('userType', $this->usertype);
		$this->layout()->setVariable('userDetails', $this->userDetails);
		$this->layout()->setVariable('userImage', $this->userImage);
	}

	public function leaveStatusAction()
	{
		$this->loginDetails();
		$leaveCategory = $this->leaveService->listAll($tableName = 'emp_leave_category');
		$employeeOccupationalGroup = $this->leaveService->getEmployeeOccupationalGroup($this->employee_details_id);

		//going to use it to iterate over the types of leave and get the leave taken
		$totalLeaveTaken = array();
		$leave_category_list = $this->leaveService->listSelectData('emp_leave_category', 'leave_category');
		foreach ($leave_category_list as $key => $value) {
			$leaveTaken = $this->leaveService->getLeaveTaken($this->employee_details_id, $value);
			if ($value == "Casual Leave") {
				foreach ($leaveTaken as $total) {
					$totalLeaveTaken[$key] = $total['casual_leave'];
				}
			} else if ($value == "Earned Leave") {
				foreach ($leaveTaken as $total) {
					$totalLeaveTaken[$key] = $total['earned_leave'];
				}
			} else if ($value == "Annual Leave") {
				foreach ($leaveTaken as $total) {
					$totalLeaveTaken[$key] = $total['annual_leave'];
				}
			} else {
				foreach ($leaveTaken as $total) {
					if (array_key_exists($key, $totalLeaveTaken))
						$totalLeaveTaken[$total['emp_leave_category_id']] += $total['days_of_leave'];
					else
						$totalLeaveTaken[$total['emp_leave_category_id']] = $total['days_of_leave'];
				}
			}
			//var_dump($totalLeaveTaken); die();
		}
		/*$eolTaken = $this->leaveService->getLeaveTaken($this->employee_details_id, $type = "EOL");
		$casualLeaveTaken = $this->leaveService->getLeaveTaken($this->employee_details_id, $type = "Casual Leave");
		$earnedLeaveTaken = $this->leaveService->getLeaveTaken($this->employee_details_id, $type = "Earned Leave");
		$studyLeaveTaken = $this->leaveService->getLeaveTaken($this->employee_details_id, $type = "Study Leave");
		$maternityLeaveTaken = $this->leaveService->getLeaveTaken($this->employee_details_id, $type = "Maternity Leave");
                $escortLeaveTaken = $this->leaveService->getLeaveTaken($this->employee_details_id, $type = "Escort Leave"); */
		$leaveBalance = $this->leaveService->getLeaveBalance($this->employee_details_id);
		//var_dump($leaveBalance); die();
		$message = NULL;

		return array(
			'leaveCategory' => $leaveCategory,
			'totalLeaveTaken' => $totalLeaveTaken,
			'leaveBalance' => $leaveBalance,
			'employeeOccupationalGroup' => $employeeOccupationalGroup,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			'test' => 'Boring leave'
		);
	}

	public function empApplyLeaveAction()
	{
		$this->loginDetails();

		//get the leave category id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$leaveCategoryId = $this->my_decrypt($id_from_route, $this->keyphrase);

		if (is_numeric($leaveCategoryId)) {
			$employeeDetails = $this->leaveService->findEmployeeId($this->username);
			$employeeId = $employeeDetails->getId();

			$form = new EmployeeLeaveForm();
			$leaveModel = new EmployeeLeave();
			$form->bind($leaveModel);

			$leaveType = $this->leaveService->findLeaveType($leaveCategoryId);
			$employeeList = $this->leaveService->getEmployeeList($this->organisation_id);
			unset($employeeList[$this->employee_details_id]);

			//Get the details for notification, i.e. Submission to, Submission Dept etc.
			$notificationDetails = $this->leaveService->getNotificationDetails($leaveCategoryId, $this->userrole, $this->departments_units_id);

			$message = NULL;

			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				$data = array_merge_recursive(
					$request->getPost()->toArray(),
					$request->getFiles()->toArray()
				);
				$form->setData($data);
				if ($form->isValid()) {
					$data = $this->params()->fromPost();
					$from_date = date("Y-m-d", strtotime(substr($data['employeeleave']['from_date'], 0, 10)));
					$to_date = date("Y-m-d", strtotime(substr($data['employeeleave']['to_date'], 0, 10)));

					$leaveBalance = $this->leaveService->getLeaveBalance($this->employee_details_id);
					$earned_leave_balance = $leaveBalance['earned_leave'];
					$casual_leave_balance = $leaveBalance['casual_leave'];
					$annual_leave_balance = $leaveBalance['annual_leave'];
					$data1 = $this->getRequest()->getPost('employeeleave');
					$emp_leave_category_id = $data1['emp_leave_category_id'];
					$days_of_leave = $data1['days_of_leave'];
					$leave_category = $this->leaveService->getLeaveCategory($emp_leave_category_id);
					$total_leave_balance = NULL;
					$pending_leave_days = $this->leaveService->getStaffAppliedLeave($this->employee_details_id, $emp_leave_category_id, 'Pending');

					$appliedLeave = $this->leaveService->crossCheckAppliedLeave($this->employee_details_id);
					$from_date1 = $appliedLeave['from_date'];
					$to_date1 = $appliedLeave['to_date'];
					$leave_status = $appliedLeave['leave_status'];

					if ($leave_category == 'Casual Leave') {
						$total_leave_balance = $casual_leave_balance;
					} else if ($leave_category == 'Earned Leave') {
						$total_leave_balance = $earned_leave_balance;
					} else if ($leave_category == 'Annual Leave') {
						$total_leave_balance = $annual_leave_balance;
					} else {
						$total_leave_balance = '3000';
					}

					if ($from_date < date('Y-m-d')) {
						$message = 'Failure';
						$this->flashMessenger()->addMessage("Sorry you can't apply for the leave since from date is less than current date");
					} else if ($from_date > $to_date) {
						$message = 'Failure';
						$this->flashMessenger()->addMessage("Sorry you can't apply for the leave since from date is greater than to date");
					} else if (($pending_leave_days + $days_of_leave) > $total_leave_balance) {
						$message = 'Failure';
						$this->flashMessenger()->addMessage("Sorry you can't apply for the leave since you still have pending leave of " . $pending_leave_days . " days that is not approved or rejected. Please inform the supervisor to take some action or the number of days you are applying for leave exceeded the total leave balance.");
					} else if ($days_of_leave > $total_leave_balance) {
						$message = 'Failure';
						$this->flashMessenger()->addMessage("Sorry you can't apply for the leave since the number of days you are applying exceed the total leave balance");
					} else if ($from_date == $from_date1 && $to_date == $to_date1) {
						$message = 'Failure';
						$this->flashMessenger()->addMessage("Sorry you can't apply for the leave since you have " . $leave_status . " leave within the requested leave duration");
					} else {
						try {
							$this->leaveService->save($leaveModel);
							$this->sendLeaveApplicationEmail($this->employee_details_id, $this->departments_id, $this->departments_units_id, $this->userrole, $emp_leave_category_id);
							$this->flashMessenger()->addMessage('Successfully applied for leave');
							$this->notificationService->saveNotification('Leave Application', $notificationDetails['submission_to'], $notificationDetails['submission_to_dept'], 'Leave Application');
							$this->auditTrailService->saveAuditTrail("INSERT", "Leave Application", "ALL", "SUCCESS");
							return $this->redirect()->toRoute('leavestatus');
						} catch (\Exception $e) {
							$message = 'Failure';
							$this->flashMessenger()->addMessage($e->getMessage());
							// Some DB Error happened, log it and let the user know
						}
					}
				}
			}

			return array(
				'form' => $form,
				'emp_leave_category_id' => $leaveCategoryId,
				'leaveType' => $leaveType,
				'employee_details_id' => $employeeId,
				'employeeList' => $employeeList,
				'message' => $message,
			);
		} else {
			return $this->redirect()->toRoute('leavestatus');
		}
	}


	/*
    *Function to apply on behalf leave
    **/
	public function applyOnBehalfLeaveAction()
	{
		$this->loginDetails();

		$form = new OnbehalfEmployeeLeaveForm();
		$leaveModel = new OnbehalfEmployeeLeave();
		$form->bind($leaveModel);

		$leaveType = $this->leaveService->listSelectData('emp_leave_category', 'leave_category');
		$employeeList = $this->leaveService->getEmployeeList($this->organisation_id);

		//Get the details for notification, i.e. Submission to, Submission Dept etc.
		//$notificationDetails = $this->leaveService->getNotificationDetails($leaveCategoryId, $this->userrole, $this->departments_units_id);

		$message = NULL;

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			$data = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);
			$form->setData($data);
			if ($form->isValid()) {
				$data = $this->getRequest()->getPost('onbehalfemployeeleave');

				$from_date = date("Y-m-d", strtotime(substr($data['from_date'], 0, 10)));
				$to_date = date("Y-m-d", strtotime(substr($data['to_date'], 0, 10)));
				//echo $from_date; echo '<br>'; echo $to_date; die();
				$employee_details_id = $data['employee_details_id'];
				$emp_leave_category_id = $data['emp_leave_category_id'];
				$days_of_leave = $data['days_of_leave'];
				$leaveBalance = $this->leaveService->getLeaveBalance($employee_details_id);
				$earned_leave_balance = $leaveBalance['earned_leave'];
				$casual_leave_balance = $leaveBalance['casual_leave'];
				$annual_leave_balance = $leaveBALANCE['annual_leave'];
				$leave_category = $this->leaveService->getLeaveCategory($emp_leave_category_id);

				$appliedLeave = $this->leaveService->crossCheckAppliedLeave($employee_details_id);
				$from_date1 = $appliedLeave['from_date'];
				$to_date1 = $appliedLeave['to_date'];
				$leave_status = $appliedLeave['leave_status'];

				$departments_id = NULL;
				$departments_units_id = NULL;
				$userrole = NULL;
				$onbehalf_staff_details = $this->leaveService->getOnBehalfStaffDetails($employee_details_id);
				foreach ($onbehalf_staff_details as $temp) {
					$departments_id = $temp['departments_id'];
					$departments_units_id = $temp['departments_units_id'];
					$userrole = $temp['role'];
				}

				$total_leave_balance = NULL;
				if ($leave_category == 'Casual Leave') {
					$total_leave_balance = $casual_leave_balance;
				} else if ($leave_category == 'Earned Leave') {
					$total_leave_balance = $earned_leave_balance;
				} else {
					$total_leave_balance = '3000';
				}

				if ($days_of_leave > $total_leave_balance) {
					$message = 'Failure';
					$this->flashMessenger()->addMessage("Sorry you can't apply for the leave since the number of days you are applying exceed the total leave balance");
				} else if ($from_date == $from_date1 && $to_date == $to_date1) {
					$message = 'Failure';
					$this->flashMessenger()->addMessage("Sorry you can't apply for on behalf leave since this particular staff have " . $leave_status . " leave within the requested leave duration");
				} else {
					try {
						$this->leaveService->saveOnBehalfLeave($leaveModel);

						//Mailing Commented by Tashi, ICTß
						$this->sendLeaveApplicationEmail($employee_details_id, $departments_id, $departments_units_id, $userrole, $emp_leave_category_id);
						$this->flashMessenger()->addMessage('Successfully applied on behalf leave');
						$this->notificationService->saveNotification('Leave Application', 'submission_to', 'submission_to_dept', 'Leave Application');
						$this->auditTrailService->saveAuditTrail("INSERT", "Leave Application", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('applyonbehalfleave');
					} catch (\Exception $e) {
						$message = 'Failure';
						$this->flashMessenger()->addMessage($e->getMessage());
						// Some DB Error happened, log it and let the user know
					}
				}
			}
		}

		return array(
			'form' => $form,
			//'emp_leave_category_id' => $leaveCategoryId,
			'leaveType' => $leaveType,
			//'employee_details_id' => $employeeId,
			'employeeList' => $employeeList,
			'applied_by_id' => $this->employee_details_id,
			'message' => $message,
		);
	}


	//Function to send leave application  to the particular applicant supervisor
	public function sendLeaveApplicationEmail($employee_details_id, $departments_id, $departments_units_id, $userrole, $emp_leave_category_id)
	{
		$this->loginDetails();

		$supervisor_email = $this->leaveService->getSupervisorEmailId($userrole, $departments_units_id, $emp_leave_category_id);

		$applicant_name = NULL;
		$applicant = $this->leaveService->getLeaveApplicant($employee_details_id, 'Leave Application');
		foreach ($applicant as $temp) {
			$applicant_name = $temp['first_name'] . ' ' . $temp['middle_name'] . ' ' . $temp['last_name'];
		}

		foreach ($supervisor_email as $email) {
			$toEmail = $email;
			$messageTitle = "New Leave Application";
			$messageBody = "Dear Sir/Madam,<br><h3>" . $applicant_name . " has applied for leave on " . date('Y-m-d') . ".</h3><br><b>Please click the link below for necessary action.</b><br><u>http://ims.rub.edu.bt</u><p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

			$this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
		}
	}



	public function empLeaveApprovalAction()
	{
		$this->loginDetails();

		$leaveCategory = $this->leaveService->listLeaveCategory();

		$message = NULL;

		return new ViewModel(array(
			'keyphrase' => $this->keyphrase,
			'pendingList' => $this->leaveService->listAllLeave($status = 'Pending', $this->employee_details_id, $userrole = $this->userrole, $this->organisation_id, $this->departments_id),
			'approvedList' => $this->leaveService->listAllLeave($status = 'Approved', $this->employee_details_id, $userrole = $this->userrole, $this->organisation_id, $this->departments_id),
			'rejectList' => $this->leaveService->listAllLeave($status = 'Rejected', $this->employee_details_id, $userrole = $this->userrole, $this->organisation_id, $this->departments_id),
			'leaveCategory' => $leaveCategory,
			'message' => $message
		));
	}

	public function empLeaveStatusAction()
	{
		$this->loginDetails();

		//get the id of the leave
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		if (is_numeric($id)) {
			$leave = $this->leaveService->findLeave($id);

			//Need to get the employee details id/username of leave application
			$leave_applicant = NULL;
			$leaveDetails = $this->leaveService->findLeave($id);
			foreach ($leaveDetails as $temp) {
				$leave_applicant = $temp['employee_details_id'];
			}

			//need to get an array of employees that have applied for list
			//associate the array with employee details
			//need to get the date as we do not need old leaves
			$empIdArray = array();
			$empIdArray = array_push($empIdArray, $id);

			$employees = $this->leaveService->findEmployeeDetails($leave_applicant);
			$leaveBalance = $this->leaveService->getLeaveBalance($leave_applicant);

			$leaveCategory = $this->leaveService->listLeaveCategory();

			$form = new EmployeeLeaveForm();
			$leaveModel = new EmployeeLeave();
			$form->bind($leaveModel);

			$request = $this->getRequest();
			if ($request->isPost()) {
				//the following set of code is to get the value from the submit buttons
				$postData = $this->getRequest()->getPost();
				$form->setData($request->getPost());
				$remarks = $postData['employeeleave']['remarks'];
				foreach ($postData as $key => $value) {
					if ($key == 'employeeleave') {
						$leaveData = $value;
						if (array_key_exists('approve', $leaveData))
							$leaveStatus = 'Approved';
						else if (array_key_exists('reject', $leaveData))
							$leaveStatus = 'Rejected';
					}
				}

				//We do not check for valid form as we are not getting any values
				// just updating the leave status
				if ($leaveStatus) {
					try {
						$this->leaveService->updateLeave($id, $leaveStatus, $remarks, $this->employee_details_id);
						$this->flashMessenger()->addMessage('Leave was ' . $leaveStatus);
						$this->notificationService->saveNotification('Leave Application', $leave_applicant, NULL, "Leave Status $leaveStatus");
						$this->auditTrailService->saveAuditTrail("UPDATE", "Updating Leave Status", "ALL", "SUCCESS");

						//$this->sendApprovedLeaveEmail($id, $leaveStatus);

						//Send message to college president
						if ($this->organisation_id != '1' && $leaveStatus == 'Approved') {
							$this->sendLeaveEmailToPresident($id, $this->organisation_id);
						}

						return $this->redirect()->toRoute('empleaveapproval');
					} catch (\Exception $e) {
						$message = 'Failure';
						$this->flashMessenger()->addMessage($e->getMessage());
						// Some DB Error happened, log it and let the user know
					}
				}
			}

			return array(
				'form' => $form,
				'leave' => $leave,
				'employees' => $employees,
				'leaveCategory' => $leaveCategory,
				'leaveBalance' => $leaveBalance
			);
		} else {
			return $this->redirect()->toRoute('empleaveapproval');
		}
	}


	//Function to send email to the applicant and substitution when approved
	public function sendApprovedLeaveEmail($id, $leaveStatus)
	{
		$this->loginDetails();

		if ($leaveStatus == "Approved") {
			$applicant_name = NULL;
			$applicant_email = NULL;
			$applicantDetails = $this->leaveService->getApprovedLeaveApplicantDetails($id);
			foreach ($applicantDetails as $temp) {
				$applicant_name = $temp['first_name'] . ' ' . $temp['middle_name'] . ' ' . $temp['last_name'];
				$applicant_email = $temp['email'];
			}

			$toEmail = $applicant_email;
			$messageTitle = "Approved Leave Application";
			$messageBody = "Dear " . $applicant_name . ",<br> Your leave application has been approved on " . date('Y-m-d') . ".<p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

			$this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);

			$this->sendSubstitutionLeaveEmail($id, $applicant_name);
		} else {
			return;
		}
	}


	public function sendLeaveEmailToPresident($id, $organisation_id)
	{
		$this->loginDetails();

		$approving_authority = NULL;
		$applicant_name = NULL;
		$from_date = NULL;
		$to_date = NULL;
		$president_det = array();
		$applicantDetails = $this->leaveService->getApprovedLeaveApplicantDetails($id);
		foreach ($applicantDetails as $temp) {
			$applicant_name = $temp['first_name'] . ' ' . $temp['middle_name'] . ' ' . $temp['last_name'];
			$approving_authority =  $temp['afirst_name'] . ' ' . $temp['amiddle_name'] . ' ' . $temp['alast_name'];
			$from_date = $temp['from_date'];
			$to_date = $temp['to_date'];
		}

		$president_detail = $this->leaveService->getPresidentDetails($organisation_id);
		foreach ($president_detail as $det) {
			$president_det = $det;
		}

		$toEmail = $president_det['email'];
		//$president_name = $president_det['first_name'].' '.$president_det['middle_name'].' '.$president_det['last_name'];
		$messageTitle = "Approved Leave Application";
		//$messageBody = "Dear ".$president_name.",<br>".$applicant_name." leave has been approved on ".date('Y-m-d')." from ".$from_date." to ".$to_date.".<p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";
		$messageBody = "Dear Sir/Madam,<br>" . $applicant_name . " leave has been approved on " . date('Y-m-d') . " from " . $from_date . " to " . $to_date . " by " . $approving_authority . ".<br><br>Thanking you for your continued support.<br>RUB-IMS<p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

		$this->emailService->sendMailer($toEmail, $messageTitle, $messageBody);
	}



	public function sendSubstitutionLeaveEmail($id, $applicant_name)
	{
		$this->loginDetails();

		$substitution_name = NULL;
		$substitution_email = NULL;
		$from_date = NULL;
		$to_date = NULL;
		$substitutionDetails = $this->leaveService->getApprovedLeaveSubstitution($id);

		if (!empty($substitutionDetails)) {
			foreach ($substitutionDetails as $details) {
				$substitution_name = $details['sub_first_name'] . ' ' . $details['sub_middle_name'] . ' ' . $details['sub_last_name'];
				$substitution_email = $details['sub_email'];
				$from_date = $details['from_date'];
				$to_date = $details['to_date'];
			}

			$emailTo = $substitution_email;
			$messageToTitle = "Leave Applicant Subsitution";
			$messageToBody = "Dear " . $substitution_name . ",<br> You have been appointed as substitution of " . $applicant_name . "  from " . $from_date . " to " . $to_date . ".<p>This is an auto-generated email and Please do not reply because this mail id is not monitored</p>";

			$this->emailService->sendMailer($emailTo, $messageToTitle, $messageToBody);
		} else {
			return;
		}
	}


	//Function to list an approved leave list for the cancel of leave or adjustment of leave balance
	public function empApprovedLeaveListAction()
	{
		$this->loginDetails();

		$approvedLeaveList = $this->leaveService->getEmpApprovedLeaveList($this->organisation_id);

		$message = NULL;

		return array(
			'approvedLeaveList' => $approvedLeaveList,
			'keyphrase' => $this->keyphrase,
			'message' => $message,
		);
	}


	//Function to update staff approved leave
	public function updateEmpApprovedLeaveAction()
	{
		$this->loginDetails();

		//get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		if (is_numeric($id)) {
			$leave = $this->leaveService->findLeave($id);

			//Need to get the employee details id/username of leave application
			$leave_applicant = NULL;
			$approved_leave_no = NULL;
			$leaveDetails = $this->leaveService->findLeave($id);
			foreach ($leaveDetails as $temp) {
				$leave_applicant = $temp['employee_details_id'];
				$approved_leave_no = $temp['days_of_leave'];
			}

			$message = NULL;

			$form = new CancelledLeaveForm();
			$leaveModel = new CancelledLeave();
			$form->bind($leaveModel);

			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {
					$data = $this->getRequest()->getPost('cancelledleave');
					$no_of_days = $data['no_of_days'];
					$emp_leave_id = $data['emp_leave_id'];
					$check_cancelled_leave = $this->leaveService->crossCheckCancelledLeave($emp_leave_id);

					if ($check_cancelled_leave) {
						$message = 'Failure';
						$this->flashMessenger()->addMessage("Sorry you have already cancelled this particular leave application");
					} else if ($no_of_days > $approved_leave_no) {
						$message = 'Failure';
						$this->flashMessenger()->addMessage("Sorry you can't cancel the leave since the no of days you are going to cancel exceed the total approved leave");
					} else {
						try {
							$this->leaveService->updateEmpApprovedLeave($leaveModel);
							$this->flashMessenger()->addMessage("Leave was successfully cancelled");
							$this->notificationService->saveNotification('Employee Leave Cancel', NULL, NULL, "Employee Leave");
							$this->auditTrailService->saveAuditTrail("UPDATE", "Employee Cancelled Leave", "ALL", "SUCCESS");
							return $this->redirect()->toRoute('empapprovedleavelist');
						} catch (\Exception $e) {
							$message = 'Failure';
							$this->flashMessenger()->addMessage($e->getMessage());
							// Some DB Error happened, log it and let the user know
						}
					}
				}
			}

			return array(
				'form' => $form,
				'leave' => $leave,
				'employee_details_id' => $this->employee_details_id,
				'message' => $message,
			);
		} else {
			return $this->redirect()->toRoute('empapprovedleavelist');
		}
	}

	//to assign an officiating supervisor
	public function empAssignOfficiatingSupervisorAction()
	{
		$this->loginDetails();
		$form = new OfficiatingSupervisorForm();
		$leaveModel = new OfficiatingSupervisor();
		$form->bind($leaveModel);

		$supervisorList = $this->leaveService->getEmployeeList($this->organisation_id);
		$officiatingList = $this->leaveService->getOfficiatingList($this->employee_details_id);
		$employeeList = $this->leaveService->getEmployeeList($this->organisation_id);

		$message = NULL;

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			$data = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);
			$form->setData($data);

			$data1 = $this->params()->fromPost();
			$date_range = $data1['officiatingsupervisor']['date_range'];
			$from_date = date('Y-m-d', strtotime(substr($date_range, 0, 10)));
			$to_date = date('Y-m-d', strtotime(substr($date_range, 13, 10)));

			$officiating = $data1['officiatingsupervisor']['officiating_supervisor'];
			$supervisor_id = $data1['officiatingsupervisor']['supervisor'];

			$officiating_role = $this->leaveService->getEmpOfficiatedRole($officiating, $from_date, $to_date, $this->userrole);

			$check_own_officiating = $this->leaveService->crossCheckOwnOfficiating($this->employee_details_id, $from_date);
			$role_details = array();
			foreach ($officiating_role as $details) {
				$role_details['officiating_supervisor'] = $details['officiating_supervisor'];
				$role_details['from_date'] = $details['from_date'];
				$role_details['to_date'] = $details['to_date'];
				$role_details['supervisor'] = $details['supervisor'];
			}
			// var_dump($role_details); die();
			if (!empty($role_details)) {
				$message = 'Failure';
				$this->flashMessenger()->addMessage("Sorry you can't officiate this particular staff since this staff has been officiated by " . $role_details['supervisor'] . " from " . $role_details['from_date'] . " till " . $role_details['to_date'] . ". Please try for another staff!");
			} else if ($check_own_officiating) {
				$message = 'Failure';
				$this->flashMessenger()->addMessage("Sorry! You have already assigned your officiating within the selected date.");
			} else {
				if ($form->isValid()) {
					try {
						$this->leaveService->saveOfficiatingOfficer($leaveModel, $supervisor_id, $this->employee_details_id, $this->userrole);
						$this->flashMessenger()->addMessage('Officiating Officer was successfully added');
						$this->notificationService->saveNotification('Officating Appointment', $officiating, NULL, 'Officating Appointment');
						$this->auditTrailService->saveAuditTrail("INSERT", "Appointment of Officiating Supervisor", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('empassignofficiatingsupervisor');
					} catch (\Exception $e) {
						$message = 'Failure';
						$this->flashMessenger()->addMessage($e->getMessage());
						// Some DB Error happened, log it and let the user know
					}
				}
			}
		}

		return array(
			'form' => $form,
			'supervisorList' => $supervisorList,
			'officiatingList' => $officiatingList,
			'employeeList' => $employeeList,
			'keyphrase' => $this->keyphrase,
			'message' => $message
		);
	}


	public function downloadEmpOfficiatingFileAction()
	{
		$this->loginDetails();
		//get the student id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		if (is_numeric($id)) {
			$file = $this->leaveService->getOfficiatingFileName($id);

			$mimetype = mime_content_type($file);
			$response = new Stream();
			$response->setStream(fopen($file, 'r'));
			$response->setStatusCode(200);
			$response->setStreamName(basename($file));
			$headers = new Headers();
			$headers->addHeaderLine('Content-Type', $mimetype)
				->addHeaderLine('Content-Disposition: inline', 'attachment; filename="' . basename($file) . '"')
				->addHeaderLine('Content-Length', filesize($file))
				->addHeaderLine('Expires', '@0')
				->addHeaderLine('Pragma', 'public')
				->addHeaderLine('Content-Transfer-Encoding: binary')
				->addHeaderLine('Accept-Ranges: bytes');

			$response->setHeaders($headers);
			return $response;
		} else {
			$this->redirect()->toRoute('empassignofficiatingsupervisor');
		}
	}


	// Function the display the employee casual leave balance and earned leave balance
	public function empLeaveDetailListAction()
	{
		$this->loginDetails();

		$message = NULL;

		return array(
			'keyphrase' => $this->keyphrase,
			'message' => $message,
			'organisation_id' => $this->organisation_id,
			'empLeaveDetails' => $this->leaveService->getEmployeeLeaveDetails($this->organisation_id),
		);
	}


	// Function to edit the casual leave balance and earned leave balance
	public function editEmpLeaveDetailsAction()
	{
		$this->loginDetails();

		//get the officiating id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		if (is_numeric($id)) {
			$form = new EmployeeLeaveDetailsForm();

			$employee_leave_details = $this->leaveService->getEmpLeaveBalanceDetails($id);

			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {
					$casual_leave = $this->getRequest()->getPost('casual_leave');
					$earned_leave = $this->getRequest()->getPost('earned_leave');
					$annual_leave  = $this->getRequest()->getPost('annual_leave');
					try {
						$this->leaveService->updateEmpLeaveBalance($id, $casual_leave, $earned_leave, $annual_leave, $this->employee_details_id);
						$this->flashMessenger()->addMessage('Staff Leave Balance was successfully edited');
						$this->auditTrailService->saveAuditTrail("EDIT", "Emp Leave Balance", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('empleavedetaillist');
					} catch (\Exception $e) {
						$message = 'Failure';
						$this->flashMessenger()->addMessage($e->getMessage());
						// Some DB Error happened, log it and let the user know
					}
				}
			}

			return array(
				'id' => $id,
				'form' => $form,
				'organisation_id' => $this->organisation_id,
				'employee_leave_details' => $employee_leave_details,
				'empLeaveDetails' => $this->leaveService->getEmployeeLeaveDetails($this->organisation_id),
			);
		} else {
			return $this->redirect()->toRoute('empleavedetaillist');
		}
	}

	//Function the edit the assigned officiating supervisor
	public function editOfficiatingSupervisorAction()
	{
		$this->loginDetails();

		//get the officiating id
		$id_from_route = $this->params()->fromRoute('id', 0);
		$id = $this->my_decrypt($id_from_route, $this->keyphrase);

		if (is_numeric($id)) {
			$form = new OfficiatingSupervisorForm();
			$leaveModel = new OfficiatingSupervisor();
			$form->bind($leaveModel);

			$officiatingDetail = $this->leaveService->getOfficiatingDetails($id);
			$officiatingList = $this->leaveService->getOfficiatingList($this->employee_details_id);
			$employeeList = $this->leaveService->getEmployeeList($this->organisation_id);

			$request = $this->getRequest();
			if ($request->isPost()) {
				$form->setData($request->getPost());
				if ($form->isValid()) {
					$officiating = $leaveModel->getOfficiating_Supervisor();
					try {
						$this->leaveService->saveOfficiatingOfficer($leaveModel, $this->employee_details_id, $this->userrole);
						$this->flashMessenger()->addMessage('Officiating Officer was successfully edited');
						$this->notificationService->saveNotification('Officating Appointment', $officiating, NULL, 'Officating Appointment');
						$this->auditTrailService->saveAuditTrail("EDIT", "Appointment of Officiating Supervisor", "ALL", "SUCCESS");
						return $this->redirect()->toRoute('empassignofficiatingsupervisor');
					} catch (\Exception $e) {
						$message = 'Failure';
						$this->flashMessenger()->addMessage($e->getMessage());
						// Some DB Error happened, log it and let the user know
					}
				}
			}

			return array(
				'form' => $form,
				'officiatingList' => $officiatingList,
				'officiatingDetail' => $officiatingDetail,
				'employeeList' => $employeeList
			);
		} else {
			return $this->redirect()->toRoute('empassignofficiatingsupervisor');
		}
	}

	public function downloadLeaveApplicationAction()
	{
		//get the param from the view file
		$file_location = $this->params()->fromRoute('filename', 0);

		//extract the training id and column name
		$column_name = preg_replace('!\d+!', '', $file_location);
		$column_name = rtrim($column_name, "_");
		preg_match_all('!\d+!', $file_location, $id);
		$leave_id = implode(' ', $id[0]);

		//get the location of the file from the database		
		$fileArray = $this->leaveService->getFileName($leave_id);
		$file;
		foreach ($fileArray as $set) {
			$file = $set['evidence_file'];
		}

		$mimetype = mime_content_type($file);
		$response = new Stream();
		$response->setStream(fopen($file, 'r'));
		$response->setStatusCode(200);
		$response->setStreamName(basename($file));
		$headers = new Headers();
		$headers->addHeaderLine('Content-Disposition: inline', 'attachment; filename="' . basename($file) . '"')
			->addHeaderLine('Content-Type', $mimetype)
			->addHeaderLine('Content-Length', filesize($file))
			->addHeaderLine('Expires', '@0') // @0, because zf2 parses date as string to \DateTime() object
			->addHeaderLine('Cache-Control', 'must-revalidate')
			->addHeaderLine('Pragma', 'public')
			->addHeaderLine('Content-Transfer-Encoding: binary')
			->addHeaderLine('Accept-Ranges: bytes');

		$response->setHeaders($headers);
		return $response;
	}

	public function my_decrypt($data, $key)
	{
		// Remove the base64 encoding from our key
		$encryption_key = base64_decode($key);

		$len = strlen($data);
		if ($len % 2) {
			return "ERROR";
		} else {
			// To decrypt, split the encrypted data from our IV - our unique separator used was "::"
			list($encrypted_data, $iv) = explode('::', base64_decode(hex2bin($data)), 2);
			return openssl_decrypt($encrypted_data, 'BF-CFB', $encryption_key, 0, $iv);
		}
	}
}
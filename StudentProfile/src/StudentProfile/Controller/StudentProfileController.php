<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace StudentProfile\Controller;

use StudentProfile\Service\StudentProfileServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use StudentProfile\Form\StudentProfileForm;
use StudentProfile\Form\StudentSearchForm;
use StudentProfile\Model\StudentProfile;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

class StudentProfileController extends AbstractActionController
{
	protected $studentProfile;
	protected $username;
	protected $employee_details_id;
	protected $student_id;
	protected $organisation_id;
	
	public function __construct(StudentProfileServiceInterface $studentProfileService)
	{
		$this->studentProfileService = $studentProfileService;
		
		/*
		 * To retrieve the user name from the session
		*/
		$user_session = new Container('user');
        $this->username = $user_session->username;
		
		/*
		* Getting the student_id/employee_details_id related to username
		*/
		
		$empData = $this->studentProfileService->getUserDetailsId($this->username, $tableName = 'employee_details');
		foreach($empData as $emp){
			$this->employee_details_id = $emp['id'];
			}


		/*
		*Getting the student_id related to username
		*/
		$stdData = $this->studentProfileService->getUserDetailsId($this->username, $tableName = 'student');
		foreach ($stdData as $std) {
			$this->student_id = $std['id'];
		}
		
		//get the organisation id
		$organisationID = $this->studentProfileService->getOrganisationId($this->username);
		foreach($organisationID as $organisation){
			$this->organisation_id = $organisation['organisation_id'];
		}
	}


	/*Display student details  */
     public function studentListsAction()
    {
         //get the organisation id
        $organisationID = $this->studentProfileService->getOrganisationId($this->username);
        foreach($organisationID as $organisation){
            $organisation_id = $organisation['organisation_id'];
        }

        $form = new StudentSearchForm();

        $request = $this->getRequest();
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $stdName = $this->getRequest()->getPost('student_name');
                $stdId = $this->getRequest()->getPost('student_id');
                $stdProgramme = $this->getRequest()->getPost('programme');
                $studentList = $this->studentProfileService->getStudentList($stdName, $stdId, $stdProgramme, $organisation_id);
            }
        }

        else {
            $studentList = array();
        }

        return new ViewModel(array(
            'form' => $form,
            'studentList' => $studentList,
            'organisation_id' => $organisation_id
            ));
    }


    //To view the details of particular student details by DSA
    public function studentPersonalDetailsAction()
    {
        $form = new StudentProfileForm();
		//get the id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		return new ViewModel(array(
			'studentDetails' => $this->studentProfileService->getStudentDetails($id),
			'studentPreviousSchool' => $this->studentProfileService->getStudentPreviousDetails($id),
			'form' => $form,
			));
    }	    
}

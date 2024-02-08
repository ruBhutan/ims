<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Responsibilities\Controller;

use Responsibilities\Service\ResponsibilitiesServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Responsibilities\Form\ResponsibilityCategoryForm;
use Responsibilities\Form\StudentResponsibilityForm;
use Responsibilities\Model\Responsibilities;
use Responsibilities\Model\ResponsibilityCategory;
use Responsibilities\Model\StudentResponsibility;

/**
 * Description of IndexController
 *
 */
 
class ResponsibilitiesController extends AbstractActionController
{
	protected $responsibilityService;
	
	public function __construct(ResponsibilitiesServiceInterface $responsibilityService)
	{
		$this->responsibilityService = $responsibilityService;
	}
    
	public function addResponsibilityCategoryAction()
    {
        $form = new ResponsibilityCategoryForm();
		$responsibilityModel = new ResponsibilityCategory();
		$form->bind($responsibilityModel);
		
		$categories = $this->responsibilityService->listAll($tableName='responsibility_category');
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->responsibilityService->save($responsibilityModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
			 $this->redirect()->toRoute('responsibilitycategory');
         }
		 
        return array(
			'form' => $form,
			'categories' => $categories);
    } 
    
	public function addStudentResponsibilityAction()
    {
        $form = new ResponsibilityCategoryForm();
		$responsibilityModel = new ResponsibilityCategory();
		$form->bind($responsibilityModel);
		
		$students = $this->responsibilityService->listAll($tableName='student');
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
                 try {
					 $this->responsibilityService->save($responsibilityModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'students' => $students);
    }
    
	public function listResponsibilityCategoryAction()
    {
        $form = new ResponsibilityCategoryForm();
		$responsibilityModel = new ResponsibilityCategory();
		$form->bind($responsibilityModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->responsibilityService->save($responsibilityModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
    
	public function editResponsibilityCategoryAction()
    {
       $form = new ResponsibilityCategoryForm();
		$responsibilityModel = new ResponsibilityCategory();
		$form->bind($responsibilityModel);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->responsibilityService->save($responsibilityModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array('form' => $form);
    }
	
	public function addIndividualStdResponsibilityAction()
    {
        //get the id
		$id = (int) $this->params()->fromRoute('id', 0);
		
		$form = new StudentResponsibilityForm();
		$responsibilityModel = new StudentResponsibility();
		$form->bind($responsibilityModel);
		
		//Need to send value of the table name and columns
		$tableName = 'responsibility_category';
		$columnName = 'responsibility_name';
		$responsibilitiesSelect = $this->responsibilityService->listSelectData($tableName, $columnName);
		
		$studentDetail = $this->responsibilityService->findStudent($id);
        
        $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setData($request->getPost());
             if ($form->isValid()) {
				 var_dump($form);
				 die();
                 try {
					 $this->responsibilityService->save($responsibilityModel);
				 }
				 catch(\Exception $e) {
						 die($e->getMessage());
						 // Some DB Error happened, log it and let the user know
				 }
             }
         }
		 
        return array(
			'form' => $form,
			'selectData' => $responsibilitiesSelect,
			'studentDetail' => $studentDetail);
    }
    
}

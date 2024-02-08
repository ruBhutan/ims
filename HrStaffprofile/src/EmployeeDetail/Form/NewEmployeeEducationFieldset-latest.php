<?php
//used when adding new employes
//this fieldset consists of education details, work experience, publications and trainings

namespace EmployeeDetail\Form;

//use EmployeeDetail\Model\NewEmployeeDetail;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class NewEmployeeEducationFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('employeefields');
		
		$this->setHydrator(new ClassMethods(false));
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
		 	   'name' => 'neweducationdetails',
			   'type' => 'Zend\Form\Element\Collection',
			   'options' => array(
			   		'count'=>1,
					'should_create_template' => true,
					'template_placeholder' => 'Education Details',
					'allow_add' => true,
					'target_element' => array(
						'type' => 'EmployeeDetail\Form\NewEducationDetailsFieldset',
					),
			   ),
		 ));
		 
		 $this->add(array(
		 	   'name' => 'newworkexperiencedetails',
			   'type' => 'Zend\Form\Element\Collection',
			   'options' => array(
			   		'count'=>1,
					'should_create_template' => true,
					'template_placeholder' => 'Work Experience',
					'allow_add' => true,
					'target_element' => array(
						'type' => 'EmployeeDetail\Form\NewWorkExperienceDetailsFieldset',
					),
			   ),
		 ));
		 
		 $this->add(array(
		 	   'name' => 'newtrainingdetails',
			   'type' => 'Zend\Form\Element\Collection',
			   'options' => array(
			   		'count'=>1,
					'should_create_template' => true,
					'template_placeholder' => 'Training Details',
					'allow_add' => true,
					'target_element' => array(
						'type' => 'EmployeeDetail\Form\NewTrainingDetailsFieldset',
					),
			   ),
		 ));
		 
		 $this->add(array(
		 	   'name' => 'newpublicationdetails',
			   'type' => 'Zend\Form\Element\Collection',
			   'options' => array(
			   		'count'=>1,
					'should_create_template' => true,
					'template_placeholder' => 'Publication Details',
					'allow_add' => true,
					'target_element' => array(
						'type' => 'EmployeeDetail\Form\NewPublicationDetailsFieldset',
					),
			   ),
		 ));
		 		
		$this->add(array(
			'name' => 'employee_details_id',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
			),
		));
            
	   $this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'class'=>'control-label',
				'value' => 'Submit',
				'id' => 'submitbutton',
				'class' => 'btn btn-success',
				),
			));
		
		$this->add(array(
			'name' => 'approve',
			'type' => 'Submit',
			'attributes' => array(
				'class'=>'control-label',
				'value' => 'Approve',
				'id' => 'approve',
				'class' => 'btn btn-success',
				),
			));
		
		$this->add(array(
			'name' => 'reject',
			'type' => 'Submit',
			'attributes' => array(
				'class'=>'control-label',
				'value' => 'Reject',
				'id' => 'reject',
				'class' => 'btn btn-danger',
				),
			));


     }
	 
	 /**
      * @return array
      */
     public function getInputFilterSpecification()
     {
         return array(
             'name' => array(
                 'required' => false,
             ),
			 'completion_year_1' => array(
                 'required' => false,
             ),
			 'start_period_1' => array(
                 'required' => false,
             ),
			 'end_period_1' => array(
                 'required' => false,
             ),
			 'training_start_date_1' => array(
                 'required' => false,
             ),
			 'training_end_date_1' => array(
                 'required' => false,
             ),
			 'training_end_date_1' => array(
                 'required' => false,
             ),
			 'submission_date_1' => array(
                 'required' => false,
             ),
			 'completion_year_2' => array(
                 'required' => false,
             ),
			 'start_period_2' => array(
                 'required' => false,
             ),
			 'end_period_2' => array(
                 'required' => false,
             ),
			 'training_start_date_2' => array(
                 'required' => false,
             ),
			 'training_end_date_2' => array(
                 'required' => false,
             ),
			 'training_end_date_2' => array(
                 'required' => false,
             ),
			 'submission_date_2' => array(
                 'required' => false,
             ),
			 'completion_year_3' => array(
                 'required' => false,
             ),
			 'start_period_3' => array(
                 'required' => false,
             ),
			 'end_period_3' => array(
                 'required' => false,
             ),
			 'training_start_date_3' => array(
                 'required' => false,
             ),
			 'training_end_date_3' => array(
                 'required' => false,
             ),
			 'training_end_date_3' => array(
                 'required' => false,
             ),
			 'submission_date_3' => array(
                 'required' => false,
             ),
         );
     }
}
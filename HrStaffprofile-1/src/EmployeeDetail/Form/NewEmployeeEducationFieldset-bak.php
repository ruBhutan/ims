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
		//Model not used as we are going to extract the values at the controller
		//$this->setObject(new NewEmployeeDetail());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 //Looping 3 times as we want 3 of each fieldset
		 for($i=1; $i<=3; $i++)
		 {
			 //Education Details
			 $this->add(array(
				'name' => 'college_name_'.$i,
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
					),
			));
			  
			 $this->add(array(
				'name' => 'college_location_'.$i,
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
					),
			));
					
			$this->add(array(
				'name' => 'college_country_'.$i,
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
				),
				'attributes' =>array(
					'class' => 'form-control',
				),
				
			));
			
			$this->add(array(
				'name' => 'field_study_'.$i,
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
				),
				'attributes' =>array(
					'class' => 'form-control',
				),
			));
			
			$this->add(array(
				'name' => 'subject_studied_'.$i,
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
					),
			));
		   
			$this->add(array(
				'name' => 'completion_year_'.$i,
				'type' => 'date',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
				),
			));
			
			$this->add(array(
				'name' => 'result_obtained_'.$i,
				'type' => 'text',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
				),
			));
			
			$this->add(array(
				'name' => 'certificate_obtained_'.$i,
				'type' => 'text',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
				),
			));
	
			$this->add(array(
				'name' => 'education_remarks_'.$i,
				'type' => 'text',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
				),
			));
			
			//Work Experience
			$this->add(array(
				'name' => 'employer_'.$i,
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
					),
			));
			  
			 $this->add(array(
				'name' => 'start_period_'.$i,
				'type' => 'Date',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
					),
			));
					
			$this->add(array(
				'name' => 'end_period_'.$i,
				'type' => 'Date',
				'options' => array(
					'class' => 'control-label',
				),
				'attributes' =>array(
					'class' => 'form-control',
				),
			));
					
			$this->add(array(
				'name' => 'work_remarks_'.$i,
				'type' => 'text',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
				),
			));
			
			//Training Details
			$this->add(array(
				'name' => 'course_title_'.$i,
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
					),
			));
			  
			 $this->add(array(
				'name' => 'institute_name_'.$i,
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
					),
			));
					
			$this->add(array(
				'name' => 'institute_location_'.$i,
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
				),
				'attributes' =>array(
					'class' => 'form-control',
				),
				
			));
			
			$this->add(array(
				'name' => 'institute_country_'.$i,
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
				),
				'attributes' =>array(
					'class' => 'form-control',
				),
			));
			
			$this->add(array(
				'name' => 'field_study_'.$i,
				'type' => 'text',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
					),
			));
		   
			$this->add(array(
				'name' => 'training_start_date_'.$i,
				'type' => 'date',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
				),
			));
			
			$this->add(array(
				'name' => 'training_end_date_'.$i,
				'type' => 'date',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
				),
			));
			
			$this->add(array(
				'name' => 'course_level_'.$i,
				'type' => 'text',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
				),
			));
			
			//Publications
			$this->add(array(
				'name' => 'publication_name_'.$i,
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
					),
			));
			  
			 $this->add(array(
				'name' => 'research_type_'.$i,
				'type' => 'Text',
				'options' => array(
					'class' => 'control-label',
					),
				'attributes' =>array(
					'class' => 'form-control',
					),
			));
					
			$this->add(array(
				'name' => 'submission_date_'.$i,
				'type' => 'date',
				'options' => array(
					'class' => 'control-label',
				),
				'attributes' =>array(
					'class' => 'form-control',
				),
				
			));
			
			$this->add(array(
			'name' => 'publication_remarks_'.$i,
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
		 }
		
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
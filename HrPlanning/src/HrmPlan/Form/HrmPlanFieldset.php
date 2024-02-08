<?php

namespace HrmPlan\Form;

use HrmPlan\Model\HrmPlan;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class HrmPlanFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('hrmplan');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new HrmPlan());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'five_year_plan',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'readonly' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'working_agency',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
           
           $this->add(array(
           'name' => 'department_name',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Department',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true
             ),
         ));
           
		 $this->add(array(
           'name' => 'existing_no',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
           
         $this->add(array(
           'name' => 'required_no',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
           
		   $this->add(array(
           'name' => 'proposal_submission_date',
            'type'=> 'date',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
              
          $this->add(array(
           'name' => 'proposal_status',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'requirement_year_1',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'requirement_year_2',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'requirement_year_3',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'requirement_year_4',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'requirement_year_5',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
           
         $this->add(array(
           'name' => 'approval_date',
            'type'=> 'date',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         )); 
         
          $this->add(array(
           'name' => 'remarks',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
          
          
		 /*
		 $this->add(array(
           'name' => 'position_title_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Position Title',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'position_category_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Position Category',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'position_level_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Position Level',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 */
		 $this->add(array(
           'name' => 'remarks',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'rows' => 3
             ),
         ));
          
         $this->add(array(
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array(
                    'class'=>'control-label',
					'value' => 'Save',
					'id' => 'submitbutton',
                    'class' => 'btn btn-success',
					),
				));
				
           $this->add(array(
				'name' => 'cancel',
				'type' => 'Submit',
				'attributes' => array(
                    'class'=>'control-label',
					'value' => 'Cancel',
					'id' => 'submitbutton',
                    'class' => 'btn btn-danger',
					),
				));
		
		$this->add(array(
				'name' => 'delete',
				'type' => 'Submit',
				'attributes' => array(
                    'class'=>'control-label',
					'value' => 'Delete',
					'id' => 'submitbutton',
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
         );
     }
}
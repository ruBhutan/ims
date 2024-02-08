<?php

namespace HrdPlan\Form;

use HrdPlan\Model\HrdPlan;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class HrdPlanFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('hrdplan');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new HrdPlan());
         
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
           'name' => 'course_title',
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
           'name' => 'total_no_slots',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
           
		   $this->add(array(
           'name' => 'duration',
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
           'name' => 'duration_unit',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select',
                      'value_options' => array(
                        'days' => 'days',
                        'months' => 'months',
                        'years' => 'years',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'duration_unit',
             ),
         ));
           
          $this->add(array(
           'name' => 'training_type',
            'type'=> 'Select',
             'options' => array(
                 'empty_option' => 'Please Select a Training Type',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
           
		   $this->add(array(
           'name' => 'source_of_funding',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                     'empty_option' => 'Please Select a Funding Source',
					 'disable_inarray_validator' => true,
					 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
              
          $this->add(array(
           'name' => 'target_group',
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
           'name' => 'tuition_fees',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         )); 
         
          $this->add(array(
           'name' => 'dsa_tada',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'air_fare',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 		 
         $this->add(array(
           'name' => 'priority',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'location_of_training',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'amount_year_1',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'amount_year_2',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'amount_year_3',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'amount_year_4',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'amount_year_5',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true,
             ),
         ));
		 /*
		 $this->add(array(
           'name' => 'submission_date',
            'type'=> 'date',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 */
		 $this->add(array(
           'name' => 'approval_status',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'approval_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'remarks',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'rows' => 3,
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
					'id' => 'submitbutton',
                    'class' => 'btn btn-success',
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
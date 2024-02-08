<?php

namespace EmpTravelAuthorization\Form;

use EmpTravelAuthorization\Model\EmpTravelAuthorization;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class EmpTravelAuthorizationFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
        parent::__construct('emptravelauthorization');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new EmpTravelAuthorization());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));
          
         $this->add(array(
             'name' => 'id',
             'attributes' => array(
             'type' => 'Hidden',
              ),   
         ));
		 
		 $this->add(array(
             'name' => 'organisation_id',
             'attributes' => array(
             'type' => 'Hidden',
              ),   
         ));
		 
		 $this->add(array(
             'type' => 'text',
             'name' => 'employee_details_id', 
             'attributes' => array(
                   'type' => 'text',
                 'class' => 'form-control'
              ),   
         ));
         
          $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'authorizing_officer',
             'options' => array(
                  'value_options' => array(
                     '1' => 'Vice Chancellor',
                      '2' => 'Director',
                      '3' => 'President',
                    ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                 'value' =>'1'//set selected to '1'
             )
         ));
         
      
            $this->add(array(
             'type' => 'Zend\Form\Element\Date',
             'name' => 'travel_auth_date', 
             'attributes' => array(
                'type' => 'date',
                 'class' => 'form-control'
              ),   
             'options' => array(
                      )
         ));
         
         $this->add(array(
             'type' => 'text',
             'name' => 'no_of_days', 
             'attributes' => array(
                   'type' => 'text',
                 'class' => 'form-control'
              ),   
             'options' => array(
                     )
         ));
         
         $this->add(array(
             'type' => 'text',
             'name' => 'estimated_expenses', 
             'attributes' => array(
                   'type' => 'text',
                 'class' => 'form-control'
              ),   
             'options' => array(
                     )
         ));
		 
		 $this->add(array(
             'type' => 'text',
             'name' => 'advanced_required', 
             'attributes' => array(
                   'type' => 'text',
                 'class' => 'form-control'
              ),   
             'options' => array(
                     )
         ));
		 
		 $this->add(array(
             'type' => 'text',
             'name' => 'advanced_sanctioned', 
             'attributes' => array(
                   'type' => 'text',
                 'class' => 'form-control'
              ),   
             'options' => array(
                     )
         ));
		 
		 $this->add(array(
             'type' => 'text',
             'name' => 'no_of_days', 
             'attributes' => array(
                   'type' => 'text',
                 'class' => 'form-control'
              ),   
             'options' => array(
                     )
         ));
		  
           $this->add(array(
             'name' => 'estimated_expenses',
             'type'=>'Text',
             'attributes' => array(
                 'class' => 'form-control',
             ),
             'options' => array(
                   ),
         ));
           
        $this->add(array(
             'name' => 'advance_required',
             'type'=>'Text',
             'attributes' => array(
                 'class' => 'form-control',
             ),
             'options' => array(
                   ),
         ));
		 
		 $this->add(array(
             'name' => 'advance_sanctioned',
             'type'=>'Text',
             'attributes' => array(
                 'class' => 'form-control',
             ),
             'options' => array(
                   ),
         )); 
		 
		 $this->add(array(
             'name' => 'tour_status',
             'type'=>'Text',
             'attributes' => array(
                 'class' => 'form-control',
                 
             ),
             'options' => array(
                   ),
         )); 
		 
		 $this->add(array(
             'name' => 'remarks',
             'type'=>'Text',
             'attributes' => array(
                 'class' => 'form-control',
                 
             ),
             'options' => array(
                   ),
         ));
		 
		 $this->add(array(
		 	   'name' => 'emptraveldetails',
			   'type' => 'Zend\Form\Element\Collection',
			   'options' => array(
			   		'count'=> 2,
					'should_create_template' => true,
					'allow_add' => true,
					'target_element' => array(
						'type' => 'EmpTravelAuthorization\Form\EmpTravelDetailsFieldset',
					),
			   ),
		 ));
         
         $this->add(array(
             'name' => 'upload',
             'attributes' => array(
             'type' => 'file',
                 'class' => 'form-control',
                 'value' => 'Choose File',
              ),   
             'options' =>array(
                   ),
         ));
		 
		 $this->add(array(
				'name' => 'approve',
				'type' => 'Submit',
				'attributes' => array(
                    'class'=>'control-label',
					'value' => 'Approve',
					'id' => 'Approve',
                    'class' => 'btn btn-success',
					),
				));
			
		$this->add(array(
			'name' => 'reject',
			'type' => 'Submit',
			'attributes' => array(
				'class'=>'control-label',
				'value' => 'Reject',
				'id' => 'Reject',
				'class' => 'btn btn-danger',
				),
			));
         
         $this->add(array(
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array(
					'value' => 'Submit',
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
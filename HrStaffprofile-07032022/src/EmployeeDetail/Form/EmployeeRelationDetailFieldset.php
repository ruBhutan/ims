<?php

namespace EmployeeDetail\Form;

use EmployeeDetail\Model\EmployeeRelationDetail;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class EmployeeRelationDetailFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('employeedetail');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new EmployeeRelationDetail());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
			'name' => 'relation_type',
			'type' => 'select',
			'options' => array(
				'empty_option' => 'Select Relation Type',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				),
		));
          
         $this->add(array(
			'name' => 'name',
			'type' => 'Text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				),
		));
                		
		$this->add(array(
			'name' => 'nationality',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Nationality',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
				
		$this->add(array(
			'name' => 'occupation',
			'type' => 'text',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
			),
		));
		
		$this->add(array(
			'name' => 'gender',
			'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Gender',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		
		$this->add(array(
			'name' => 'remarks',
			'type' => 'Textarea',
			'options' => array(
				'class' => 'control-label',
				),
			'attributes' =>array(
				'class' => 'form-control',
				'row' => 5,
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
				'value' => 'Add Relation Detail',
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
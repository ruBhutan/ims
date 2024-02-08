<?php

namespace OrgSettings\Form;

use OrgSettings\Model\Organisation;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class DepartmentFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
        parent::__construct('departments');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new Organisation());
         
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
             'type' => 'text',
             'name' => 'department_name', 
             'attributes' => array(
                   'type' => 'text',
                 'class' => 'form-control'
              ),   
         ));
         
         $this->add(array(
             'name' => 'organisation_id', 
             'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select an Organisation',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
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
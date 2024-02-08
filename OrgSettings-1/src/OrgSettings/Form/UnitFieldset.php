<?php

namespace OrgSettings\Form;

use OrgSettings\Model\Organisation;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class UnitFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
        parent::__construct('unit');
		
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
             'name' => 'unit_name', 
             'attributes' => array(
                   'type' => 'text',
                 'class' => 'form-control'
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
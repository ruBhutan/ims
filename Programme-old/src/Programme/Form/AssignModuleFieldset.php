<?php

namespace Programme\Form;

use Programme\Model\AssignModule;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class AssignModuleFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('assignmodule');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new AssignModule());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
         
		 $this->add(array(
           'name' => 'year',
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
           'name' => 'section',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select A Section',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
				  'class' => 'form-control',
             ),
         ));
           
         $this->add(array(
           'name' => 'module_tutor',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Module Tutor',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
				  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'module_tutor_2',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Module Tutor',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
				  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'module_tutor_3',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Module Tutor',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
				  'class' => 'form-control',
             ),
         ));
		 		 
		 $this->add(array(
           'name' => 'module_coordinator',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Module Co-Ordinator',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
				  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'programmes_id',
           'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Programme',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 		 
		 $this->add(array(
           'name' => 'academic_modules_allocation_id',
           'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Module',
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
					'value' => 'Assign Module',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
		  
		  $this->add(array(
			'name' => 'search',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Search',
					'id' => 'searchbutton',
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
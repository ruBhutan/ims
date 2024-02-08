<?php

namespace Administration\Form;

use Administration\Model\UserRoutes;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class UserRoutesFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('userroutes');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new UserRoutes());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'route_category',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select a Route Category',
            				 'class'=>'control-label',
            				 'value_options' => array(
            				 	'Home' => 'Home',
            					'Administration' => 'Administration',
            					'Academic' => 'Academic',
            					'Human Resources' => 'Human Resources',
            					'Inventory' => 'Inventory',
            					'Student' => 'Student',
            					'Planning' => 'Planning',
            					'Finance' => 'Finance',
            					'PMS' => 'PMS',
            					'Budgeting' => 'Budgeting',
            					'User' => 'User'
      				          ),
                   ),
                   'attributes' => array(
                        'class' => 'form-control',
                        'required' => 'required',
                   ),
               ));
		 
		 $this->add(array(
           'name' => 'route_name',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'route_details',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'user_level_one_module_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Module Name',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'user_level_two_module_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Module Name',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'user_level_three_module_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Module Name',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
           
         $this->add(array(
           'name' => 'route_remarks',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'rows' => 3
             ),
         ));
		 
		 $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Add Route',
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
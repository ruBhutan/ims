<?php

namespace Administration\Form;

use Administration\Model\UserSubModule;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class SubmoduleFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('submodule');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new UserSubModule());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'submodule_name',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required'
             ),
         ));
		 
		 $this->add(array(
           'name' => 'submodule_description',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
				          'rows' => 3
             ),
         ));
		 
		 $this->add(array(
           'name' => 'menu_weight',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'user_module_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Module Name',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required'
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
                  'required' => 'required',
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
                  'class' => 'form-control',
                  'required' => 'required',
             ),
         ));
		 
		 $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Add Sub Module',
					'id' => 'submitbutton',
                        'class' => 'btn btn-success',
				),
		  ));
		  
		  $this->add(array(
			'name' => 'search',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Search',
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
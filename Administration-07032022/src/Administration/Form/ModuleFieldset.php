<?php

namespace Administration\Form;

use Administration\Model\UserModule;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ModuleFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('module');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new UserModule());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'module_name',
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
           'name' => 'module_description',
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
           'name' => 'menu_weight',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		  $this->add(array(
           'name' => 'menu_icon',
            'type'=> 'radio',
             'options' => array(
                 'class' => 'radio',
				 'value_options' => array(
						'fa-home' => 'Home Icon',
						'fa-bar-chart' => 'Bar Chart',
						'fa-bank' => 'Building',
						'fa-book' => 'Book',
						'fa-building-o' => 'University',
						'fa-calculator' => 'Calculator',
						'fa-calendar' => 'Calendar',
						'fa-child' => 'Person',
						'fa-dashboard' => 'Dashboard',
						'fa-graduation-cap' => 'Graudation Cap',
						'fa-user' => 'User',
						'fa-users' => 'Users',
						'fa-table' => 'Table',
						'fa-desktop' => 'Desktop',
						'fa-edit' => 'Writing',
						'fa-windows' => 'Windows',
						'fa-thumbs-o-up' => 'Thumbs up',
                        'fa fa-database' => 'Money'
					 )
             ),
         ));
		 
		 $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Add Module',
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
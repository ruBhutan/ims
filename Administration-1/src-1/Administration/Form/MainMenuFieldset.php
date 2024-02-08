<?php

namespace Administration\Form;

use Administration\Model\UserMainMenu;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class MainMenuFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('menu');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new UserMainMenu());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'menu_name',
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
           'name' => 'menu_description',
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
						'fa fa-home' => 'Home Icon',
						'fa fa-bar-chart' => 'Bar Chart',
						'fa fa-bank' => 'Building',
						'fa fa-book' => 'Book',
						'fa fa-building-o' => 'University',
						'fa fa-calculator' => 'Calculator',
						'fa fa-calendar' => 'Calendar',
						'fa fa-child' => 'Person',
						'fa fa-dashboard' => 'Dashboard',
						'fa fa-graduation-cap' => 'Graudation Cap',
						'fa fa-user' => 'User',
						'fa fa-users' => 'Users',
						'fa fa-table' => 'Table',
						'fa fa-desktop' => 'Desktop',
						'fa fa-edit' => 'Writing',
						'fa fa-windows' => 'Windows',
						'fa fa-thumbs-o-up' => 'Thumbs up',
            			'fa fa-database' => 'Store'
					 )
             ),
         ));

      $this->add(array(
           'name' => 'user_menu_level',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'value' => 0,
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
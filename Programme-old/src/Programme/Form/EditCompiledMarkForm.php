<?php

namespace Programme\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

class EditCompiledMarkForm extends Form
{
	protected $weightage;
	protected $serviceLocator;
	
	public function __construct($serviceLocator = null,  $weightage, array $options = [])
    {
		parent::__construct('ajax', $options);

		$this->serviceLocator = $serviceLocator; 
		$this->ajax = $serviceLocator;
		$this->ajax = $options;

		$authPlugin = $this->serviceLocator->get('ControllerPluginManager')->get('AuthPlugin')->getUserAttributes();
		// Use service locator to get the authPlugin
		$this->weightage = $weightage;
				
		$this->setAttributes(array(
			'class' => 'form-horizontal form-label-left',
		));

		$this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          		 
		 $this->add(array(
           'name' => 'marks',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
					  'class' => 'form-control',
					  'required' => true,
					  'min' => 0.0,
					  'max' => $this->weightage,
					  'step' => 0.01
				 ),
         ));
		 
		 $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
			 'options' => array(
                'csrf_options' => array(
                        'timeout' => 600
                )
             )
         ));
                
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Search',
				'id' => 'submitbutton',
				'class' => 'btn btn-success'
				),
		));
		
		$this->add(array(
			'name' => 'update',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Update Marks',
				'id' => 'compilebutton',
				'class' => 'btn btn-success'
				),
		));
	}
	
}
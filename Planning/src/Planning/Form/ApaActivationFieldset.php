<?php

namespace Planning\Form;

use Planning\Model\ApaActivation;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ApaActivationFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('apaactivation');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new ApaActivation());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'apa_type',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
				 'empty_option' => 'Please Select Date For',
                 'value_options' => array(
                      'APA' => 'APA',
          					  'Mid-Term Review (APA)' => 'Mid-Term Review (APA)',
          					  'Annual Review (APA)' => 'Annual Review (APA)',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
          		 
		  $this->add(array(
           'name' => 'apa_year',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'readonly' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'date_range',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control glyphicon glyphicon-calendar fa fa-calendar',
				  'id' => 'reservation'
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
<?php

namespace PmsDates\Form;

use PmsDates\Model\PmsDates;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class PmsDatesFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('pmsdates');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new PmsDates());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'date_for',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
				 'empty_option' => 'Please Select Date For',
                 'value_options' => array(
                      'IWP Submission' => 'IWP Submission',
					  'IWP Review' => 'IWP Review',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'pms_year',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'readonly' => true,
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
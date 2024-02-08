<?php

namespace HrActivation\Form;

use HrActivation\Model\HrActivation;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class HrActivationFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('hractivation');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new HrActivation());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'hr_proposal_type',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select HR Proposal Type',
				 'class'=>'control-label',
				 'value_options' => array(
				 	'HRD Proposal' => 'HRD Propsoal',
					'HRM Proposal' => 'HRM Proposal'
				 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		  $this->add(array(
           'name' => 'five_year_plan',
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
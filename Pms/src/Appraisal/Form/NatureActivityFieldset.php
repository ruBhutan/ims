<?php

namespace Appraisal\Form;

use Appraisal\Model\NatureActivity;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class NatureActivityFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('natureactivity');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new NatureActivity());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
           		 		 
		 $this->add(array(
           'name' => 'pms_academic_weight_id',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
				 'empty_option' => 'Please Select Nomination For',
				 'value_options' => array(
				 	'Peer' => 'Peer',
					'Subordinate' => 'Sub-Ordinate',
					'Beneficiary' => 'Beneficiary'
				 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'maximum_score',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'nature_of_activity',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
			'name' => 'submitactivity',
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
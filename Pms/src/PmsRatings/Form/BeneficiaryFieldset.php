<?php

namespace PmsRatings\Form;

use PmsRatings\Model\Beneficiary;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class BeneficiaryFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('beneficiary');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new Beneficiary());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
           		 
		 $this->add(array(
           'name' => 'questions',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 		 
		 $this->add(array(
			'name' => 'submitpeer',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Submit Peer Question',
					'id' => 'submitpeer',
                    'class' => 'btn btn-success',
				),
		  ));
		  
		  $this->add(array(
			'name' => 'submitbeneficiary',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Submit Beneficiary Question',
					'id' => 'submitbeneficiary',
                    'class' => 'btn btn-success',
				),
		  ));
		  
		  $this->add(array(
			'name' => 'submitsubordinate',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Submit Subordinate Question',
					'id' => 'submitsubordinate',
                    'class' => 'btn btn-success',
				),
		  ));
		  
		  $this->add(array(
			'name' => 'submitstudent',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Submit Student Question',
					'id' => 'submitstudent',
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
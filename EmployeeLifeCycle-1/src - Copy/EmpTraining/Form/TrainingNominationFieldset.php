<?php

namespace EmpTraining\Form;

use EmpTraining\Model\TrainingNomination;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class TrainingNominationFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('trainingnomination');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new TrainingNomination());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 $this->add(array(
             'name' => 'employee_details_id',
              'type' => 'Hidden'  
         ));
          		 
		 $this->add(array(
           'name' => 'training_detail',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Training',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
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
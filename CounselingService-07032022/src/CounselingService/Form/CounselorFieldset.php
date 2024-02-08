<?php

namespace CounselingService\Form;

use CounselingService\Model\Counselor;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class CounselorFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('counselor');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new Counselor());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'organisation_id',
              'type' => 'Hidden'  
         ));

     $this->add(array(
                'name' => 'employee_details_id',
                'type'=> 'Select',
                'options' => array(
                    'class'=>'control-label',
                    'disable_inarray_validator' => true,
                    'empty_option' => 'Select Staff to Appoint Counselor',
                    'value_options' => array(
                        '0' => 'Select'
                    )
                ),
               'attributes' => array(
                    'class' => 'form-control',
                    'required' => 'required'
                ),
            ));		 
		 
		 $this->add(array(
           'name' => 'status',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Status',
                 'value_options' => array(
                    'Active' => 'Active',
                    'Inactive' => 'Inactive'
                 )
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));


     $this->add(array(
           'name' => 'remarks',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'rows' => 3
             ),
         ));

     $this->add(array(
           'name' => 'appointment_date',
            'type'=> 'Date',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'rows' => 3
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
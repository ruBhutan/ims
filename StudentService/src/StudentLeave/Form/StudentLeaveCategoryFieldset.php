<?php

namespace StudentLeave\Form;

use StudentLeave\Model\StudentLeaveCategory;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class StudentLeaveCategoryFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('studentleavecategory');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new StudentLeaveCategory());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'leave_category',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Leave Category',
                 'value_options' => array(
                        'Weekend Leave' => 'Weekend Leave',
                        'Weekday Leave' => 'Weekday Leave',
                        'Day Outing' => 'Day Outing',
                        'Hospital/ Emergency' => 'Hospital/ Emergency'
                    )
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 
		 $this->add(array(
           'name' => 'approval_by',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
				 'empty_option' => 'Please Select',
				 'value_options' => array(
				),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'remarks',
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
           'name' => 'organisation_id',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
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
<?php

namespace DocumentFiling\Form;

use DocumentFiling\Model\FilingType;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class FilingTypeFieldset extends Fieldset implements InputFilterProviderInterface
{
  public function __construct()
  {
    // we want to ignore the name passed
    parent::__construct('filingtype');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new FilingType());
    
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
         'name' => 'department_id',
          'type' => 'Hidden'  
     ));
     $this->add(array(
         'name' => 'employee_details_id',
          'type' => 'Hidden'  
     ));
		 
		 $this->add(array(
           'name' => 'meeting',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true
             ),
         ));
    $this->add(array(
           'name' => 'meeting_abbr',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                'class' => 'form-control ',
                'required' => true
             ),
         ));
		 
     $this->add(array(
            'name' => 'status',
            'type'=> 'Select',
             'options' => array(
                //'default_value_option' => 'Active',
                'empty_option' => 'Please Select a Type',
                'disable_inarray_validator' => true,
                'class'=>'control-label',
                'value_options'=> array(
                    'Active' => 'Active',
                    'Close' => 'Close'
                ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
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
<?php

namespace Hostel\Form;

use Hostel\Model\HostelAllocation;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class HostelAllocationFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('hostelallocation');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new HostelAllocation());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'hostel_name',
            'type'=> 'select',
             'options' => array(
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'multiple' => 'multiple',
                  'class' => 'form-control',
                  'style' => 'height:150px',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'yearwise',
            'type'=> 'select',
             'options' => array(
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'multiple' => 'multiple',
                  'class' => 'form-control',
                  'style' => 'height:100px',
             ),
         ));
		 /*
		 $this->add(array(
           'name' => 'branchwise',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Assortment Type',
				 'value_options' => array(
				 	'unassorted_programme' => 'Do Not Mix Programmes',
					'assorted_programme' => 'Mix Programmes'
				 ),
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
          */
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
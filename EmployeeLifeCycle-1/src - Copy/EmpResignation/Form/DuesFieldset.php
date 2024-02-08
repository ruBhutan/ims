<?php

namespace EmpResignation\Form;

use EmpResignation\Model\Dues;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class DuesFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('dues');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new Dues());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'emp_resignation_id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'issuing_authority',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		  $this->add(array(
           'name' => 'date_of_issue',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calender-o',
                  'id' => 'single_cal2',
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
                  'class' => 'form-control',
				  'rows' => 5
             ),
         ));
		 
		 $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Issue No Due Clearance',
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
             'remarks' => array(
                 'required' => true,
             ),
         );
     }
}
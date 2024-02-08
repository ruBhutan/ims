<?php

namespace EmployeeLeave\Form;

use EmployeeLeave\Model\OnbehalfEmployeeLeave;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class OnbehalfEmployeeLeaveFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('onbehalfemployeeleave');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new OnbehalfEmployeeLeave());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'days_of_leave',
            'type'=> 'number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
		  'required' => 'required',
                  'min' => 0.5,
                  'step' => 0.5
             ),
         ));
		 
		 $this->add(array(
           'name' => 'from_date',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                  'required' => 'required',
                  'id' => 'single_cal3'
             ),
         ));

     $this->add(array(
           'name' => 'to_date',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                  'required' => 'required',
                  'id' => 'single_cal4'
             ),
         ));
		 
           		 
		 $this->add(array(
           'name' => 'substitution',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Substitution',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'evidence_file',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'id' => 'evidence_file',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'reason',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'rows' => 3,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'leave_status',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
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
				  'rows' => 3,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'employee_details_id',
            'type'=> 'Select',
             'options' => array(
                 'empty_option' => 'Please Select Staff',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'emp_leave_category_id',
             'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Leave Type',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
              ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => 'required',
             ),
         ));

     $this->add(array(
           'name' => 'applied_by_id',
            'type'=> 'text',
             'options' => array(
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
			 'evidence_file' => array(
			 	'required' => false,
				'validators' => array(
					array(
					'name' => 'FileUploadFile',
					),
          array(
                'name' => 'Zend\Validator\File\Size',
                'options' => array(
                  'min' => '10kB',
                  'max' => '2MB',
                ),
              ),
              array(
                'name' => 'Zend\Validator\File\Extension',
                'options' => array(
                  'extension' => ['png','jpg','jpeg','pdf'],
                ),
              ),
				),
				'filters' => array(
					array(
					'name' => 'FileRenameUpload',
					'options' => array(
						'target' => './data/leave',
						'useUploadName' => true,
						'useUploadExtension' => true,
						'overwrite' => true,
						'randomize' => true
						),
					),
				),
			 ),
         );
     }
}
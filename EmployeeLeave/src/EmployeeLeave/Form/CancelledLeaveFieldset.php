<?php

namespace EmployeeLeave\Form;

use EmployeeLeave\Model\CancelledLeave;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class CancelledLeaveFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('cancelledleave');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new CancelledLeave());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'no_of_days',
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
           'name' => 'cancelled_by',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'employee_details_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'emp_leave_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

     $this->add(array(
           'name' => 'remarks',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'rows' => 5,
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
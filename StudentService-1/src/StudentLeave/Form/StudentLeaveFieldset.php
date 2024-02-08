<?php

namespace StudentLeave\Form;

use StudentLeave\Model\StudentLeave;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class StudentLeaveFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('leaveapplication');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new StudentLeave());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'from_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control fa fa-calender-o',
                  'required' => 'required',
                  'id' => 'single_cal3',
                ),
            ));
     $this->add(array(
           'name' => 'to_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calender-o',
                  'required' => 'required',
                    'id' => 'single_cal4',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'outing_category',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Outing',
                 'value_options' => array(
                        'Morning' => 'Morning',
                        'Afternoon' => 'Afternoon',
                        'Evening' => 'Evening'
                    )
             ),
             'attributes' => array(
                  'class' => 'form-control',
          'required' => true
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
           'name' => 'reasons',
            'type'=> 'Textarea',
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
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				          'rows' => 3,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'approved_by',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'student_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'student_leave_category_id',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Please Select Leave Category',
                 'value_options' => array(
                ),
              ),
             'attributes' => array(
                  'class' => 'form-control ',
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
		  
		  $this->add(array(
				'name' => 'approve',
				'type' => 'Submit',
				'attributes' => array(
                    'class'=>'control-label',
					'value' => 'Approve',
					'id' => 'Approve',
                    'class' => 'btn btn-success',
					),
				));
			
			$this->add(array(
				'name' => 'reject',
				'type' => 'Submit',
				'attributes' => array(
                    'class'=>'control-label',
					'value' => 'Reject',
					'id' => 'Reject',
                    'class' => 'btn btn-danger',
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
				),
				'filters' => array(
					array(
					'name' => 'FileRenameUpload',
					'options' => array(
						'target' => './data/studentleave',
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
<?php

namespace EmployeeTask\Form;

use EmployeeTask\Model\EmployeeTask;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class EmployeeTaskFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('employeetask');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new EmployeeTask());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'employeetask_details',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => 'required'
             ),
         ));
           
         $this->add(array(
           'name' => 'from_date',
            'type'=> 'Text',

             'options' => array(
                 'class'=>'control-label',     
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                'class' => 'form-control',
                'placeholder'=>'yyyy-mm-dd',
                'id' => 'single_cal3'
             ),
         ));
         $this->add(array(
           'name' => 'to_date',
            'type'=> 'Text',

             'options' => array(
                 'class'=>'control-label',     
             ),
             'attributes' => array(
                'class' => 'form-control fa fa-calendar-o',
                'class' => 'form-control',
                'placeholder'=>'yyyy-mm-dd',
                'id' => 'single_cal4'
             ),
         ));

         $this->add(array(
           'name' => 'from_time',
            'type'=> 'time',
             'options' => array(
                 'class'=>'control-label',
                 'format' => 'H:i'
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => false
             ),
         ));

         $this->add(array(
           'name' => 'to_time',
            'type'=> 'time',
             'options' => array(
                 'class'=>'control-label',
                 'format' => 'H:i'
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => false
             ),
         ));
		 
		 $this->add(array(
           'name' => 'recorded_by',
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
           'name' => 'staff_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'employeetask_category_id',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
				 'disable_inarray_validator' => true,
				 'empty_option' => 'Please Select Record Type',
				 'value_options' => array(
				 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'employeetask_category',
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
           'name' => 'employeetask_type',
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
            'name' => 'employeetask_type',
            'type'=> 'Select',
             'options' => array(
                'empty_option' => 'Please Select a Type',
                'disable_inarray_validator' => true,
                'class'=>'control-label',
                'value_options'=> array(
                    'Work_from_Home' => 'Work from Home',
                    'Work_from_Office' => 'Work from Office',
                    'Work_During_Travel' => 'Work During Travel'
                ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
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
                    'Ongoing' => 'Ongoing',
                    'Completed' => 'Completed'
                ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
             ),
         ));
	 
		 $this->add(array(
           'name' => 'description',
            'type'=> 'textarea',
             'options' => array(
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
                 'required' => false
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
            'from_time' => array(
                'required' => false, 
            ),
            'to_time' => array(
                'required' => false, 
            ),
            'name' => array(
                'required' => false,
            ),
            'evidence_file' => array(
                'required' => false,
                'allow_empty' => true,
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
                            'target' => './data/employeetask',
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
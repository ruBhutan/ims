<?php

namespace StudentAdmission\Form;

use StudentAdmission\Model\StudentAdmission;
use StudentAdmission\Model\StudentSemesterRegistration;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;


class StudentSemesterRegistrationFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
  {
         // we want to ignore the name passed
    parent::__construct('updatestudentsemester');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new StudentSemesterRegistration());
          

    $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

        $this->add(array(
             'name' => 'id',
             'attributes' => array(
             'type' => 'Hidden',
              ),   
         ));

         $this->add(array(
           'name' => 'student_section_id',
            'type'=> 'Text',
            'options' => array(
              'class'=>'control-label',
              'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
                  'readonly' => 'readonly',
             ),
         ));


         $this->add(array(
           'name' => 'section',
            'type'=> 'Text',
            'options' => array(
              'class'=>'control-label',
              'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
                  'readonly' => 'readonly',
             ),
         ));

         $this->add(array(
           'name' => 'year_id',
            'type'=> 'Text',
            'options' => array(
              'class'=>'control-label',
              'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
                  'readonly' => 'readonly',
             ),
         ));


         $this->add(array(
           'name' => 'year',
            'type'=> 'Text',
            'options' => array(
              'class'=>'control-label',
              'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
                  'readonly' => 'readonly',
             ),
         ));


         $this->add(array(
           'name' => 'semester_id',
            'type'=> 'Text',
            'options' => array(
              'class'=>'control-label',
              'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
                  'readonly' => 'readonly',
             ),
         ));


         $this->add(array(
           'name' => 'semester',
            'type'=> 'Text',
            'options' => array(
              'class'=>'control-label',
              'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
                  'readonly' => 'readonly',
             ),
         ));


          $this->add(array(
           'name' => 'first_name',
            'type'=> 'Text',
            'options' => array(
              'class'=>'control-label',
              'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
             ),
         ));

          $this->add(array(
           'name' => 'middle_name',
            'type'=> 'Text',
            'options' => array(
              'class'=>'control-label',
              'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
             ),
         ));

          $this->add(array(
           'name' => 'last_name',
            'type'=> 'Text',
            'options' => array(
              'class'=>'control-label',
              'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
             ),
         ));

          $this->add(array(
           'name' => 'student_id',
            'type'=> 'Text',
            'options' => array(
              'class'=>'control-label',
              'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
             ),
         ));

          $this->add(array(
           'name' => 'studentId',
            'type'=> 'Text',
            'options' => array(
              'class'=>'control-label',
              'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
             ),
         ));

         $this->add(array(
           'name' => 'academic_year',
            'type'=> 'Text',
            'options' => array(
              'class'=>'control-label',
              'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
		  'required' => 'required',
                  'readonly' => 'readonly',
             ),
         ));

         $this->add(array(
          'name' => 'student_status_type_id',
          'type'=> 'Select',
             'options' => array(
                    'class'=>'control-label',
                    'disable_inarray_validator' => true,
                    'empty_option' => 'Select Reason',
                ),
             'attributes' => array(
                  'class' => 'form-control',
                    'required' => 'required',
             ),
       ));

      $this->add(array(
           'name' => 'remarks',
            'type'=> 'Textarea',
            'options' => array(
              'class'=>'control-label',
              'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'rows' => '5',
             ),
         ));

       $this->add(array(
           'name' => 'file',
            'type'=> 'file',
            'options' => array(
              'class'=>'control-label',
              'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
      
            $this->add(array(
            'name' => 'submit',
             'type' => 'Submit',
              'attributes' => array(
                'value' => 'Update',
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
        'file' => array(
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
                'target' => './data/not_reported_student',
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

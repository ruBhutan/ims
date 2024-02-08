<?php

namespace StudentAdmission\Form;

use StudentAdmission\Model\StudentAdmission;
use StudentAdmission\Model\UpdateStudentPreviousSchool;
use Zend\Form\Fieldset;
//use Zend\Form\AlumniNewRegistrationFieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;


class UpdateStudentPreviousSchoolFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('updatestudentpreviousschool');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new UpdateStudentPreviousSchool());
         
            $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

            $this->add(array(
                'name' => 'id',
                'type' => 'Hidden'  
            ));
          

            $this->add(array(
                'name' => 'student_id',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
             ),
            ));

            $this->add(array(
                'name' => 'enrollment_year',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
                ),
            ));

            $this->add(array(
                'name' => 'programme_name',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                'rows' => '4',
                'readonly' => 'readonly',
                ),
            ));

            $this->add(array(
                'name' => 'first_name',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
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
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                'rows' => '3',
                'readonly' => 'readonly',
                ),
            )); 

            $this->add(array(
                'name' => 'last_name',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                 ),
                'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
                ),
            ));

            $this->add(array(
                'name' => 'student_type',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',

                ),
            ));

          $this->add(array(
             'name' => 'previous_institution',
             'type' => 'Text',    
             'options' => array(
                'class' => 'control-label',
                      ),

             'attributes' => array(
                'class' => 'form-control',
                 'placeholder' => 'Previous School',
                 'required' => 'required',
              ),
         ));
         
         $this->add(array(
             'name' => 'aggregate_marks_obtained',
             'type' => 'Text',    
             'options' => array(
                'class' => 'control-label',
                      ),
             'attributes' => array(
                'class' => 'form-control',
                 'placeholder' => 'Marks Obtained',
              ),
         ));
         
         $this->add(array(
             'name' => 'from_date',
             'type' => 'Zend\Form\Element\Date',    
             'options' => array(
                'class' => 'control-label',
                     ),
             'attributes' => array(
                'class' => 'form-control',
                   'type' => 'Date',
              ),
         ));

         $this->add(array(
             'name' => 'to_date',
             'type' => 'Zend\Form\Element\Text',    
             'options' => array(
                'class' => 'control-label',
                     ),
             'attributes' => array(
                'class' => 'form-control',
                 'type' => 'Date',
              ),
         ));
         
         $this->add(array(
             'name' => 'previous_education',
             'type' => 'Text',    
             'options' => array(
                'class' => 'control-label',
                      ),
             'attributes' => array(
                'class' => 'form-control',
                 'placeholder' => 'Education Attended',
                 'required' => 'required',
              ),
         ));

          $this->add(array(
           'name' => 'student_gender',
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
           'name' => 'studentID',
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
                'name' => 'submit',
                'type' => 'Submit',
                'attributes' => array(
                'class'=>'control-label',
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

<?php

namespace StudentAdmission\Form;

use StudentAdmission\Model\StudentAdmission;
use StudentAdmission\Model\UpdateStudent;
use Zend\Form\Fieldset;
//use Zend\Form\AlumniNewRegistrationFieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;


class UpdateStudentFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('updatestudent');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new UpdateStudent());
         
            $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

            $this->add(array(
                'name' => 'id',
                'type' => 'Hidden'  
            ));

            $this->add(array(
             'name' => 'student_id',
              'type' => 'Hidden'  
         ));
          
            $this->add(array(
                'name' => 'registration_no',
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
                'name' => 'admission_year',
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
                'name' => 'submission_date',
                'type'=> 'Text',
                'options' => array(
                  'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control',
                  'readonly' => 'readonly',

                ),
            ));

         /*   $this->add(array(
                'name' => 'joining_date',
                'type'=> 'Date',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',

                ),
            ));*/

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
                'name' => 'organisation_id',
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
                'name' => 'organisation_name',
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
                'name' => 'programme_id',
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
                'name' => 'programmes_id',
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
                'name' => 'cid',
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
                'name' => 'gender',
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
                'name' => 'semester_id',
                'type'=> 'Select',
                'options' => array(
                    'class'=>'control-label',
                    'disable_inarray_validator' => true,
                    'empty_option' => 'Select Semester',
                    'value_options' => array(
                        '0' => 'Select'
                    )
                ),
               'attributes' => array(
                    'class' => 'form-control',
                    'value' => '0', // Set selected to 0
                    'required' => 'required'
                ),
            ));

            $this->add(array(
                'name' => 'academic_year',
                'type'=> 'Text',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control',
                ),
            ));

            $this->add(array(
                'name' => 'date_of_birth',
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
                'name' => 'student_type_id',
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
                'name' => 'student_type',
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
                'name' => 'scholarship_type',
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
           'name' => 'student_reporting_status',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                  'empty_option' => 'Select Report Status',
                      'value_options' => array(                     
                        'Reported' => 'Reported',
                        'Not Reported' => 'Not Reported',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
         ));

             $this->add(array(
           'name' => 'student_registration_id',
            'type'=> 'Text',
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
           'name' => 'parent_name',
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
           'name' => 'parents_contact_no',
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
           'name' => 'relationship_id',
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
           'name' => 'relation',
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
           'name' => 'guardian_name',
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
           'name' => 'guardian_contact_no',
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
           'name' => 'guardian_relation',
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

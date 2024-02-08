<?php

namespace StudentAdmission\Form;

use StudentAdmission\Model\StudentAdmission;
use StudentAdmission\Model\UpdateReportedStudentDetails;
use Zend\Form\Fieldset;
//use Zend\Form\AlumniNewRegistrationFieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;


class UpdateReportedStudentDetailsFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('updatedstudentreportingdetails');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new UpdateReportedStudentDetails());
         
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
                'name' => 'date',
                'type'=> 'date',
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
                'name' => 'programmes_id',
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
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',

                ),
            ));
            $this->add(array(
                'name' => 'date_of_birth',
                'type'=> 'Date',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                  'readonly' => 'readonly',
                 ),
            ));

            $this->add(array(
                'name' => 'house_no',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                 ),
            ));

            $this->add(array(
                'name' => 'thram_no',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                 ),
            ));

            $this->add(array(
                'name' => 'scholarship_type',
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
           'name' => 'student_category_id',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Student Category',
                 'value_options' => array(
                    )
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
             ));

             $this->add(array(
           'name' => 'student_country_id',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Country',
                 'value_options' => array(
                    )
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'value' => '31',
                  'required' => 'required',
             ),
             ));

             $this->add(array(
           'name' => 'student_nationality_id',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Nationality',
                 'value_options' => array(
                    )
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'value' => '23',
                  'required' => 'required',
             ),
             ));

            $this->add(array(
                'name' => 'email',
                'type'=> 'email',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
                ),
             ));

            $this->add(array(
                'name' => 'contact_no',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
                ),
             ));

            $this->add(array(
                'name' => 'father_name',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'father_cid',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
           'name' => 'father_nationality',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Nationality',
                 'value_options' => array(
                    )
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'value' => '23',
                  'required' => 'required',
             ),
             ));

            $this->add(array(
                'name' => 'father_house_no',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'father_thram_no',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'father_occupation',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'mother_name',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'mother_cid',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
           'name' => 'mother_nationality',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Nationality',
                 'value_options' => array(
                    )
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'value' => '23',
                  'required' => 'required',
             ),
             ));

            $this->add(array(
                'name' => 'mother_house_no',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'mother_thram_no',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'mother_occupation',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'parents_present_address',
                'type'=> 'TextArea',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                  'rows' => 4,
                ),
             ));

            $this->add(array(
                'name' => 'parents_contact_no',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'guardian_name',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'guardian_relation',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'guardian_occupation',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'guardian_contact_no',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'guardian_address',
                'type'=> 'TextArea',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                  'rows' => 4,
                ),
             ));

            $this->add(array(
                'name' => 'remarks',
                'type'=> 'TextArea',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                  'rows' => 4,
                ),
             ));

          $this->add(array(
              'name' => 'stdpreviousschooldetails',
              'type' => 'Zend\Form\Element\Collection',
              'options' => array(
                'count'=> 1,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => array(
                  'type' => 'StudentAdmission\Form\StdPreviousSchoolDetailsFieldset',
              ),
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

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
                'name' => 'registration_no',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
             ),
            ));
     
            $this->add(array(
                'name' => 'joining_date',
                'type'=> 'Date',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
            ));

            $this->add(array(
                'name' => 'admission_year',
                'type'=> 'Date',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
            ));
           
            $this->add(array(
                'name' => 'organisation_name_id',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
            ));
           
            $this->add(array(
                'name' => 'programme_name_id',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                'rows' => '4',
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
                 ),
            ));

            $this->add(array(
                'name' => 'blood_group',
                'type'=> 'select',
                'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     '0' => 'Select',
                     'A+' => 'A+',
                     'B+' => 'B+',
                     'AB+'=> 'AB+',
                     'O' => 'O',
                      ),
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
            ));

            $this->add(array(
                'name' => 'birth_place',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                 ),
            ));

            $this->add(array(
                'name' => 'nationality',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
            ));

            $this->add(array(
                'name' => 'mother_tongue',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
             'name' => 'student_type_id',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => '--Select--',
                  'value_options' => array(
                    
                    )
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
             ));

            $this->add(array(
           'name' => 'student_reporting_status',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     '0' => 'Select',
                     'Reported' => 'Reported',
                     'Not Reported' => 'Not Reported',
                     'Not Reported 2' => 'Not Reported 2',
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

            
        

            $this->add(array(
                'name' => 'village',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'gewog_name_id',
                'type'=> 'Select',
                'options' => array(
                'class'=>'control-label',
                'disable_inarray_validator' => true,
                'empty_option' => '--Select--',
                'value_options' => array(
                     'self' => 'self'
                    )
                ),

                'attributes' => array(
                'class' => 'form-control ',
                ),
            ));

            $this->add(array(
                'name' => 'dzongkhag_name_id',
                'type'=> 'Select',
                'options' => array(
                'class'=>'control-label',
                'disable_inarray_validator' => true,
                'empty_option' => '--Select--',
                'value_options' => array(
                     'self' => 'self'
                    )
                ),

                'attributes' => array(
                'class' => 'form-control ',
                ),
            ));

            $this->add(array(
                'name' => 'present_address',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'phone_number',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'mobile_number',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'email_address',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
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
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
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
                'name' => 'father_village',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'father_gewog',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'father_dzongkhag',
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
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
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
                'name' => 'mother_village',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'mother_gewog',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'mother_dzongkhag',
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
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
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
                'name' => 'guardian_present_address',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'guardian_village',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'guardian_gewog',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'guardian_dzongkhag',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'guardian_phone_no',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'guardian_email_address',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'institution',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'aggregate_marks_obtained',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'duration_from',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'duration_to',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));  

            $this->add(array(
                'name' => 'education',
                'type'=> 'Text',
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
                'class'=>'control-label',
                    'value' => 'Submit',
                    'id' => 'submitbutton',
                    'class' => 'btn btn-success',
                  ),
            ));

            $this->add(array(
                'name' => 'save',
                'type' => 'Submit',
                'attributes' => array(
                'class'=>'control-label',
                    'value' => 'Save',
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

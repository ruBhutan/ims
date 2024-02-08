<?php

namespace StudentAdmission\Form;

use StudentAdmission\Model\StudentAdmission;
use Zend\Form\Fieldset;
use zend\InputFilter\InputFilter;
use zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class StudentDetailsFieldset extends Fieldset implements InputFilterProviderInterface
{
  protected $inputFilter;

	public function __construct()
     {

         // we want to ignore the name passed
        parent::__construct('studentdetails');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new StudentAdmission());
         
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
            'name' => 'dzongkhag',
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
            'name' => 'gewog',
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
            'name' => 'village',
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
			 
			 // Father
        $this->add(array(
            'name' => 'father_dzongkhag',
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
            'name' => 'father_gewog',
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
            'name' => 'father_village',
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
			 
			 //Mother
        $this->add(array(
            'name' => 'mother_dzongkhag',
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
            'name' => 'mother_gewog',
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
            'name' => 'mother_village',
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
                'name' => 'previous_institution',
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
                'name' => 'from_date',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

            $this->add(array(
                'name' => 'to_date',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));  

            $this->add(array(
                'name' => 'previous_education',
                'type'=> 'Text',
                'options' => array(
                 'class'=>'control-label',
                ),
                'attributes' => array(
                  'class' => 'form-control ',
                ),
             ));

             $this->add(array(
                'name' => 'student_reporting_status',
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
					'value' => 'Add',
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
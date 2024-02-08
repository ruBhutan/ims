<?php

namespace StudentAdmission\Form;

use StudentAdmission\Model\StudentAdmission;
use StudentAdmission\Model\RegisterStudent;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;


class RegisterStudentFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('registerstudent');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new RegisterStudent());
         
            $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

            $this->add(array(
                'name' => 'id',
                'type' => 'Hidden'  
            ));

            $this->add(array(
                'name' => 'rank',
                'type'=> 'number',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                    'required' => 'required',
            ),));

            $this->add(array(
                'name' => 'aggregate',
                'type'=> 'Text',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                    'required' => 'required'
             ),
            ));

            $this->add(array(
                'name' => 'gender',
                'type'=> 'Select',
                'options' => array(
                    'class'=>'control-label',
                    'disable_inarray_validator' => true,
                    'empty_option' => 'Select Gender',
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
                'name' => 'registration_no',
                'type'=> 'Text',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                    'required' => 'required'
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
                    'required' => 'required'
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
                'name' => 'cid',
                'type'=> 'Text',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                    'required' => 'required'
                ),
            ));

            $this->add(array(
                'name' => 'student_type_id',
                'type'=> 'Select',
                'options' => array(
                    'class'=>'control-label',
                    'disable_inarray_validator' => true,
                    'empty_option' => 'Select Student Type',
                    'value_options' => array(
                        '0' => '--Select--',
                    )
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                    'required' => 'required'
                 ),
            ));
             
             $this->add(array(
                'name' => 'parent_name',
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
                'name' => 'date_of_birth',
                'type'=> 'Text',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control fa fa-calendar-o',
                    'required' => 'required',
                    'id' => 'single_cal2',
                    //'format' => 'Y-m-d'
                ),
            ));

             $this->add(array(
                'name' => 'relationship_id',
                'type'=> 'Select',
                'options' => array(
                    'class'=>'control-label',
                    'disable_inarray_validator' => true,
                    'empty_option' => 'Select Relation Type',
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
                'name' => 'submission_date',
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
                'name' => 'moe_student_code',
                'type'=> 'Text',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                ),
            ));

               $this->add(array(
                'name' => 'twelve_indexnumber',
                'type'=> 'Text',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                ),
            ));

                $this->add(array(
                'name' => 'twelve_stream',
                'type'=> 'Text',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                ),
            ));

                 $this->add(array(
                'name' => 'twelve_student_type',
                'type'=> 'Text',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                ),
            ));

                  $this->add(array(
                'name' => 'twelve_school',
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
                    'value' => 'Register',
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

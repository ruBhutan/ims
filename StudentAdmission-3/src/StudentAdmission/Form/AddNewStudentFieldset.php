<?php

namespace StudentAdmission\Form;

use StudentAdmission\Model\StudentAdmission;
use StudentAdmission\Model\AddNewStudent;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class AddNewStudentFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('addnewstudent');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new AddNewStudent());
         
            $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

            $this->add(array(
                'name' => 'id',
                'type' => 'Hidden'  
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
                ),
                'attributes' => array(
                    'class' => 'form-control fa fa-calender-o',
                    'required' => 'required',
                    'id' => 'single_cal2',
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
                'name' => 'contact_no',
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
                'name' => 'relationship_id',
                'type'=> 'Select',
                'options' => array(
                    'class'=>'control-label',
                    'disable_inarray_validator' => true,
                    'empty_option' => 'Select Relationship',
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
                'name' => 'organisation_id',
                'type'=> 'Text',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                ),
            ));

            $this->add(array(
           'name' => 'year_id',
            'type'=> 'Select',
             'options' => array(
                 'empty_option' => 'Please Select Year',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
    
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
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

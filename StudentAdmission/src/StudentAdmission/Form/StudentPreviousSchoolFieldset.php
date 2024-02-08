<?php

namespace StudentAdmission\Form;

use StudentAdmission\Model\StudentAdmission;
use StudentAdmission\Model\StudentPreviousSchool;
use Zend\Form\Fieldset;
use zend\InputFilter\InputFilter;
use zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class StudentPreviousSchoolFieldset extends Fieldset implements InputFilterProviderInterface
{

    public function __construct()
     {

         // we want to ignore the name passed
        parent::__construct('studentpreviousschool');
        
        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new StudentPreviousSchool());
         
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
                'type'=> 'Select',
                'options' => array(
                    'class'=>'control-label',
                    'disable_inarray_validator' => true,
                    'empty_option' => 'Select Previous School',
                    'value_options' => array(
                    )
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                    'required' => 'required'
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
                    'required' => 'required',
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
                    'required' => 'required',
                    'id' => 'single_cal2',
                    //'format' => 'Y-m-d'
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
                    'required' => 'required',
                    'id' => 'single_cal3',
                    //'format' => 'Y-m-d'
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
                    'required' => 'required',
                ),
            ));

          $this->add(array(
           'name' => 'stdgender',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'readonly' => 'readonly',
             ),
         ));
           
         $this->add(array(
                'name' => 'submit',
                'type' => 'Submit',
                'attributes' => array(
                 'class'=>'control-label',
                    'value' => 'Add Previous School',
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




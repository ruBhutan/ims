<?php

namespace Alumni\Form;

//use Alumni\Model\AlumniMember;
use Alumni\Model\AlumniRegistration;
use Zend\Form\Fieldset;
//use Zend\Form\AlumniNewRegistrationFieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;


class AlumniNewRegistrationFieldset extends Fieldset implements InputFilterProviderInterface
{
  public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('alumninewregistration');
    
    $this->setHydrator(new ClassMethods(false));
    $this->setObject(new AlumniRegistration());
         
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
                  'required' => true,
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
           'name' => 'cid',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
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
           'rows' => '4',
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
                  'id' => 'single_cal3',
                  'required' => true,
             ),
         ));


            $this->add(array(
           'name' => 'contact_no',
            'type'=> 'Number',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
             ),
         ));

            $this->add(array(
           'name' => 'email_address',
            'type'=> 'Email',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
             ),
         ));

            $this->add(array(
           'name' => 'enrollment_year',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                    'disable_inarray_validator' => true,
                    'empty_option' => 'Select Enrollment Year',
                    'value_options' => array(
                    )
                ),
               'attributes' => array(
                    'class' => 'form-control',
                    'required' => true,
             ),
         ));

            $this->add(array(
           'name' => 'graduation_year',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                    'disable_inarray_validator' => true,
                    'empty_option' => 'Select Graduation Year',
                    'value_options' => array(
                    )
                ),
               'attributes' => array(
                    'class' => 'form-control',
                    'required' => true,
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
                    )
                ),
               'attributes' => array(
                    'class' => 'form-control',
                    'required' => true,
             ),
         ));

            $this->add(array(
           'name' => 'registration_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                  'id' => 'single_cal2',
                  'required' => true,
             ),
         ));
           
         $this->add(array(
           'name' => 'alumni_programmes_id',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => '--Select--',
                 'value_options' => array(
                      '0' => '--Select--',
                    )
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
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
           'name' => 'current_job_title',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

        $this->add(array(
           'name' => 'present_address',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
                  'rows' => 5,
             ),
         ));

        $this->add(array(
           'name' => 'qualification_level_id',
            'type'=> 'Select',
             'options' => array(
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                 'empty_option' => 'Select Qualification Level',
                 'value_options' => array(
                    )
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

         $this->add(array(
           'name' => 'current_job_organisation',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
         $this->add(array(
           'name' => 'qualification_field',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

		 $this->add(array(
           'name' => 'alumni_status',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

     $this->add(array(
           'name' => 'alumni_type',
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

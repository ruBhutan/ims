<?php

namespace CounselingService\Form;

use CounselingService\Model\CounselingAppointment;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class CounselingAppointmentFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('counselingappointment');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new CounselingAppointment());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
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
                'name' => 'counselor_id',
                'type'=> 'Select',
                'options' => array(
                    'class'=>'control-label',
                    'disable_inarray_validator' => true,
                    'empty_option' => 'Select Counselor',
                    'value_options' => array(
                    )
                ),
               'attributes' => array(
                    'class' => 'form-control',
                    'required' => 'required'
                ),
            ));
		 
		 
		 $this->add(array(
           'name' => 'subject',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'description',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				          'rows' => 5
             ),
         ));
		 
		 $this->add(array(
           'name' => 'appointment_time',
            'type'=> 'time',
             'options' => array(
                'class'=>'control-label',
                'format' => 'H:i'
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  //'step' => "1",
                  'required' => true,
             ),
         ));
		 
		 $this->add(array(
           'name' => 'appointment_date',
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
           'name' => 'appointment_status',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 		 
		 $this->add(array(
           'name' => 'remarks',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				          'rows' =>3
             ),
         ));

         $this->add(array(
            'name' => 'consent_detail',
             'type'=> 'Textarea',
              'options' => array(
                  'class'=>'control-label',
              ),
              'attributes' => array(
                   'class' => 'form-control ',
                           'rows' => 5
              ),
          ));
		 
		 $this->add(array(
           'name' => 'applicant_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

     $this->add(array(
           'name' => 'applicant_type',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

     $this->add(array(
           'name' => 'applied_date',
            'type'=> 'Date',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

     $this->add(array(
           'name' => 'granted_date',
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
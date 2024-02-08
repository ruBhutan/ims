<?php

namespace Alumni\Form;

//use AlumniMember\Model\AlumniMember;
use Alumni\Model\AlumniEnquiry;
use Zend\Form\Fieldset;
//use Zend\Form\AlumniNewRegistrationFieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;


class CreateAlumniEnquiryFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('alumnienquiry');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new AlumniEnquiry());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
        
        
           $this->add(array(
           'name' => 'topic',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
     
	 	$this->add(array(
           'name' => 'alumni_id',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
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
           'name' => 'email_contents',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'rows' => '4',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'enquiry_status',
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

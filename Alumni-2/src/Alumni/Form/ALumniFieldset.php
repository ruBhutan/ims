<?php

namespace Alumni\Form;

//use Alumni\Model\AlumniMember;
use Alumni\Model\Alumni;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class AlumniFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('alumnilist');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new Alumni());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'fk_student_id',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));

          $this->add(array(
           'name' => 'fk_programmes_id',
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
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                     '0' => '--Select--',
                     'Active' => 'Active',
                 ),
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

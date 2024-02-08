<?php

namespace Alumni\Form;

use Alumni\Model\AlumniResource;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;


class CreateAlumniResourceFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('alumniresource');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new AlumniResource());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
        
         
           $this->add(array(
           'name' => 'description',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
             ),
         ));
     
         $this->add(array(
           'name' => 'link',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 $this->add(array(
           'name' => 'alumni_remarks',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'rows' => 5,
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

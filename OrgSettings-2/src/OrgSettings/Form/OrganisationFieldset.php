<?php

namespace OrgSettings\Form;

use OrgSettings\Model\Organisation;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class OrganisationFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
        parent::__construct('organisation');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new Organisation());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));
          
         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));

         $this->add(array(
           'name' => 'organisation_name',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true
             ),
         ));

          $this->add(array(
           'name' => 'organisation_code',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true
             ),
         ));

          $this->add(array(
           'name' => 'abbr',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true
             ),
         ));


          $this->add(array(
           'name' => 'address',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'rows' => 3,
                  'required' => true
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
<?php

namespace Planning\Form;

use Planning\Model\OrganisationBudgetOverlay;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class OrganisationBudgetOverlayFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('budgetoverlay');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new OrganisationBudgetOverlay());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'amount',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
             ),
         ));
		 
        $this->add(array(
           'name' => 'awpa_objectives_activity_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select an Objective',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true
             ),
         ));
        
        $this->add(array(
           'name' => 'organisation_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select an Organisation',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
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
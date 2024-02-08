<?php

namespace EmpTravelAuthorization\Form;

use EmpTravelAuthorization\Model\EmpTravelAuthorization;
use EmpTravelAuthorization\Model\EmpTravelDetails;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class EmpTravelDetailsFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
        parent::__construct('emptraveldetails');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new EmpTravelDetails());
         
         $this->setAttributes(array(
                    'class' => 'form-group',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'travel_authorization_id',
              'type' => 'Hidden'  
         ));
               
         $this->add(array(
             'type' => 'text',
             'name' => 'from_date',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control has-feedback-left',
                  'id' => 'single_cal3'
             ),
         ));
		 
		 $this->add(array(
             'name' => 'from_station', 
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
             'type' => 'text',
             'name' => 'to_date',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control has-feedback-left',
				  'id' => 'single_cal4'
             ),
         ));
		 
		 $this->add(array(
             'type' => 'text',
             'name' => 'to_station',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
         
         $this->add(array(
             'name' => 'mode_of_travel',
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
             'name' => 'purpose_of_tour',
             'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
             'name' => 'halt_at',
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
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array(
					'value' => 'Add Travel Details',
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
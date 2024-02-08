<?php

namespace EmpTravelAuthorization\Form;

use EmpTravelAuthorization\Model\TravelDetails;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class EmpTravelDetailsFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
        parent::__construct('emptraveldetails');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new TravelDetails());
         
         $this->setAttributes(array(
                    'class' => 'form-group',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
               
         $this->add(array(
             'type' => 'Zend\Form\Element\Date',
             'name' => 'from_date', 
             'attributes' => array(
                'type' => 'date',
				 'class' => 'col-md-2 col-sm-12 col-xs-12 form-group'
              ),   
             'options' => array(
                      )
         ));
		 
		 $this->add(array(
             'type' => 'text',
             'name' => 'from_station', 
             'attributes' => array(
                 'class' => 'col-sm-1 nopadding',
				 'placeholder' => 'From Station'
              ),   
             'options' => array(
                      )
         ));
         
         $this->add(array(
             'type' => 'Zend\Form\Element\Date',
             'name' => 'to_date', 
             'attributes' => array(
                   'type' => 'date',
                 'class' => 'col-sm-2 nopadding'
              ),   
             'options' => array(
                     )
         ));
		 
		 $this->add(array(
             'type' => 'text',
             'name' => 'to_station', 
             'attributes' => array(
                 'class' => 'col-sm-1 nopadding',
				 'placeholder' => 'To Station'
              ),   
             'options' => array(
                      )
         ));
         
         $this->add(array(
             'type' => 'text',
             'name' => 'mode_of_travel',
             'options' => array(
                 'value_options' => array(
                     ),
             ),
             'attributes' => array(
                  'class' => 'col-sm-2 nopadding',
				  'placeholder' => 'Mode of Travel'
             )
         ));
		 
		 $this->add(array(
             'type' => 'text',
             'name' => 'halt_at', 
             'attributes' => array(
                 'class' => 'col-sm-1 nopadding',
				 'placeholder' => 'Halt At'
              ),   
             'options' => array(
                      )
         ));
         
          
         $this->add(array(
             'name' => 'purpose_of_tour',
             'type'=>'text',
             'attributes' => array(
                 'class' => 'col-sm-3 nopadding',
				 'placeholder' => 'Purpose of Tour',
                 'rows' => 1,
             ),
             'options' => array(
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
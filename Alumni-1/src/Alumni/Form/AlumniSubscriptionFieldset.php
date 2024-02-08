<?php

namespace Alumni\Form;

use Alumni\Model\AlumniSubscription;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;


class AlumniSubscriptionFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('alumnisubscription');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new AlumniSubscription());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));

  
           $this->add(array(
           'name' => 'subscription_details',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true,
                  'rows' => '5',
             ),
         ));

           $this->add(array(
                'name' => 'subscription_type',
                'type'=> 'Select',
                'options' => array(
                    'class'=>'control-label',
                    'empty_option' => 'Select Subscription Type',
                    'value_options' => array(
                        'Yearly' => 'Yearly',
                        'Lifetime' => 'Lifetime',
                    )
                ),
               'attributes' => array(
                    'class' => 'form-control',
                    'required' => 'required',
                ),
            ));
     
         $this->add(array(
           'name' => 'subscription_charge',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true,
                  'rows' => '3',
             ),
         ));
           

          $this->add(array(
           'name' => 'bank_name',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true,
             ),
         ));

          $this->add(array(
           'name' => 'bank_account_no',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
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

<?php

namespace CollegeResearch\Form;

use CollegeResearch\Model\CargBudgetPlan;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class CargBudgetPlanFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('cargbudgetplan');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new CargBudgetPlan());
         
         $this->setAttributes(array(
                    'class' => 'form-group',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'purpose',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'control-label col-md-4 col-sm-3 col-xs-12',
				  'placeholder' => 'Purpose',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'amount',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'control-label col-md-2 col-sm-3 col-xs-12',
				  'placeholder' => 'Amount',
             ),
         ));
           
         $this->add(array(
           'name' => 'remarks',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'control-label col-md-5 col-sm-5 col-xs-12',
				  'placeholder' => 'Remarks',
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
<?php

namespace UniversityResearch\Form;

use UniversityResearch\Model\AurgActionPlanBudget;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class AurgActionPlanBudgetFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('aurgactionplanbudget');
		
		$this->setHydrator(new ClassMethodsHydrator(false));
		$this->setObject(new AurgActionPlanBudget());
         
         $this->setAttributes(array(
                    'class' => 'form-group form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'aurg_grant_id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'particulars',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'control-label col-md-5 col-sm-3 col-xs-12',
				          'placeholder' => 'Particulars',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'start_date',
            'type'=> 'Date',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'control-label col-md-2 col-sm-3 col-xs-12',
				          'placeholder' => 'Start Date',
             ),
         ));
           
         $this->add(array(
           'name' => 'end_date',
            'type'=> 'Date',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'control-label col-md-2 col-sm-3 col-xs-12',
        				  'placeholder' => 'End Date',
             ),
         ));
           
		 $this->add(array(
           'name' => 'budget',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'control-label col-md-3 col-sm-3 col-xs-12',
				  'placeholder' => 'Budget Amount',
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
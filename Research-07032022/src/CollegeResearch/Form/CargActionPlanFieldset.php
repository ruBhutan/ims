<?php

/*
 * ActionPlanFieldset is dynamically added rows
*/

namespace CollegeResearch\Form;

use CollegeResearch\Model\CargActionPlan;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class CargActionPlanFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('cargactionplan');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new CargActionPlan());
         
         $this->setAttributes(array(
                    'class' => 'form-group',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'activity_name',
            'type'=> 'Text',
             'attributes' => array(
                  'class' => 'control-label col-md-3 col-sm-3 col-xs-12',
				  'placeholder' => 'Activity Name',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'time_frame',
            'type'=> 'Date',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'control-label col-md-2 col-sm-2 col-xs-12',
				  'placeholder' => 'Time Frame',
             ),
         ));
           
         $this->add(array(
           'name' => 'remarks',
            'type'=> 'TextArea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'control-label col-md-6 col-sm-6 col-xs-12',
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
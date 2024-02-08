<?php

namespace CollegeResearch\Form;

use CollegeResearch\Model\CargResearch;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class CargResearchFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('cargresearch');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new CargResearch());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		
		 $this->add(array(
           'name' => 'amount_applied_for',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
           
		  
		  $this->add(array(
           'name' => 'research_summary',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'resizable_textarea form-control ',
				  'style' => 'width: 90%; overflow: hidden; word-wrap: break-word; resize: horizontal; height: 87px;',
             ),
         ));
		 
		 $this->add(array(
		 	   'name' => 'actionplan',
			   'type' => 'Zend\Form\Element\Collection',
			   'options' => array(
			   		'count'=>1,
					'should_create_template' => true,
					'allow_add' => true,
					'target_element' => array(
						'type' => 'CollegeResearch\Form\CargActionPlanFieldset',
					),
			   ),
		 ));
		  
		 $this->add(array(
           'name' => 'application_step_status',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'employee_details_id',
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
					'value' => 'Save and Move to Next Form',
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
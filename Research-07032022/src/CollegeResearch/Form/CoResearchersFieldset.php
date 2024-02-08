<?php

namespace CollegeResearch\Form;

use CollegeResearch\Model\CoResearchers;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class CoResearchersFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
        parent::__construct('cargcoresearchers');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new CoResearchers());
         
         $this->setAttributes(array(
                    'class' => 'form-group',
                ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
               
		 $this->add(array(
             'name' => 'name', 
             'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'control-label',
                  'placeholder' => 'Co-Researcher Name',
             ),
         ));
		 		 
		 $this->add(array(
             'name' => 'qualification', 
             'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'control-label',
                  'placeholder' => 'Co-Researcher Qualification ',
             ),
         ));
		 
		 $this->add(array(
             'name' => 'position_level', 
             'type'=> 'Text',
             'options' => array(
				  'class' => 'control-label',
             ),
             'attributes' => array(
                  'class' => 'control-label',
                  'placeholder' => 'Position Level ',
             ),
         ));
		 
		 $this->add(array(
             'name' => 'appointment_date', 
             'type'=> 'date',
             'options' => array(
                  'class' => 'control-label',
             ),
             'attributes' => array(
				  'class' => 'control-label',
				  'placeholder' => 'App. Year',
             ),
         ));
		 
		 $this->add(array(
             'name' => 'email', 
             'type'=> 'Text',
             'options' => array(
                  'class' => 'control-label',
             ),
             'attributes' => array(
				  'class' => 'control-label',
				  'placeholder' => 'Email',
             )
         ));
		 
		 $this->add(array(
             'name' => 'contact_no', 
             'type'=> 'Text',
             'options' => array(
                  'class' => 'control-label',
             ),
             'attributes' => array(
				  'class' => 'control-label',
				  'placeholder' => 'Contact No.',
             )
         ));
		 
		 $this->add(array(
             'name' => 'researcher_type', 
             'type'=> 'select',
			 'options' => array(
                'class' => 'control-label',
				 'empty_option' => 'Co-Researcher Type',
				 'value_options' => array(
				 	'Faculty' => 'Faculty',
					'Student' => 'Student',
				 ),
             ),
             'attributes' => array(
				  'class' => 'control-label',
             )
         ));
		 
		 $this->add(array(
             'name' => 'researcher_category', 
             'type'=> 'select',
			 'options' => array(
                'class' => 'control-label',
				 'empty_option' => 'CARG Category',
				 'value_options' => array(
                    'ECR –Early Career Researcher' => 'ECR –Early Career Researcher',
                    'MCR – Middle career researcher' => 'MCR – Middle career researcher',
                    'ACR – Advanced career Researcher' => 'ACR – Advanced career Researcher'
				 ),
             ),
             'attributes' => array(
				  'class' => 'control-label',
             )
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
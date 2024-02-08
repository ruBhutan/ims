<?php

namespace UniversityResearch\Form;

use UniversityResearch\Model\AurgResearchers;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class AurgResearchersFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('aurgtitle');
		
		$this->setHydrator(new ClassMethodsHydrator(false));
		$this->setObject(new AurgResearchers());
         
		 $this->setAttributes(array(
                    'class' => 'form-group',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'coresearcher_name',
            'type'=> 'Text',
             'attributes' => array(
				  'class' => 'control-label col-md-2 col-sm-2 col-xs-12',
				  'placeholder' => 'Co-Researcher Name ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'working_agency',
            'type'=> 'Text',
             'attributes' => array(
                  'class' => 'control-label col-md-2 col-sm-2 col-xs-12',
				  'placeholder' => 'Working Agency',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'position_level',
            'type'=> 'Text',
             'attributes' => array(
                  'class' => 'control-label col-md-2 col-sm-2 col-xs-12',
				  'placeholder' => 'Position Level',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'sex',
            'type'=> 'Text',
             'attributes' => array(
                  'class' => 'control-label col-md-2 col-sm-2 col-xs-12',
				  'placeholder' => 'Co-Researcher Sex',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'email',
            'type'=> 'Text',
             'attributes' => array(
                  'class' => 'control-label col-md-2 col-sm-2 col-xs-12',
				  'placeholder' => 'Email',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'contact_no',
            'type'=> 'Text',
             'attributes' => array(
                  'class' => 'control-label col-md-2 col-sm-2 col-xs-12',
				  'placeholder' => 'Contact No',
             ),
         ));
          
		  $this->add(array(
           'name' => 'employee_details_id',
            'type'=> 'hidden',
             'attributes' => array(
                  'class' => 'col-md-3 col-sm-3 col-xs-12',
             ),
         ));
     }
	 
	 /**
      * @return array
      */
     public function getInputFilterSpecification()
     {
         return array(
             'coresearcher_name' => array(
                 'required' => false,
             ),
         );
     }
}
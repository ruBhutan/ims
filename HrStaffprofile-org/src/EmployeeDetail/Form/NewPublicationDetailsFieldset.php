<?php
//used when adding new employes
//this fieldset consists of education details, work experience, publications and trainings

namespace EmployeeDetail\Form;

//use EmployeeDetail\Model\NewEmployeeDetail;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class NewPublicationDetailsFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('employeefields');
		
		$this->setHydrator(new ClassMethods(false));
		//Model not used as we are going to extract the values at the controller
		//$this->setObject(new NewEmployeeDetail());
         
         $this->setAttributes(array(
                    'class' => 'form-group',
                ));
	 
		 $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'publication_name',
            'type'=> 'Text',
             'attributes' => array(
				  'class' => 'control-label',
				  'placeholder' => 'Publication Name ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'research_type',
            'type'=> 'Text',
             'attributes' => array(
                  'class' => 'control-label',
				  'placeholder' => 'Research Type',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'submission_date',
            'type'=> 'Text',
             'attributes' => array(
                  'class' => 'control-label',
				  'placeholder' => 'Sbumission Date',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'publication_remarks',
            'type'=> 'Text',
             'attributes' => array(
                  'class' => 'control-label',
				  'placeholder' => 'Pulibcation Remarks',
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
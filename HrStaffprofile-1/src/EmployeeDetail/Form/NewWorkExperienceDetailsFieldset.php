<?php
//used when adding new employes
//this fieldset consists of education details, work experience, publications and trainings

namespace EmployeeDetail\Form;

//use EmployeeDetail\Model\NewEmployeeDetail;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class NewWorkExperienceDetailsFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('employeeworkfields');
		
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
           'name' => 'employer',
            'type'=> 'Text',
			'options' => array(
					'label' => ' ',
			 ),
             'attributes' => array(
				  'class' => 'control-label',
				  'placeholder' => 'Employer Name ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'start_period',
            'type'=> 'Date',
			'options' => array(
					'label' => ' Start Period: ',
			 ), 
             'attributes' => array(
                  'class' => 'control-label',
				  'placeholder' => 'Period From',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'end_period',
            'type'=> 'Date',
			'options' => array(
					'label' => ' End Period: ',
			 ),
             'attributes' => array(
                  'class' => 'control-label',
				  'placeholder' => 'Period To',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'remarks',
            'type'=> 'Text',
			'options' => array(
					'label' => ' Remarks: ',
			 ),
             'attributes' => array(
                  'class' => 'control-label',
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
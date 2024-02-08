<?php

namespace AcademicAssessment\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

class DeleteCompiledAssessmentForm extends Form
{	
	public function __construct()
     {
        
         parent::__construct('deletecompiledassessment');
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
         
       $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));

         $this->add(array(
            'name' => 'programmes_id',
             'type' => 'Hidden'  
        ));

        $this->add(array(
            'name' => 'academic_modules_allocation_id',
             'type' => 'Hidden'  
        ));

        $this->add(array(
            'name' => 'assessment_component_id',
             'type' => 'Hidden'  
        ));

        $this->add(array(
            'name' => 'section',
             'type' => 'Hidden'  
        ));
		 	 
         $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
	        'options' => array(
		        'csrf_options' => array(
			        'timeout' => 1800
             	)
	        )
         ));


         $this->add(array(
			'name' => 'submit',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Search',
					'id' => 'submitbutton',
                        'class' => 'btn btn-danger',
				),
		  ));
     }
}

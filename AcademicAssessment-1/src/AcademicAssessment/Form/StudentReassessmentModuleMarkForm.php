<?php

namespace AcademicAssessment\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

class StudentReassessmentModuleMarkForm extends Form
{
    protected $moduleAssessmentCount;

	public function __construct($moduleAssessmentCount)
	{
		 // we want to ignore the name passed
        parent::__construct();
		
		$this->moduleAssessmentCount = $moduleAssessmentCount;
         
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
			'type' => 'hidden',
		));
        $this->add(array(
            'name' => 'student_id',
            'type' => 'hidden',
        ));
        $this->add(array(
            'name' => 'programmes_id',
            'type' => 'hidden',
        ));
        $this->add(array(
            'name' => 'programme_name',
            'type' => 'hidden',
        ));
        $this->add(array(
            'name' => 'backlog_semester',
            'type' => 'hidden',
        ));
        $this->add(array(
            'name' => 'module_title',
            'type' => 'hidden',
        ));
        $this->add(array(
            'name' => 'module_code',
            'type' => 'hidden',
        ));
        $this->add(array(
            'name' => 'module_credit',
            'type' => 'hidden',
        ));
        $this->add(array(
            'name' => 'weightage',
            'type' => 'hidden',
        ));
        $this->add(array(
            'name' => 'backlog_academic_year',
            'type' => 'hidden',
        ));
        $this->add(array(
            'name' => 'assessment',
            'type' => 'hidden',
        ));
        $this->add(array(
            'name' => 'backlog_in',
            'type' => 'hidden',
        ));
        $this->add(array(
            'name' => 'backlog_status',
            'type' => 'hidden',
        ));

        $this->add(array(
             'name' => 'moduleAssessmentCount',
              'type' => 'Hidden'  
         ));

        
                
        for($i=1; $i <= $this->moduleAssessmentCount; $i++)
        {
            $this->add(array(
            'name' => 'marks_'.$i,
            'type' => 'number',
            'options' => array(
                'class' => 'control-label',
                ),
                'attributes' =>array(
                    'class' => 'form-control',
                    'min' => 0.0,
                    'step' => 0.01,
                    'required' => false,
                ),
            
            ));
        }
        

		$this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
			 'options' => array(
                'csrf_options' => array(
					'timeout' => 600
                )
             )
         ));
                
		/*$this->add(array(
			'name' => 'submit',
            'type' => 'Submit',
            'options' => array(
                //'label' => '<i class="far fa-trash"></i> Update',
                'label_options' => array(
                    'disable_html_escape' => true,
                ),
            ),
			'attributes' => array(
				'value' => 'Update',
				'id' => 'submitbutton',
                'class' => 'btn btn-primary btn-xs',
				),
		));  */
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Update',
                'id' => 'submitbutton',
                'class' => 'btn btn-primary'
                ),
        ));     
	}
}
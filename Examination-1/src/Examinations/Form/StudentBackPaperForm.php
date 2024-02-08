<?php
namespace Examinations\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;


class StudentBackPaperForm extends Form
 {

    protected $student_ids;

     public function __construct($student_ids)
     {
        parent::__construct('studentbackpaper');

        $this->student_ids = $student_ids;
         
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
             'name' => 'backlog_semester',
              'type' => 'Hidden'  
         ));

         $this->add(array(
             'name' => 'backlog_academic_year',
              'type' => 'Hidden'  
         ));

         $this->add(array(
             'name' => 'programmes_id',
              'type' => 'Hidden'  
         ));

         $this->add(array(
            'name' => 'academic_modules_id',
            'type' => 'Hidden'
         ));
		 
		 $this->add(array(
             'name' => 'student_batch',
              'type' => 'Hidden'  
         ));

        foreach($this->student_ids as $key=>$value)
		{
			$this->add(array(
			  'name' => 'SE_'.$key,
			  'type'=> 'checkbox',
				 'options' => array(
					'class'=>'control-label',
					'use_hidden_element' => true,
					'checked_value' => '1',
					),
				'attributes' => array(
					'class' => 'flat',
					'value' => 'no',
				   // 'name' => 'table_records',
					//'required' => true
				),
		   ));
		  }
		  
		foreach($this->student_ids as $key=>$value)
		{
			$this->add(array(
			  'name' => 'CA_'.$key,
			  'type'=> 'checkbox',
				 'options' => array(
					'class'=>'control-label',
					'use_hidden_element' => true,
					'checked_value' => '1',
					),
				'attributes' => array(
					'class' => 'flat',
					'value' => 'no',
				   // 'name' => 'table_records',
					//'required' => true
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

       $this->add(array(
                'name' => 'submit',
                'type' => 'Submit',
                'attributes' => array(
                                    'class'=>'control-label',
                    'value' => 'Add Student Back Papers',
                    'id' => 'submitbutton',
                                    'class' => 'btn btn-success',
                    ),
                
                ));
     }
 }
<?php
namespace StudentAdmission\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;


class AddStudentSectionForm extends Form
 {

    protected $studentCount;
    protected $studentSection;

     public function __construct($studentCount, $studentSection)
     {
        parent::__construct('addstudentsection');

        $this->studentCount = $studentCount;
        $this->studentSection = $studentSection;
         
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


         // foreach($this->section_id as $id){
         /* $this->add(array(
           'name' => 'student_section_id',
            'type'=> 'Select',
             'options' => array(
                    'class'=>'control-label',
                    'disable_inarray_validator' => true,
                    'empty_option' => 'Select Section',
                    'value_options' => array(
                        '0' => 'Select'
                    )
                ),
             'attributes' => array(
                  'class' => 'form-control',
                    'value' => '0', // Set selected to 0
                    'required' => 'required'
             ),
         )); */
      //  }
         $this->add(array(
             'name' => 'programmes_id',
              'type' => 'Hidden'  
         ));
         $this->add(array(
             'name' => 'studentCount',
              'type' => 'Hidden'  
         ));

        for($i=1; $i <= $this->studentCount; $i++)
    {
        $this->add(array(
          'name' => 'student_section_id'.$i,
          'type'=> 'Select',
             'options' => array(
                    'class'=>'control-label',
                    'disable_inarray_validator' => true,
                    'empty_option' => 'Select Section',
                ),
             'attributes' => array(
                  'class' => 'form-control',
                    'required' => 'required',
                    'options' => $this->studentSection,
             ),
       ));
      }

       $this->add(array(
                'name' => 'submit',
                'type' => 'Submit',
                'attributes' => array(
                    'class'=>'control-label',
                    'value' => 'Submit',
                    'id' => 'submitbutton',
                    'class' => 'btn btn-success',
                    ),
                
                ));
		 
        $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
			 'options' => array(
                'csrf_options' => array(
                        'timeout' => 1200
                )
             )
         )); 
     }
 }
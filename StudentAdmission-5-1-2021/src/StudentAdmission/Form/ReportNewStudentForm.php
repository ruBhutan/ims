<?php
namespace StudentAdmission\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;


class ReportNewStudentForm extends Form
 {

    protected $studentCount;
    //protected $changeProgramme;

     public function __construct($studentCount)
     {
        parent::__construct('reportnewstudent');

        $this->studentCount = $studentCount;
        // $this->changeProgramme = $changeProgramme;
         
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
             'name' => 'organisation_id',
              'type' => 'Hidden'  
         ));

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
          'name' => 'student_'.$i,
          'type'=> 'checkbox',
             'options' => array(
                'class'=>'control-label',
                'use_hidden_element' => true,
                'checked_value' => '1',
                ),
            'attributes' => array(
                'class' => 'flat',
                'value' => '1',
                //'required' => true
            ),
       ));
      }

        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
			'options' => array(
                'csrf_options' => array(
                        'timeout' => 1200
                )
             )
        )); 

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
     }
 }
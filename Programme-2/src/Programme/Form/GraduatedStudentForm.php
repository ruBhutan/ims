<?php
namespace Programme\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;


class GraduatedStudentForm extends Form
 {

    protected $studentCount;

     public function __construct($studentCount)
     {
        parent::__construct('updatesemester');

        $this->studentCount = $studentCount;
         
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
             'name' => 'academic_year',
              'type' => 'Hidden'  
         ));

         $this->add(array(
             'name' => 'programme',
              'type' => 'Hidden'  
         ));


         $this->add(array(
             'name' => 'student_name',
              'type' => 'Hidden'  
         ));


         $this->add(array(
             'name' => 'student_id',
              'type' => 'Hidden'  
         ));


         $this->add(array(
            'name' => 'year',
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
                'value' => 'no',
               // 'name' => 'table_records',
                //'required' => true
            ),
       ));
      }


        $this->add(array(
          'name' => 'confirmation',
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
                'required' => true,
            ),
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
                                    'class'=>'control-label',
                    'value' => 'Update',
                    'id' => 'submitbutton',
                                    'class' => 'btn btn-success',
                    ),
                
                ));
     }
 }

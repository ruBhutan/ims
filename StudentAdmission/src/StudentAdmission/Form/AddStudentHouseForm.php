<?php
namespace StudentAdmission\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;


class AddStudentHouseForm extends Form
 {

    protected $studentCount;
    protected $studentHouse;

     public function __construct($studentCount, $studentHouse)
     {
        parent::__construct('addstudenthouse');

        $this->studentCount = $studentCount;
        $this->studentHouse = $studentHouse;
         
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
             'name' => 'studentCount',
              'type' => 'Hidden'  
         ));

        for($i=1; $i <= $this->studentCount; $i++)
    {
        $this->add(array(
          'name' => 'student_house_id'.$i,
          'type'=> 'Select',
             'options' => array(
                    'class'=>'control-label',
                    'disable_inarray_validator' => true,
                    'empty_option' => 'Select House',
                ),
             'attributes' => array(
                  'class' => 'form-control',
                    'required' => 'required',
                    'options' => $this->studentHouse,
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
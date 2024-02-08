<?php

namespace Examinations\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

class RepeatSemesterForm extends Form
{
    protected $moduleCount;

    public function __construct($moduleCount)
    {
         // we want to ignore the name passed
        parent::__construct();

        $this->moduleCount = $moduleCount;

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
            'name' => 'student_id',
             'type' => 'Hidden'  
        ));

        $this->add(array(
            'name' => 'semester',
             'type' => 'Hidden'  
        ));

        $this->add(array(
            'name' => 'module_code',
             'type' => 'Hidden'  
        ));

        $this->add(array(
            'name' => 'academic_module_id',
             'type' => 'Hidden'  
        ));

        $this->add(array(
            'name' => 'programmes_id',
             'type' => 'Hidden'  
        ));

        $this->add(array(
           'name' => 'semester_id',
           'type' => 'Hidden'
        ));

        $this->add(array(
           'name' => 'year',
           'type' => 'Hidden'
        ));

        $this->add(array(
            'name' => 'moduleCount',
             'type' => 'Hidden'  
        ));

        $this->add(array(
            'name' => 'status',
             'type' => 'Hidden'  
        ));
    
        for($i=1; $i <= $this->moduleCount; $i++)
        {
            $this->add(array(
                'name' => 'module_'.$i,
                'type'=> 'checkbox',
                   'options' => array(
                      'class'=>'control-label',
                      'use_hidden_element' => true,
                      'checked_value' => '1',
                      ),
                  'attributes' => array(
                      'class' => 'flat',
                      'value' => '0',
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
                'value' => 'Update',
                'id' => 'submitbutton',
                'class' => 'btn btn-success'
                ),
        ));
                
                
    }
}
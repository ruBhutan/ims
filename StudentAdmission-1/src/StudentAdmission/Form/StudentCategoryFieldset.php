<?php

namespace StudentAdmission\Form;

use StudentAdmission\Model\StudentAdmission;
use StudentAdmission\Model\StudentCategory;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;


class StudentCategoryFieldset extends Fieldset implements InputFilterProviderInterface
{
  public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('studentcategory');
    
    $this->setHydrator(new ClassMethods(false));
    $this->setObject(new StudentCategory());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));

         $this->add(array(
             'name' => 'id',
             'attributes' => array(
             'type' => 'Hidden',
              ),   
         ));
          
         $this->add(array(
            'name' => 'student_category',
            'type'=> 'Text',
            'options' => array(
                'class'=>'control-label',
             ),
             'attributes' => array(
                'class' => 'form-control ',
                'required' => 'required'
             ),
         ));
     
        $this->add(array(
           'name' => 'description',
            'type'=> 'TextArea',
            'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'rows'=> 5,
             ),
         ));

          $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'class'=>'control-label',
                    'value' => 'Add',
                    'id' => 'submitbutton',
                    'class' => 'btn btn-success',
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

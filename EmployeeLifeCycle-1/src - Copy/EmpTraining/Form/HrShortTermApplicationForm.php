<?php

namespace EmpTraining\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Form\Form;

class HrShortTermApplicationForm extends Form
{
    protected $traineeCount;

	public function __construct($traineeCount)
     {
        parent::__construct('shorttermtraining');

        $this->traineeCount = $traineeCount;
         
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

        for($i=1; $i <= $this->traineeCount; $i++)
        {
            $this->add(array(
              'name' => 'trainee_'.$i,
              'type'=> 'checkbox',
                 'options' => array(
                    'class'=>'control-label',
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    ),
                'attributes' => array(
                    'class' => 'flat',
                    'value' => 'no',
                    //'required' => true
                ),
            ));
        }
         
         
         $this->add(array(
           'name' => 'course_content_schedule',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
             ),
         ));
                 
         $this->add(array(
            'name' => 'submit',
             'type' => 'Submit',
                'attributes' => array(
                    'value' => 'Submit',
                    'id' => 'submitbutton',
                        'class' => 'btn btn-success',
                ),
          ));
          
          $this->add(array(
            'name' => 'search',
             'type' => 'Submit',
                'attributes' => array(
                    'value' => 'Search',
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
             'course_content_schedule' => array(
                'required' => true,
                'validators' => array(
                    array(
                    'name' => 'FileUploadFile',
                    ),
                ),
                'filters' => array(
                    array(
                    'name' => 'FileRenameUpload',
                    'options' => array(
                        'target' => './data/training',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => true
                        ),
                    ),
                ),
             ),
         );
     }
}
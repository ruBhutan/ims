<?php
namespace EmpTraining\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;

use Zend\InputFilter\InputFilterProviderInterface;


class HrShortTermApplicationForm extends Form implements InputFilterProviderInterface
 {

    protected $emp_workshop_details_id;
    //protected $changeProgramme;

     public function __construct($emp_workshop_details_id)
     {
        parent::__construct('shorttermtraining');

        $this->emp_workshop_details_id = $emp_workshop_details_id;
         
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
             'name' => 'traineeCount',
              'type' => 'Hidden'  
         ));


        foreach($this->emp_workshop_details_id as $id)
        { 
            $this->add(array(
              'name' => 'trainee_'.$id,
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
           'name' => 'acceptance_letter',
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
           'name' => 'award_letter',
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
            'value' => 'Submit',
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

             'acceptance_letter' => array(
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

             'award_letter' => array(
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

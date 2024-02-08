<?php

namespace UniversityAdministration\Form;

use UniversityAdministration\Model\NewsPaper;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class NewsPaperFieldset extends Fieldset implements InputFilterProviderInterface
{

    public function __construct()
     { 
         // we want to ignore the name passed
        parent::__construct('newspaper');
        
        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new NewsPaper());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));

         $this->add(array(
            'name' => 'newspaper_type',
            'type'=> 'Select',
             'options' => array(
                'empty_option' => 'Please Select the Newspaper',
                'disable_inarray_validator' => true,
                'class'=>'control-label',
                'value_options'=> array(
                    'Kuensel' => 'Kuensel',
                    'Bhutan_Times' => 'Bhutan Times',
                    'Business_Bhutan' => 'Business Bhutan'
                ),
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
             ),
         ));

         $this->add(array(
           'name' => 'newspaper_date',
            'type'=> 'Text',

             'options' => array(
                 'class'=>'control-label',     
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                'class' => 'form-control',
                'placeholder'=>'yyyy-mm-dd',
                'id' => 'single_cal3'
             ),
         ));

         $this->add(array(
           'name' => 'dzongkha_newspaper',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                 'class' => 'form-control ',
                 'id' => 'dzongkha_newspaper',
                 'required' => false
             ),
         ));

         $this->add(array(
           'name' => 'english_newspaper',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                 'class' => 'form-control ',
                 'id' => 'english_newspaper',
                 'required' => false
             ),
         ));

         $this->add(array(
           'name' => 'recorded_by',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true
             ),
         ));
           
         $this->add(array(
           'name' => 'staff_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
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

     }
     
     /**
      * @return array
      */
     public function getInputFilterSpecification()
     {
         return array(
            'dzongkha_newspaper' => array(
                'required' => true,
                'allow_empty' => true,
                'validators' => array(
                    array(
                        'name' => 'FileUploadFile',
                    ),
                    array(
                    'name' => 'Zend\Validator\File\Size',
                    'options' => array(
                      'min' => '10kB',
                      'max' => '2MB',
                    ),
                  ),
                  array(
                    'name' => 'Zend\Validator\File\Extension',
                    'options' => array(
                      'extension' => ['png','jpg','jpeg','pdf'],
                    ),
                  ),
                ),
                'filters' => array(
                    array(
                        'name' => 'FileRenameUpload',
                        'options' => array(
                            'target' => './data/newspaper',
                            'useUploadName' => true,
                            'useUploadExtension' => true,
                            'overwrite' => true,
                            'randomize' => true
                        ),
                    ),
                ),
            ),

            'english_newspaper' => array(
                'required' => true,
                'allow_empty' => true,
                'validators' => array(
                    array(
                        'name' => 'FileUploadFile',
                    ),
                    array(
                    'name' => 'Zend\Validator\File\Size',
                    'options' => array(
                      'min' => '10kB',
                      'max' => '2MB',
                    ),
                  ),
                  array(
                    'name' => 'Zend\Validator\File\Extension',
                    'options' => array(
                      'extension' => ['png','jpg','jpeg','pdf'],
                    ),
                  ),
                ),
                'filters' => array(
                    array(
                        'name' => 'FileRenameUpload',
                        'options' => array(
                            'target' => './data/newspaper',
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
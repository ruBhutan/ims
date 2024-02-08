<?php

namespace Programme\Form;

use Programme\Model\UploadModuleTutors;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;


class UploadModuleTutorsFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('uploadmoduletutors');
        
        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new UploadModuleTutors());
         
            $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
                ));
            $this->setAttribute('enctype','multipart/form-data');

            $this->add(array(
                'name' => 'id',
                'type' => 'Hidden'  
            ));

             $this->add(array(
                'name' => 'file_name',
                'type'=> 'file',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                    'id' => 'file_name',
                    'required' => 'required'
                ),
            ));

            $this->add(array(
                'name' => 'year',
                'type'=> 'Text',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                ),
            ));

            $this->add(array(
                'name' => 'organisation_id',
                'type'=> 'Text',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                ),
            ));

              
            $this->add(array(
                'name' => 'submit',
                'type' => 'submit',
                'attributes' => array(
                    'class'=>'control-label',
                    'value' => 'Upload Tutor Lists',
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
        'file_name' => array(
          'required' => true,
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
                'target' => './data/newstudent',
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

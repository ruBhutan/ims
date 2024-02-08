<?php

namespace EmpPromotion\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class EmpPromotionForm extends Form implements InputFilterProviderInterface
{
    protected $pmsDetails;

	public function __construct($pmsDetails)
     {
        parent::__construct();

        $this->pmsDetails = $pmsDetails; 
         
         $this
             ->setAttribute('method', 'post')
             ->setAttribute('enctype','multipart/form-data')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
         
        /*$this->add(array(
             'type' => 'EmpPromotion\Form\EmpPromotionFieldset',
             'options' => array(
                 'use_as_base_fieldset' => true,
             ),
         )); */

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
         
         $this->add(array(
             'name' => 'promotion_status',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'promotion_type',
           'type'=> 'text',
           'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
         
         $this->add(array(
           'name' => 'years_service_from_appointment',
           'type'=> 'text',
           'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
         
         $this->add(array(
           'name' => 'years_service_from_promotion',
           'type'=> 'text',
           'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
         
         $this->add(array(
           'name' => 'security_clearance_no',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
             ),
         ));
         
         $this->add(array(
           'name' => 'audit_clearance_no',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true
             ),
         ));
         
         $this->add(array(
           'name' => 'other_certificate_description',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
         
         $this->add(array(
           'name' => 'security_clearance_file',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                    'id' => 'security_clearance_file',
                    'required' => true
             ),
         ));
         
         $this->add(array(
           'name' => 'audit_clearance_file',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'id' => 'audit_clearance_file',
                  'required' => true
             ),
         ));
         
         $this->add(array(
           'name' => 'other_certificate_file',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                    'id' => 'other_certificate_file',
             ),
         ));
                 
          $this->add(array(
           'name' => 'meritorious_promotion_file',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                    'id' => 'meritorious_promotion_file',
                    'required' => true
             ),
         ));
         
         $this->add(array(
           'name' => 'employee_details_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));

      $this->add(array(
           'name' => 'emp_promotion_id',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));

        foreach($this->pmsDetails as $key=>$value) 
       {
        $this->add(array(
           'name' => 'performance_year'.$key,
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'required' => true,
             ),
         ));
       }

        foreach($this->pmsDetails as $key=>$value)
        { 
            $this->add(array(
             'name' => 'performance_rating'.$key,
              'type'=> 'text',
               'options' => array(
                   'class'=>'control-label',
               ),
               'attributes' => array(
                    'class' => 'form-control ',
                    'required' => true,
               ),
           ));
        }

         foreach($this->pmsDetails as $key=>$value) 
         {
            $this->add(array(
             'name' => 'performance_category'.$key,
              'type'=> 'text',
               'options' => array(
                   'class'=>'control-label',
               ),
               'attributes' => array(
                    'class' => 'form-control',
                    'required' => true,
               ),
           ));
         }

          $this->add(array(
         'name' => 'supporting_file',
          'type'=> 'file',
           'options' => array(
               'class'=>'control-label',
           ),
           'attributes' => array(
                'class' => 'form-control',
                'required' => false,
           ),
       ));

          $this->add(array(
         'name' => 'performance_detail_file1',
          'type'=> 'file',
           'options' => array(
               'class'=>'control-label',
           ),
           'attributes' => array(
                'class' => 'form-control',
                'required' => false,
           ),
       ));


          $this->add(array(
         'name' => 'performance_detail_file2',
          'type'=> 'file',
           'options' => array(
               'class'=>'control-label',
           ),
           'attributes' => array(
                'class' => 'form-control',
                'required' => false,
           ),
       ));


          $this->add(array(
         'name' => 'performance_detail_file3',
          'type'=> 'file',
           'options' => array(
               'class'=>'control-label',
           ),
           'attributes' => array(
                'class' => 'form-control',
                'required' => false,
           ),
       ));

          $this->add(array(
         'name' => 'performance_detail_file4',
          'type'=> 'file',
           'options' => array(
               'class'=>'control-label',
           ),
           'attributes' => array(
                'class' => 'form-control',
                'required' => false,
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
             'name' => array(
                 'required' => false,
             ),
             'security_clearance_file' => array(
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
                        'target' => './data/promotion',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => true
                        ),
                    ),
                ),
             ),
             'audit_clearance_file' => array(
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
                        'target' => './data/promotion',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => true
                        ),
                    ),
                ),
             ),
             'other_certificate_file' => array(
                'required' => false,
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
                        'target' => './data/promotion',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => true
                        ),
                    ),
                ),
             ),
               
               'meritorious_promotion_file' => array(
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
                        'target' => './data/promotion',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => true
                        ),
                    ),
                ),
             ),

       'supporting_file' => array(
        'required' => false,
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
            'target' => './data/promotion',
            'useUploadName' => true,
            'useUploadExtension' => true,
            'overwrite' => true,
            'randomize' => true
            ),
          ),
        ),
       ),


       //Following are the temporary variables to store the supporting files and upload in supporting_file
       'performance_detail_file1' => array(
        'required' => false,
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
            'target' => './data/promotion',
            'useUploadName' => true,
            'useUploadExtension' => true,
            'overwrite' => true,
            'randomize' => true
            ),
          ),
        ),
       ),


       'performance_detail_file2' => array(
        'required' => false,
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
            'target' => './data/promotion',
            'useUploadName' => true,
            'useUploadExtension' => true,
            'overwrite' => true,
            'randomize' => true
            ),
          ),
        ),
       ),


       'performance_detail_file3' => array(
        'required' => false,
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
            'target' => './data/promotion',
            'useUploadName' => true,
            'useUploadExtension' => true,
            'overwrite' => true,
            'randomize' => true
            ),
          ),
        ),
       ),

       'performance_detail_file4' => array(
        'required' => false,
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
            'target' => './data/promotion',
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

<?php

namespace EmpTravelAuthorization\Form;

use EmpTravelAuthorization\Model\EmpTravelAuthorization;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class UpdateTravelAuthorizationFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
        parent::__construct('travelauthorization');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new EmpTravelAuthorization());
         
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
             'name' => 'organisation_id',
             'attributes' => array(
             'type' => 'Hidden',
              ),   
         ));
		 
		 $this->add(array(
             'type' => 'text',
             'name' => 'employee_details_id', 
             'attributes' => array(
                   'type' => 'text',
                 'class' => 'form-control'
              ),   
         ));         
      
           $this->add(array(
           'name' => 'travel_auth_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                  'required' => true,
                  'id' => 'single_cal2'
             ),
         ));
         
         $this->add(array(
            'name' => 'no_of_days', 
             'type' => 'number',
             'options' => array(
                'class'=>'control-label',
                      'value_options' => array(
                 ),
                     ),
             'attributes' => array(
                 'class' => 'form-control',
                 'required' => true,
                 'min' => 0,
              ),   
         ));


         /*$this->add(array(
           'name' => 'start_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                  'required' => true,
                  'id' => 'single_cal1'
             ),
         ));


         $this->add(array(
           'name' => 'end_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
                      'value_options' => array(
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                  'required' => true,
                  'id' => 'single_cal4'
             ),
         ));*/


         $this->add(array(
            'name' => 'start_date',
            'type'=>'Text',
            'attributes' => array(
                'class' => 'form-control',
                
            ),
            'options' => array(
                  ),
        )); 

        $this->add(array(
            'name' => 'end_date',
            'type'=>'Text',
            'attributes' => array(
                'class' => 'form-control',
                
            ),
            'options' => array(
                  ),
        )); 


          $this->add(array(
            'name' => 'estimated_expenses', 
             'type' => 'number',
             'options' => array(
                'class'=>'control-label',
                      'value_options' => array(
                 ),
                     ),
             'attributes' => array(
                 'class' => 'form-control',
                 'required' => true,
                 'min' => 0.0,
                  'step' => 0.01
              ),   
         ));


          $this->add(array(
            'name' => 'advance_required', 
             'type' => 'number',
             'options' => array(
                'class'=>'control-label',
                      'value_options' => array(
                 ),
                     ),
             'attributes' => array(
                 'class' => 'form-control',
                 'required' => true,
                 'min' => 0.0,
                  'step' => 0.01
              ),   
         ));


          $this->add(array(
            'name' => 'advance_sanctioned', 
             'type' => 'Text',
             'options' => array(
                'class'=>'control-label',
                      'value_options' => array(
                 ),
                     ),
             'attributes' => array(
                 'class' => 'form-control',
                 'required' => false,
                 'min' => 0.0,
                  'step' => 0.01
              ),   
         ));
		 
		 
		 $this->add(array(
             'name' => 'tour_status',
             'type'=>'Text',
             'attributes' => array(
                 'class' => 'form-control',
                 
             ),
             'options' => array(
                   ),
         )); 

     $this->add(array(
           'name' => 'officiating_staff',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Officiating',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));


         $this->add(array(
            'name' => 'applied_by_id',
             'type'=> 'text',
              'options' => array(
                  'class'=>'control-label',
              ),
              'attributes' => array(
                   'class' => 'form-control ',
              ),
          ));

		 
		 $this->add(array(
             'name' => 'remarks',
             'type'=>'Textarea',
             'attributes' => array(
                 'class' => 'form-control',
                 
             ),
             'options' => array(
                'rows' => 2,
                   ),
         ));
		 
		 $this->add(array(
             'name' => 'purpose_of_tour',
             'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
                  'rows' => 2,
				  'required' => true
             ),
         ));
		          
         $this->add(array(
             'name' => 'related_document_file',
             'type' => 'file',
             'options' => array(
                 'class' => 'form-control',
                 'value' => 'Choose File',
              ),   
             'attributes' => array(
                  'class' => 'form-control',
                    'id' => 'related_document_file',
             ),
         ));
		 
		 $this->add(array(
				'name' => 'approve',
				'type' => 'Submit',
				'attributes' => array(
                    'class'=>'control-label',
					'value' => 'Approve',
					'id' => 'Approve',
                    'class' => 'btn btn-success',
					),
				));
			
		$this->add(array(
			'name' => 'reject',
			'type' => 'Submit',
			'attributes' => array(
				'class'=>'control-label',
				'value' => 'Reject',
				'id' => 'Reject',
				'class' => 'btn btn-danger',
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
             'name' => array(
                 'required' => false,
             ),
             'related_document_file' => array(
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
                        'target' => './data/tourdocuments',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => true
                        ),
                    ),
                ),
             ),

             'officiating_staff' => array(
                'required' => false,
                ),
             
         );
     }
}
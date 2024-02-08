<?php

namespace Programme\Form;

use Programme\Model\Programme;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ProgrammeFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('programme');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new Programme());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'programme_name',
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
           'name' => 'programme_leader',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Programme Leader',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true
             ),
         ));
           
         $this->add(array(
           'name' => 'programme_approval_date',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control has-feedback-left',
				  'id' => 'single_cal3',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'programme_apmr_date',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control has-feedback-left',
				  'id' => 'single_cal4',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'programme_ccr_date',
            'type'=> 'text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control has-feedback-left',
				  'id' => 'single_cal2',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'mode_of_study',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Mode of Study',
				 'class' => 'control-label',
				 'value_options' => array(
				 		'Full Time on Campus' => 'Full Time on Campus',
						'Mixed Mode' => 'Mixed Mode',
						'Part-Time' => 'Part-Time'
				 )
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'programme_duration',
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
           'name' => 'programme_code',
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
           'name' => 'duration_units',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Duration Year/Month',
				 'class'=>'control-label',
				 'value_options' => array(
				 	'months' => 'months',
					'years' => 'years'
				 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'academic_session_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select Session Start',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'programme_approved_dpd',
            'type'=> 'file',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'programme_description',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'rows' => 3
             ),
         ));
		 
		 $this->add(array(
           'name' => 'status',
           'type'=> 'select',
             'options' => array(
                 'value_options'=> array(
                        'Active' => 'Active',
                        'In Active' => 'In Active',
						'Phasing Out' => 'Phasing Out'
                 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'organisation_id',
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
					'value' => 'Add New Programme',
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
		  
		  
		  $this->add(array(
			'name' => 'update',
			 'type' => 'Submit',
				'attributes' => array(
					'value' => 'Update Programme',
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
			 'programme_approved_dpd' => array(
			 	'required' => false,
				'allow_empty' => true,
				'validators' => array(
					array(
					'name' => 'FileUploadFile',
					),
				),
				'filters' => array(
					array(
					'name' => 'FileRenameUpload',
					'options' => array(
						'target' => './data/programmes',
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

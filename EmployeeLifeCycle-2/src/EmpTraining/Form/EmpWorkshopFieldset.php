<?php

namespace EmpTraining\Form;

use EmpTraining\Model\WorkshopDetails;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class EmpWorkshopFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('employeeworkshop');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new WorkshopDetails());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'proposing_agency',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
             'name' => 'hrd_type',
              'type' => 'Hidden'  
         ));
          		 
		 $this->add(array(
           'name' => 'type',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
        				 'empty_option' => 'Please Select Training Type',
        				 'disable_inarray_validator' => true,
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required',
             ),
         ));
		 		 		 
		 $this->add(array(
           'name' => 'title',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'institute_name',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'institute_location',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'institute_country',
            'type'=> 'Select',
             'options' => array(
                 'empty_option' => 'Select Source of Country',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
                     ),
             'attributes' => array(
                  'class' => 'form-control',
				          'required' => true
                ),
              ));
		 	 
        		 $this->add(array(
                   'name' => 'workshop_start_date',
                    'type'=> 'text',
                     'options' => array(
                         'class'=>'control-label',
                     ),
                     'attributes' => array(
                          'class' => 'form-control glyphicon glyphicon-calendar fa fa-calendar',
        				  'id' => 'reservation',
        				  'required' => true
                     ),
                 ));
		 
		 /*
		 $this->add(array(
           'name' => 'workshop_end_date',
            'type'=> 'date',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'required' => true
             ),
         ));
		 */
		 
		 $this->add(array(
           'name' => 'source_of_funding',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Select Source of Funding',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true
             ),
         ));
		 
		 $this->add(array(
           'name' => 'remarks',
            'type'=> 'Textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
				  'rows' => 3
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
         );
     }
}
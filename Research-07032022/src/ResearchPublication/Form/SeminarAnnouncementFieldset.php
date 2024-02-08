<?php

namespace ResearchPublication\Form;

use ResearchPublication\Model\SeminarAnnouncement;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class SeminarAnnouncementFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('seminarannouncement');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new SeminarAnnouncement());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'seminar_title',
            'type'=> 'Text',
             'options' => array(
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'placeholder' => 'Seminar Title',
                  'required' => true,
             ),
         ));

         $this->add(array(
            'name' => 'seminar_location',
             'type'=> 'Text',
              'options' => array(
                  'class'=>'control-label',
              ),
              'attributes' => array(
                   'class' => 'form-control ',
                   'placeholder' => 'Seminar Location',
                   'required' => true,
              ),
          ));

          $this->add(array(
            'name' => 'seminar_country',
             'type'=> 'Select',
              'options' => array(
                  'empty_option' => 'Please Select Seminar Country',
                  'disable_inarray_validator' => true,
                  'class'=>'control-label',
              ),
              'attributes' => array(
                   'class' => 'form-control ',
                   'required' => true,
              ),
          ));
		 
		 $this->add(array(
           'name' => 'seminar_start_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                  'required' => true,
                  'id' => 'single_cal3'
             ),
         ));
		 
		 $this->add(array(
           'name' => 'seminar_end_date',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control fa fa-calendar-o',
                  'required' => 'required',
                  'id' => 'single_cal4'
             ),
         ));

         $this->add(array(
            'name' => 'funding_agency',
             'type'=> 'Select',
              'options' => array(
                  'empty_option' => 'Please Select Funding Source',
                  'disable_inarray_validator' => true,
                  'class'=>'control-label',
              ),
              'attributes' => array(
                   'class' => 'form-control ',
                   'required' => true,
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
            'name' => 'announced_by',
             'type'=> 'Text',
              'options' => array(
                  'class'=>'control-label',
              ),
              'attributes' => array(
                   'class' => 'form-control ',
              ),
          ));

          $this->add(array(
            'name' => 'remarks',
             'type'=> 'Textarea',
              'options' => array(
                  'class'=>'control-label',
              ),
              'attributes' => array(
                   'class' => 'form-control ',
                   'rows' => 5,
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
<?php

namespace Planning\Form;

use Planning\Model\SuccessIndicatorTrend;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class SuccessIndicatorTrendFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('successindicatortrend');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new SuccessIndicatorTrend());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'year_one_projected',
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
           'name' => 'year_one_achieved',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'year_two_projected',
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
           'name' => 'year_two_achieved',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'year_three_projected',
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
           'name' => 'year_three_achieved',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'year_four_projected',
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
           'name' => 'year_four_achieved',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'year_five_projected',
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
           'name' => 'year_five_achieved',
            'type'=> 'Text',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'awpa_activities_id',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select a Success Indicator',
				 'disable_inarray_validator' => true,
				 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
                  'required' => true
             ),
         ));
         
                 $this->add(array(
           'name' => 'employee_details_id',
            'type'=> 'select',
             'options' => array(
                 'class'=>'control-label',
				 'disable_inarray_validator' => true,
				 'value_options' => array(
				 )
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
             'name' => array(
                 'required' => false,
             ),
         );
     }
}
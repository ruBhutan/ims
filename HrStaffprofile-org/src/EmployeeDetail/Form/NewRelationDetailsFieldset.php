<?php
//used when adding new employes
//this fieldset consists of education details, work experience, publications and trainings

namespace EmployeeDetail\Form;

//use EmployeeDetail\Model\NewEmployeeDetail;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class NewRelationDetailsFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('employeeworkfields');
		
		$this->setHydrator(new ClassMethods(false));
		//Model not used as we are going to extract the values at the controller
		//$this->setObject(new NewEmployeeDetail());
         
         $this->setAttributes(array(
                    'class' => 'form-group',
                ));
	 
		 $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
		 
		 $this->add(array(
           'name' => 'relation_type',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Relation Type',
				 'class'=>'control-label',
				 'value_options' => array(
				 		'Father' => 'Father',
						'Mother' => 'Mother',
						'Spouse' => 'Spouse',
						'Son' => 'Son',
						'Daughter' => 'Daughter',
				 ),
             ),
             'attributes' => array(
                  'class' => 'control-label col-md-3 col-sm-3 col-xs-12',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'relation_name',
            'type'=> 'Text',
             'attributes' => array(
				  'class' => 'control-label col-md-3 col-sm-3 col-xs-12',
				  'placeholder' => 'Relation Name ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'relation_cid',
            'type'=> 'Text',
             'attributes' => array(
				  'class' => 'control-label col-md-3 col-sm-3 col-xs-12',
				  'placeholder' => 'CID No ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'relation_nationality',
            'type'=> 'Text',
             'attributes' => array(
				  'class' => 'control-label col-md-3 col-sm-3 col-xs-12',
				  'placeholder' => 'Nationality',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'relation_house_no',
            'type'=> 'Text',
             'attributes' => array(
				  'class' => 'control-label col-md-3 col-sm-3 col-xs-12',
				  'placeholder' => 'House No ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'relation_thram_no',
            'type'=> 'Text',
             'attributes' => array(
				  'class' => 'control-label col-md-3 col-sm-3 col-xs-12',
				  'placeholder' => 'Thram No ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'relation_village',
            'type'=> 'Text',
             'attributes' => array(
				  'class' => 'control-label col-md-3 col-sm-3 col-xs-12',
				  'placeholder' => 'Village Name ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'relation_gewog',
            'type'=> 'Text',
             'attributes' => array(
				  'class' => 'control-label col-md-3 col-sm-3 col-xs-12',
				  'placeholder' => 'Gewog ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'relation_dzongkhag',
            'type'=> 'Text',
             'attributes' => array(
				  'class' => 'control-label col-md-3 col-sm-3 col-xs-12',
				  'placeholder' => 'Dzongkhag ',
             ),
         ));
		 		 		 
		 $this->add(array(
           'name' => 'relation_occupation',
            'type'=> 'Text',
             'attributes' => array(
                  'class' => 'control-label col-md-3 col-sm-3 col-xs-12',
				  'placeholder' => 'Occupation',
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
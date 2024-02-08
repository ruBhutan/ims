<?php

namespace UniversityResearch\Form;

use UniversityResearch\Model\ResearchRecommendation;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ResearchRecommendationFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('researchrecommendation');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new ResearchRecommendation());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));
          
         $this->add(array(
           'name' => 'application_status',
            'type'=> 'select',
             'options' => array(
                 'empty_option' => 'Please Select Grant Type',
				 'class'=>'control-label',
				 'value_options' => array(
                    'Approved' => 'Approve',
                    'Resubmit/ Rejected' => 'Resubmit/ Reject',
				  	/*'Accept as is, all ratings are 3 or better' => 'Accept as is, all ratings are 3 or better',
					'Accept Subject to identified limited revisions' => 'Accept Subject to identified limited revisions',
					'Resubmit following substantial revision' => 'Resubmit following substantial revision',
					'Reject Research' => 'Reject Research'*/
				 ),
             ),
             'attributes' => array(
                  'class' => 'form-control ',
             ),
         ));
		 
		 $this->add(array(
           'name' => 'college_endorsement',
            'type'=> 'checkbox',
             'options' => array(
                'class'=>'control-label',
        				'use_hidden_element' => true,
        				'checked_value' => 'no',
        				),
        			'attributes' => array(
        				'class' => 'flat',
        				'value' => 'yes',
        				'required' => true
        			)
  		    ));
		
		$this->add(array(
           'name' => 'president_endorsement',
            'type'=> 'checkbox',
             'options' => array(
                'class'=>'control-label',
        				'use_hidden_element' => true,
        				'checked_value' => 'no',
        				),
        			'attributes' => array(
        				'class' => 'flat',
        				'value' => 'yes',
        				'required' => true
        			)
        		));
		 
		 $this->add(array(
           'name' => 'remarks',
            'type'=> 'textarea',
             'options' => array(
                 'class'=>'control-label',
             ),
             'attributes' => array(
                  'class' => 'form-control ',
				  'rows' => 3,
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
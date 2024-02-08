<?php

namespace Review\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;

class AdministrativeReviewForm extends Form
{
	protected $appraisalCount;
	
	public function __construct($appraisalCount)
     {
        parent::__construct('appraisals');
        
		$this->appraisalCount = $appraisalCount;
				
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;
        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));
         
        foreach($this->appraisalCount as $count)
		{
			$this->add(array(
			   'name' => 'rating'.$count,
				'type'=> 'select',
				 'options' => array(
					 'class'=>'control-label',
					 'value_options' => array(
					 		'1' => 'Needs Improvement',
							'2' => 'Good',
							'3' => 'Very Good',
							'4' => 'Excellent',
					 ),
				 ),
				 'attributes' => array(
					  'class' => 'form-control',
				 ),
			 ));			
		}
        $this->add(array(
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
             'options' => array(
                'csrf_options' => array(
                        'timeout' => 600
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
}

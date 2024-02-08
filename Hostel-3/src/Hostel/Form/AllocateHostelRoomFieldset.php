<?php

namespace Hostel\Form;

use Hostel\Model\AllocateHostelRoom;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class AllocateHostelRoomFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
     {
         // we want to ignore the name passed
        parent::__construct('allocatehostelroom');
		
		$this->setHydrator(new ClassMethods(false));
		$this->setObject(new AllocateHostelRoom());
         
         $this->setAttributes(array(
                    'class' => 'form-horizontal form-label-left',
          ));

         $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));

         $this->add(array(
            'name' => 'hostel_rooms_id',
             'type'=> 'Select',
              'options' => array(
                  'class'=>'control-label',
                  'disable_inarray_validator' => true,
                  'empty_option' => 'Please Select Unallocated Room',
                  'value_options' => array(
                     )
              ),
              'attributes' => array(
                   'class' => 'form-control',
                   'required' => 'required',
              ),
            ));


            $this->add(array(
                'name' => 'student_id',
                 'type'=> 'Select',
                  'options' => array(
                      'class'=>'control-label',
                      'disable_inarray_validator' => true,
                      'empty_option' => 'Please Select Unallocated Student',
                      'value_options' => array(
                         )
                  ),
                  'attributes' => array(
                       'class' => 'form-control',
                       'required' => 'required',
                  ),
                  ));

            $this->add(array(
                    'name' => 'year',
                    'type' => 'Text',
                    'options' => array(
                        'class' => 'control-label',
                        ),
                    'attributes' =>array(
                        'class' => 'form-control',
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
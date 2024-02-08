<?php
namespace StudentAdmission\Form;

use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Zend\Form\Form;
use Zend\Session\Container;


class ChangeProgrammeForm extends Form
 {

    protected $studentCount;
    protected $serviceLocator;
    //protected $changeProgramme;

     public function __construct($studentCount, $serviceLocator = null, array $options = [])
     {
        parent::__construct('ajax', $options);

        $this->studentCount = $studentCount;
        $this->serviceLocator = $serviceLocator;
        $this->ajax = $serviceLocator;
        $this->ajax = $options;
        // $this->changeProgramme = $changeProgramme;
         
         $this
             ->setAttribute('method', 'post')
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setInputFilter(new InputFilter())
         ;

        
        $this->setAttributes(array(
            'class' => 'form-horizontal form-label-left',
        ));

        $this->add(array(
             'name' => 'id',
              'type' => 'Hidden'  
         ));

        $this->add(array(
             'name' => 'programme',
              'type' => 'Hidden'  
         ));

         $this->add(array(
             'name' => 'studentName',
              'type' => 'Hidden'  
         ));

         $this->add(array(
            'name' => 'studentId',
            'type' => 'Hidden',
         ));

         $this->add(array(
             'name' => 'year',
              'type' => 'Hidden'  
         ));

         $this->add(array(
             'name' => 'semester',
              'type' => 'Hidden'  
         ));
         $this->add(array(
             'name' => 'section',
              'type' => 'Hidden'  
         ));

         $this->add(array(
             'name' => 'studentCount',
              'type' => 'Hidden'  
         ));

        for($i=1; $i <= $this->studentCount; $i++)
    {
        $this->add(array(
          'name' => 'student_'.$i,
          'type'=> 'checkbox',
             'options' => array(
                'class'=>'control-label',
                'use_hidden_element' => true,
                'checked_value' => '1',
                ),
            'attributes' => array(
                'class' => 'flat',
                'value' => 'no',
                //'required' => true
            ),
       ));
      }

        $this->add(array(
            'name' => 'changed_programme',
            'type'=> 'Select',
            'options' => array(
                'class'=>'control-label',
                'disable_inarray_validator' => true,
                'empty_option' => 'Please Select Programme',
                'value_options' => array(
                    '0' => 'Select'
                )
            ),
           'attributes' => array(
                'class' => 'form-control',
                'value' => '0', // Set selected to 0
                'required' => 'required'
            ),
        ));

        $this->add(array(
            'name' => 'year_id',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Year',
                 'disable_inarray_validator' => true,
                 'class'=>'control-label',
                ),
            'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectYear',
                  'options' => $this->createProgrammeYear(),
                  'required' => 'required'
             ),
        ));


        $this->add(array(
            'name' => 'semester_id',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Semester',
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                ),
            'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectSemester',
                  'options' => array(),
                  'required' => 'required'
             ),
        ));
        $this->add(array(
            'name' => 'changed_session',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Session',
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                ),
            'attributes' => array(
                  'class' => 'form-control ',
                  'id' => 'selectSession',
                  'options' => array(),
                  'required' => 'required'
             ),
        ));

        $this->add(array(
            'name' => 'academic_year',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Select Academic Year',
                 'class'=>'control-label',
                 'disable_inarray_validator' => true,
                ),
            'attributes' => array(
                  'class' => 'form-control ',
                  'required' => 'required'
             ),
        ));


        $this->add(array(
                'name' => 'updated_date',
                'type'=> 'Date',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
                ),
            ));

        $this->add(array(
                'name' => 'updated_by',
                'type'=> 'Text',
                'options' => array(
                    'class'=>'control-label',
                ),
                'attributes' => array(
                    'class' => 'form-control ',
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
             'type' => 'Zend\Form\Element\Csrf',
             'name' => 'csrf',
			 'options' => array(
                'csrf_options' => array(
                        'timeout' => 1200
                )
             )
         )); 

       $this->add(array(
                'name' => 'submit',
                'type' => 'Submit',
                'attributes' => array(
                    'class'=>'control-label',
                    'value' => 'Submit',
                    'id' => 'submitbutton',
                    'class' => 'btn btn-success',
                    ),
                
                ));
     }


     private function createProgrammeYear()
    {
        // You probably want to get those from the Database as in previous example
        $dbAdapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, year FROM programme_year';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();

       foreach ($result as $res) {
            $selectData[$res['id']] = $res['year'];
        }
        return $selectData;
    }

 }
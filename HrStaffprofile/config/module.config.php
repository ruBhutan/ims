<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'EmployeeDetail\Mapper\EmployeeMapperInterface' => 'EmployeeDetail\Factory\ZendDbSqlMapperFactory',
			'EmployeeDetail\Service\EmployeeDetailServiceInterface'=> 'EmployeeDetail\Factory\EmployeeServiceFactory',
			'HrSettings\Mapper\HrSettingsMapperInterface' => 'HrSettings\Factory\ZendDbSqlMapperFactory',
			'HrSettings\Service\HrSettingsServiceInterface'=> 'HrSettings\Factory\HrSettingsServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'EmployeeDetail' => 'EmployeeDetail\Factory\EmployeeDetailControllerFactory',
			'HrSettings' => 'HrSettings\Factory\HrSettingsControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
			 'job' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/job[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'job',
					),
				),
			),
			'otherconfig' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/otherconfig[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'otherConfiguration',
					),
				),
			),
			'addresearchcategory' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addresearchcategory[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addResearchCategory',
					),
				),
			),
			'editresearchcategory' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editresearchcategory[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editResearchCategory',
					),
				),
			),
			'addstudylevel' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addstudylevel[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addStudyLevel',
					),
				),
			),
			'editstudylevel' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editstudylevel[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editStudyLevel',
					),
				),
			),
			'addfunding' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addfunding[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addFunding',
					),
				),
			),
			'editfunding' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editfunding[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editFunding',
					),
				),
			),
			'addemploymentstatus' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addemploymentstatus[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addEmploymentStatus',
					),
				),
			),
			'editemploymentstatus' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editemploymentstatus[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editEmploymentStatus',
					),
				),
			),
			'addoccupationalgroup' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addoccupationalgroup[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addOccupationalGroup',
					),
				),
			),
			'editoccupationalgroup' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editoccupationalgroup[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editOccupationalGroup',
					),
				),
			),
			'addpayscale' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addpayscale[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addPayScale',
					),
				),
			),
			'editpayscale' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editpayscale[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editPayScale',
					),
				),
			),
			'addpositioncategory' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addpositioncategory[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addPositionCategory',
					),
				),
			),
			'editpositioncategory' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editpositioncategory[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editPositionCategory',
					),
				),
			),
			'addpositionlevel' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addpositionlevel[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addPositionLevel',
					),
				),
			),
			'editpositionlevel' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editpositionlevel[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editPositionLevel',
					),
				),
			),
			'addpositiontitle' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addpositiontitle[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addPositionTitle',
					),
				),
			),
			'editpositiontitle' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editpositiontitle[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editPositionTitle',
					),
				),
			),
			'addrentallowance' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addrentallowance[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addRentAllowance',
					),
				),
			),
			
			'editrentallowance' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editrentallowance[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editRentAllowance',
					),
				),
			),
			'adduniversityallowance' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/adduniversityallowance[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addUniversityAllowance',
					),
				),
			),
			'edituniversityallowance' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/edituniversityallowance[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editUniversityAllowance',
					),
				),
			),
			'addteachingallowance' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addteachingallowance[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addTeachingAllowance',
					),
				),
			),
			'editteachingallowance' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editteachingallowance[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editTeachingAllowance',
					),
				),
			),
			'addjobcategory' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addjobcategory[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addjobcategory',
					),
				),
			),
			'editjobcategory' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editjobcategory[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editjobcategory',
					),
				),
			),

			'hrothersetting' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/hrothersetting[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'hrOtherSetting',
					),
				),
			),
			'addempawardcategory' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addempawardcategory[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addEmpAwardCategory',
					),
				),
			),
			'editempawardcategory' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editempawardcategory[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editEmpAwardCategory',
					),
				),
			),
			'communityservicecategory' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/communityservicecategory[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addEmpCommunityServiceCategory',
					),
				),
			),
			'editcommunityservicecategory' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editcommunityservicecategory[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editEmpCommunityServiceCategory',
					),
				),
			),
			'addempcontributioncategory' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addempcontributioncategory[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addEmpContributionCategory',
					),
				),
			),
			'editempcontributioncategory' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editempcontributioncategory[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editEmpContributionCategory',
					),
				),
			),
			'addempresponsibilitycategory' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addempresponsibilitycategory[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'addEmpResponsibilityCategory',
					),
				),
			),
			'editempresponsibilitycategory' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editempresponsibilitycategory[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'HrSettings',
						'action' =>'editEmpResponsibilityCategory',
					),
				),
			),
			
			'addempdetail' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addempdetail[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addempdetail',
					),
				),
			),
			'employeelist' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/employeelist[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'employeeList',
					),
				),
			),

			'addempjobprofile' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addempjobprofile[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addEmpJobProfile',
					),
				),
			),

			'editempjobprofile' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editempjobprofile[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
						'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' => 'editEmpJobProfile',
					),

				),
			),


			'empjobprofile' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/empjobprofile[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' => 'empJobProfile',
					),
				),
			),

			'updateempinitialdetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/updateempinitialdetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'updateEmpInitialDetails',
					),
				),
			),
			'empeducationalbackground' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/empeducationalbackground[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empeducationalbackground',
					),
				),
			),
			'empeducation' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/empeducation[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empEducation',
					),
				),
			),
			'addempeducation' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addempeducation[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addEmployeeEducation',
					),
				),
			),
			'viewempeducation' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewempeducation[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'viewEmployeeEducation',
					),
				),
			),
			'editemployeeeducation' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editemployeeeducation[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'editEmployeeEducation',
					),
				),
			),
			'deleteemployeeeducation' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/deleteemployeeeducation[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'deleteEmployeeEducation',
					),
				),
			),
			'downloadempeducationevidencefile' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/downloadempeducationevidencefile[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'downloadEmpEducationEvidenceFile',
					),
				),
			),
        	'emppersonaldetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/emppersonaldetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empPersonalDetails',
					),
				),
			),

			'editemppersonaldetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editemppersonaldetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'editEmployeePersonalDetails',
					),
				),
			),

            'emppermanentaddress' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/emppermanentaddress[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empPermanentAddress',
					),
				),
			),
			'editpermanentaddress' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editpermanentaddress[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'editEmployeePermanentAddress',
					),
				),
			),

			'editempemploymentdetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editempemploymentdetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'editEmployeeEmploymentDetailsAction',
					),
				),
			),

			'empprofilepicture' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/empprofilepicture[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empProfilePicture',
					),
				),
			),
			'addempprofilepicture' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addempprofilepicture[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addEmployeeProfilePicture',
					),
				),
			),
			'emppositionlevel' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/emppositionlevel[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empPositionLevel',
					),
				),
			),
			'addemppositionlevel' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addemppositionlevel[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addEmployeePositionLevel',
					),
				),
			),
			'viewemppositionlevel' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewemppositionlevel[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'viewEmployeePositionLevel',
					),
				),
			),
			'emppositiontitle' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/emppositiontitle[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empPositionTitle',
					),
				),
			),
			'addemppositiontitle' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addemppositiontitle[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addEmployeePositionTitle',
					),
				),
			),
			'viewemppositiontitle' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewemppositiontitle[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'viewEmployeePositionTitle',
					),
				),
			),
			'empworkexperience' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/empworkexperience[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empWorkExperience',
					),
				),
			),
			'addempworkexperience' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addempworkexperience[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addEmployeeWorkExperience',
					),
				),
			),
			'addemprubworkexperience' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addemprubworkexperience[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   //'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addEmployeeRubWorkExperience',
					),
				),
			),
			'viewempworkexperience' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewempworkexperience[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'viewEmployeeWorkExperience',
					),
				),
			),

			'editemployeenonrubworkexperience' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editemployeenonrubworkexperience[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'editEmployeeNonRubWorkExperience',
					),
				),
			),

			'editemployeerubworkexperience' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editemployeerubworkexperience[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'editEmployeeRubWorkExperience',
					),
				),
			),

			'deleteemployeeworkexperience' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/deleteemployeeworkexperience[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'deleteEmployeeWorkExperience',
					),
				),
			),

			'downloadempworkexperiencefile' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/downloadempworkexperiencefile[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'downloadEmpWorkExperienceFile',
					),
				),
			),


			'emprelationdetail' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/emprelationdetail[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empRelationDetail',
					),
				),
			),
			'addemprelationdetail' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addemprelationdetail[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addEmployeeRelationDetail',
					),
				),
			),
			'viewemprelationdetail' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewemprelationdetail[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'viewEmployeeRelationDetail',
					),
				),
			),
			'editemployeerelationdetail' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editemployeerelationdetail[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'editEmployeeRelationDetail',
					),
				),
			),
			'deleteemployeerelationdetail' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/deleteemployeerelationdetail[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'deleteEmployeeRelationDetail',
					),
				),
			),
			'emptrainingdetail' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/emptrainingdetail[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empTrainingDetail',
					),
				),
			),
			'addemptrainingdetail' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addemptrainingdetail[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addEmployeeTrainingDetail',
					),
				),
			),
			'viewemptrainingdetail' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewemptrainingdetail[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'viewEmployeeTrainingDetail',
					),
				),
			),
			'editemployeetrainingdetail' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editemployeetrainingdetail[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'editEmployeeTrainingDetail',
					),
				),
			),
			'deleteemployeetrainingdetail' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/deleteemployeetrainingdetail[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'deleteEmployeeTrainingDetail',
					),
				),
			),
			'downloademptrainingevidencefile' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/downloademptrainingevidencefile[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'downloadEmpTrainingEvidenceFile',
					),
				),
			),
			'emppublication' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/emppublication[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empPublication',
					),
				),
			),
			'addemppublication' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addemppublication[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addEmployeePublication',
					),
				),
			),
			'viewemppublication' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewemppublication[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'viewEmployeePublication',
					),
				),
			),
			'editemployeepublication' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editemployeepublication[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'editEmployeePublication',
					),
				),
			),
			'deleteemployeepublication' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/deleteemployeepublication[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'deleteEmployeePublication',
					),
				),
			),
			'downloademppublicationevidencefile' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/downloademppublicationevidencefile[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'downloadEmpPublicationEvidenceFile',
					),
				),
			),
			'empaward' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/empaward[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empAward',
					),
				),
			),
			'addempaward' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addempaward[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addEmployeeAward',
					),
				),
			),
			'viewempaward' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewempaward[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'viewEmployeeAward',
					),
				),
			),
			'editempaward' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editempaward[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'editEmployeeAward',
					),
				),
			),
			'deleteempaward' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/deleteempaward[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'deleteEmployeeAward',
					),
				),
			),
			'downloadempawardevidencefile' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/downloadempawardevidencefile[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'downloadEmpAwardEvidenceFile',
					),
				),
			),
			'empcommunityservice' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/empcommunityservice[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empCommunityService',
					),
				),
			),
			'addempcommunityservice' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addempcommunityservice[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addEmployeeCommunityService',
					),
				),
			),
			'viewempcommunityservice' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewempcommunityservice[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'viewEmployeeCommunityService',
					),
				),
			),
			'editempcommunityservice' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editempcommunityservice[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'editEmployeeCommunityService',
					),
				),
			),
			'deleteempcommunityservice' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/deleteempcommunityservice[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'deleteEmployeeCommunityService',
					),
				),
			),

			'empdepartmentsearch' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/empdepartmentsearch[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empDepartmentSearch',
					),
				),
			),

			'updateemployeedepartment' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/updateemployeedepartment[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'updateEmployeeDepartment',
					),
				),
			),


			'editstaffdepartment' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editstaffdepartment[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'editStaffDepartment',
					),
				),
			),


			'updateeditedstaffdepartment' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/updateeditedstaffdepartment[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'updateEditedStaffDepartment',
					),
				),
			),


			'editstaffpositiontitlelevel' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editstaffpositiontitlelevel[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'editStaffPositionTitleLevel',
					),
				),
			),


			'updateeditedpositiontitlelevel' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/updateeditedpositiontitlelevel[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'updateEditedPositionTitleLevel',
					),
				),
			),


			'downloadempcommunityservicefile' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/downloadempcommunityservicefile[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'downloadEmpCommunityServiceFile',
					),
				),
			),
			'empcontribution' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/empcontribution[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empContribution',
					),
				),
			),
			'addempcontribution' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addempcontribution[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addEmployeeContribution',
					),
				),
			),
			'viewempcontribution' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewempcontribution[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'viewEmployeeContribution',
					),
				),
			),
			'editemployeecontribution' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editemployeecontribution[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'editEmployeeContribution',
					),
				),
			),
			'deleteemployeecontribution' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/deleteemployeecontribution[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'deleteEmployeeContribution',
					),
				),
			),
			'downloadempcontributionfile' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/downloadempcontributionfile[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'downloadEmpContributionFile',
					),
				),
			),
			'empresponsibility' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/empresponsibility[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empResponsibility',
					),
				),
			),
			'addempresponsibility' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addempresponsibility[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addEmployeeResponsibility',
					),
				),
			),
			'viewempresponsibility' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewempresponsibility[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'viewEmployeeResponsibility',
					),
				),
			),

			'editemployeeresponsibility' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editemployeeresponsibility[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'editEmployeeResponsibility',
					),
				),
			),

			'deleteemployeeresponsibility' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/deleteemployeeresponsibility[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'deleteEmployeeResponsibility',
					),
				),
			),
			'downloadempresponsibilityfile' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/downloadempresponsibilityfile[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'downloadEmpResponsibilityFile',
					),
				),
			),
                        'empdiscipline' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/empdiscipline[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'empDiscipline',
					),
				),
			),
			'addempdisciplinaryrecord' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addempdisciplinaryrecord[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addEmployeeDisciplinaryRecord',
					),
				),
			),
                        'viewempdisciplinary' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewempdisciplinary[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'viewEmployeeDisciplinaryRecord',
					),
				),
			),
			//the following routes are when an employee is being added
			'addemployee' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addemployee[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addEmployee',
					),
				),
			),

			'addnewemployee' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addnewemployee[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addNewEmployee',
					),
				),
			),

			'downloadnewempdocument' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/downloadnewempdocument[/:filename]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'filename' => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'downloadNewEmpDocument',
					),
				),
			),

			'newemployeelist' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/newemployeelist[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'newEmployeeList',
					),
				),
			),

			'generatenewemployeeid' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/generatenewemployeeid[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'generateNewEmployeeId',
					),
				),
			),
			// to delete new employee if records are not inserted correctly
			'rollbacknewemployeeid' => array(
                                'type' => 'segment',
                                'options' => array(
                                        'route' => '/rollbacknewemployeeid[/:action][/:id]',
                                        'constraints' => array(
                                           'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                           'id' => '[a-zA-Z0-9_-]*',
					),
					//'may_terminate' => true,
                                        'defaults' => array(
                                                'controller' => 'EmployeeDetail',
                                                'action' =>'rollBackNewEmployeeId',
                                        ),
                                ),
                        ),
			'uploadnewemployeeorder' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/uploadnewemployeeorder[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'uploadNewEmployeeOrder',
					),
				),
			),

			'viewnewaddedemployeedetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewnewaddedemployeedetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'viewNewAddedEmployeeDetails',
					),
				),
			),

			'downloadrecruitedemployeedoc' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/downloadrecruitedemployeedoc[/:filename]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'filename' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'EmployeeDetail',
					'action' => 'downloadRecruitedEmployeeDoc',
 					),
 				),
 			),

			'updateemployeedetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/updateemployeedetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'updateEmployeeDetails',
					),
				),
			),

			//old route. need to check whether it is used or not
			'addnewemployeedetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addnewemployeedetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addNewEmployeeDetails',
					),
				),
			),
			'viewnewemployeedetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/viewnewemployeedetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'viewNewEmployeeDetails',
					),
				),
			),
			'addnewemployeerelationdetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addnewemployeerelationdetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addNewEmployeeRelationDetails',
					),
				),
			),
			'addnewemployeeeducationdetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addnewemployeeeducationdetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*', 
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addNewEmployeeEducationDetails',
					),
				),
			),
			'addnewemployeetrainingdetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addnewemployeetrainingdetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addNewEmployeeTrainingDetails',
					),
				),
			),
			'addnewemployeeemploymentdetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addnewemployeeemploymentdetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addNewEmployeeEmploymentDetails',
					),
				),
			),
			'addnewemployeeresearchdetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addnewemployeeresearchdetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addNewEmployeeResearchDetails',
					),
				),
			),
			'addnewemployeedocumentsdetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/addnewemployeedocumentsdetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'addNewEmployeeDocumentsDetails',
					),
				),
			),
			'employeeonprobation' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/employeeonprobation[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'employeeOnProbation',
					),
				),
			),
			'updateemployeeonprobation' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/updateemployeeonprobation[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'updateEmployeeOnProbation',
					),
				),
			),

			'employeepaydetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/employeepaydetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'employeePayDetails',
					),
				),
			),

			'editemployeepaydetails' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/editemployeepaydetails[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'editEmployeePayDetails',
					),
				),
			),

			'hrmreports' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/hrmreports[/:action][/:id]',
					'constraints' => array(
					   'action' => '[a-zA-Z][a-zA-Z0-9_-]*', 
					   'id' => '[a-zA-Z0-9_-]*',
					),
					'defaults' => array(
						'controller' => 'EmployeeDetail',
						'action' =>'hrmReports',
					),
				),
			),
 		),
 	),

	'view_manager' => array(
		'template_path_stack' => array(
			'HrStaffprofile' => __DIR__ . '/../view',
		),
	),
);

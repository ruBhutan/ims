<?php

return array(
	'service_manager'=>array(
		'factories'=> array(
			'PmsDates\Mapper\PmsDatesMapperInterface' => 'PmsDates\Factory\ZendDbSqlMapperFactory',
			'PmsDates\Service\PmsDatesServiceInterface'=> 'PmsDates\Factory\PmsDatesServiceFactory',
			'PmsRatings\Mapper\PmsRatingsMapperInterface' => 'PmsRatings\Factory\ZendDbSqlMapperFactory',
			'PmsRatings\Service\PmsRatingsServiceInterface'=> 'PmsRatings\Factory\PmsRatingsServiceFactory',
			'Nominations\Mapper\NominationsMapperInterface' => 'Nominations\Factory\ZendDbSqlMapperFactory',
			'Nominations\Service\NominationsServiceInterface'=> 'Nominations\Factory\NominationsServiceFactory',
			'Appraisal\Mapper\AppraisalMapperInterface' => 'Appraisal\Factory\ZendDbSqlMapperFactory',
			'Appraisal\Service\AppraisalServiceInterface'=> 'Appraisal\Factory\AppraisalServiceFactory',
			'Review\Mapper\ReviewMapperInterface' => 'Review\Factory\ZendDbSqlMapperFactory',
			'Review\Service\ReviewServiceInterface'=> 'Review\Factory\ReviewServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
		),
	),
	'controllers' => array(
		'factories' => array(
			'PmsDates' => 'PmsDates\Factory\PmsDatesControllerFactory',
			'PmsRatings' => 'PmsRatings\Factory\PmsRatingsControllerFactory',
			'Nominations' => 'Nominations\Factory\NominationsControllerFactory',
			'Appraisal' => 'Appraisal\Factory\AppraisalControllerFactory',
			'Review' => 'Review\Factory\ReviewControllerFactory',
		),
	),
	'router' => array(
 		'routes' => array(
            //PMS Activation routes
			'addpmsdates' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addpmsdates[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'PmsDates',
					'action' => 'addPmsDates',
 					),
 				),
 			),
			'editpmsdates' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editpmsdates[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'PmsDates',
					'action' => 'editPmsDates',
 					),
 				),
 			),
			//Nominations
			'listnominations' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/listnominations[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Nominations',
					'action' => 'listNominations',
 					),
 				),
 			),
			'addnominations' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addnominations[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Nominations',
					'action' => 'addNominations',
 					),
 				),
 			),
			'nominatepeer' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/nominatepeer[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Nominations',
					'action' => 'nominatePeer',
 					),
 				),
 			),
			'viewpeer' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewpeer[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Nominations',
					'action' => 'viewPeer',
 					),
 				),
 			),
			'editpeer' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editpeer[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Nominations',
					'action' => 'editPeer',
 					),
 				),
 			),
			'deletepeer' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletepeer[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Nominations',
					'action' => 'deletePeer',
 					),
 				),
 			),
			'nominatesubordinate' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/nominatesubordinate[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Nominations',
					'action' => 'nominateSubordinate',
 					),
 				),
 			),
			'viewsubordinate' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewsubordinate[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Nominations',
					'action' => 'viewSubordinate',
 					),
 				),
 			),
			'editsubordinate' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editsubordinate[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Nominations',
					'action' => 'editSubordinate',
 					),
 				),
 			),
			'deletesubordinate' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletesubordinate[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Nominations',
					'action' => 'deleteSubordinate',
 					),
 				),
 			),
			'nominatebeneficiary' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/nominatebeneficiary[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Nominations',
					'action' => 'nominateBeneficiary',
 					),
 				),
 			),
			'viewbeneficiary' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewbeneficiary[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Nominations',
					'action' => 'viewBeneficiary',
 					),
 				),
 			),
			'editbeneficiary' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editbeneficiary[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Nominations',
					'action' => 'editBeneficiary',
 					),
 				),
 			),
			'deletebeneficiary' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deletebeneficiary[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Nominations',
					'action' => 'deleteBeneficiary',
 					),
 				),
 			),
			//PMS Ratings
			'viewfeedbackquestions' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewfeedbackquestions[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'PmsRatings',
					'action' => 'viewFeedbackQuestions',
 					),
 				),
 			),
			'addpeerquestions' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addpeerquestions[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'PmsRatings',
					'action' => 'addPeerQuestions',
 					),
 				),
 			),
			'editpeerquestions' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editpeerquestions[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'PmsRatings',
					'action' => 'editPeerQuestions',
 					),
 				),
 			),
			'addsubordinatequestions' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addsubordinatequestions[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'PmsRatings',
					'action' => 'addSubordinateQuestions',
 					),
 				),
 			),
			'editsubordinatequestions' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editsubordinatequestions[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'PmsRatings',
					'action' => 'editSubordinateQuestions',
 					),
 				),
 			),
			'addbeneficiaryquestions' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addbeneficiaryquestions[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'PmsRatings',
					'action' => 'addBeneficiaryQuestions',
 					),
 				),
 			),
			'editbeneficiaryquestions' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editbeneficiaryquestions[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'PmsRatings',
					'action' => 'editBeneficiaryQuestions',
 					),
 				),
 			),
			'addstudentquestions' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/addstudentquestions[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'PmsRatings',
					'action' => 'addStudentQuestions',
 					),
 				),
 			),
			'editstudentquestions' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editstudentquestions[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'PmsRatings',
					'action' => 'editStudentQuestions',
 					),
 				),
 			),
			//Appraisals
			'natureofactivity' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/natureofactivity[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'addNatureActivity',
 					),
 				),
 			),
			'editnatureofactivity' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editnatureofactivity[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'editNatureActivity',
 					),
 				),
 			),
			'academicweight' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/academicweight[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'addAcademicWeight',
 					),
 				),
 			),
			'editacademicweight' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editacademicweight[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'editAcademicWeight',
 					),
 				),
 			),
			'academicapi' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/academicapi[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'addAcademicApi',
 					),
 				),
 			),
			'administrativeappraisal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/administrativeappraisal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'administrativeAppraisal',
 					),
 				),
 			),
			'editadministrativeappraisal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editadministrativeappraisal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'editAdministrativeAppraisal',
 					),
 				),
 			),
			'viewadministrativeappraisal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewadministrativeappraisal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'viewAdministrativeAppraisal',
 					),
 				),
 			),
			'deleteadministrativeappraisal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deleteadministrativeappraisal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'deleteAdministrativeAppraisal',
 					),
 				),
 			),
			'academicappraisal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/academicappraisal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'academicAppraisal',
 					),
 				),
 			),
			'viewacademicappraisal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewacademicappraisal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'viewAcademicAppraisal',
 					),
 				),
 			),
			'editacademicappraisal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/editacademicappraisal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'editAcademicAppraisal',
 					),
 				),
 			),
			'deleteacademicappraisal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/deleteacademicappraisal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'deleteAcademicAppraisal',
 					),
 				),
 			),
			'administrativeappraisalform' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/administrativeappraisalform[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'administrativeappraisalform',
 					),
 				),
 			),
			'academicappraisalform' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/academicappraisalform[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'academicappraisalform',
 					),
 				),
 			),
			'appraisalapprovallist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/appraisalapprovallist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'appraisalApprovalList',
 					),
 				),
 			),
			'viewadministrativeappraisaldetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewadministrativeappraisaldetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'viewAdministrativeAppraisalDetail',
 					),
 				),
 			),
			'viewacademicappraisaldetail' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewacademicappraisaldetail[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'viewAcademicAppraisalDetail',
 					),
 				),
 			),
                        'empiwpactivities' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empiwpactivities[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'employeeIWPActivities',
 					),
 				),
 			),
                        'submitiwpactivities' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/submitiwpactivities[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'submitIWPActivities',
 					),
 				),
 			),
			'viewnominationappraisal' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewnominationappraisal[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'viewNominationAppraisal',
 					),
 				),
 			),
			//the approve and reject functions are not used
			'approvepeernomination' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approvepeernomination[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'approvePeerNomination',
 					),
 				),
 			),
			'rejectpeernomination' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/rejectpeernomination[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'rejectPeerNomination',
 					),
 				),
 			),
			'approvebeneficiarynomination' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approvebeneficiarynomination[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'approveBeneficiaryNomination',
 					),
 				),
 			),
			'rejectbeneficiarynomination' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/rejectbeneficiarynomination[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'rejectBeneficiaryNomination',
 					),
 				),
 			),
			'approvesubordinatenomination' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/approvesubordinatenomination[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'approveSubordinateNomination',
 					),
 				),
 			),
			'rejectsubordinatenomination' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/rejectsubordinatenomination[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Appraisal',
					'action' => 'rejectSubordinateNomination',
 					),
 				),
 			),
			'empperformanceassessment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empperformanceassessment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Review',
					'action' => 'performanceAssessment',
 					),
 				),
 			),
			'administrativereviewform' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/administrativereviewform[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Review',
					'action' => 'administrativeReviewForm',
 					),
 				),
 			),
			'academicreviewform' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/academicreviewform[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Review',
					'action' => 'academicReviewForm',
 					),
 				),
 			),
			'studentfeedbackstatus' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentfeedbackstatus[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Review',
					'action' => 'studentfeedbackstatus',
 					),
 				),
 			),
			'empviewassessment' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/empviewassessment[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Review',
					'action' => 'empviewassessment',
 					),
 				),
 			),
			'pmsemployeelist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/pmsemployeelist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Review',
					'action' => 'pmsEmployeeList',
 					),
 				),
 			),
			'performancereviewlist' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/performancereviewlist[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Review',
					'action' => 'performanceReviewList',
 					),
 				),
 			),
			'feedbacks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/feedbacks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Review',
					'action' => 'feedbacks',
 					),
 				),
 			),
			'peerfeedbackform' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/peerfeedbackform[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Review',
					'action' => 'peerFeedbackform',
 					),
 				),
 			),
			'subordinatefeedbackform' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/subordinatefeedbackform[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Review',
					'action' => 'subordinateFeedbackform',
 					),
 				),
 			),
			'beneficiaryfeedbackform' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/beneficiaryfeedbackform[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Review',
					'action' => 'beneficiaryFeedbackform',
 					),
 				),
 			),
			'studentfeedbacks' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/studentfeedbacks[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Review',
					'action' => 'studentFeedback',
 					),
 				),
 			),
			//this is used to display pms details
			//used by promotion as well
			'viewemployeepmsdetails' => array(
 				'type' => 'segment',
 				'options' => array(
 				'route' => '/viewemployeepmsdetails[/:action][/:id]',
 				'constraints' => array(
 					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 					'id' => '[a-zA-Z0-9_-]*',
 					),
				'defaults' => array(
					'controller' => 'Review',
					'action' => 'viewEmployeePmsDetails',
 					),
 				),
 			),
         ),
 	),
 
	'view_manager' => array(
		'template_path_stack' => array(
		'Pms' => __DIR__ . '/../view',
		),
	),
);


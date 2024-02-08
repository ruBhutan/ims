<?php

namespace Alumni\Mapper;

//use Alumni\Model\AlumniMember;
use Alumni\Model\AlumniStudent;
use Alumni\Model\Alumni;
use Alumni\Model\AlumniRegistration;
use Alumni\Model\AlumniEvent;
use Alumni\Model\AlumniResource;
use Alumni\Model\AlumniProfile;
use Alumni\Model\UpdateAlumni;
use Alumni\Model\AlumniEnquiry;
use Alumni\Model\AlumniFaqs;
use Alumni\Model\AlumniContribution;
use Alumni\Model\AlumniSubscriptionDetails;
use Alumni\Model\AlumniSubscriberDetails;
use Alumni\Model\AlumniSubscription;
use Alumni\Model\UpdateAlumniSubscriberDetails;

interface AlumniMapperInterface
{
	/*
	* Getting the id for username
	*/
	
	public function getEmployeeDetailsId($emp_id);

	public function getAlumniDetailsId($cid);
	
	/*
	* Get organisation id based on the username
	*/
	
	public function getOrganisationId($username, $usertype);

	public function getUserDetails($username, $usertype);

	public function getUserImage($username, $usertype);
	
	//public function findAlumniNewRegistered($id);

	public function findAllAlumniNewRegistered($organisation_id);

	public function getAlumniMemberList($alumniProgramme, $alumniBatch, $alumniName, $organisation_id);
        
	/*public function findAlumniNewRegisteredDetails($id);*/

	public function saveAlumniNewRegistered(AlumniRegistration $alumniInterface);

	/*public function findUpdatedAlumni($id);

	public function findAllUpdatedAlumni();
        
	public function findUpdatedAlumniDetails($id);

	public function saveUpdatedAlumni(UpdateAlumni $alumniInterface); */

	public function findAlumniProfile($id);
	
	public function getAlumniPersonalDetails($id);
	
	public function getAlumniPermanentAddress($id);
	
	public function getAlumniContactDetails($id);
	
	public function getAlumniEmploymentDetails($id);

	/*public function findAllAlumniProfile();
        
	public function findAlumniProfileDetails($id);

	public function saveAlumniProfile(AlumniProfile $alumniInterface);

	public function findAlumni($id);

	public function findAllAlumni();
        
	public function findAlumniDetails($id);

	public function saveAlumni(Alumni $alumniInterface);

	public function findAlumniEvent($id); */

	public function findAllAlumniEvent($organisation_id);

	public function getEventEmailList($batch, $programme, $organisation);

	public function getAlumniContributionEmailList($organisation);

	public function listAllContributionDetails($organisation_id);
        
	/*public function findAlumniEventDetails($id);*/

	public function saveAlumniEvent(AlumniEvent $alumniInterface);

	public function saveAlumniContribution(AlumniContribution $alumniInterface);
	
	public function saveAlumniResource(AlumniResource $alumniInterface);
	
	public function listAllAlumniResource($organisation_id);
	
	public function saveAlumniEnquiry(AlumniEnquiry $alumniInterface);
	
	public function listAllAlumniEnquiry($organisation_id);

	public function listAlumniEnquiry($alumni_id);

	public function checkAlumniSubscription($alumni_id);
	
	public function saveAlumniFaqs(AlumniFaqs $alumniInterface);
	
	public function listAllAlumniFaqs($organisation_id);

	public function crossCheckSubscriptionDetails($subscription_details);

	public function crossCheckSubscriptionType($action_type, $id, $subscription_type, $organisation_id);

	public function listAllAlumniSubscriptionList($organisation_id);

	public function listAlumniSubscriptionDetailList($organisation_id);

	public function getAlumniSubscriptionList($organisation_id);

	public function checkRegisteredSubscriber($alumni_id);

	public function getAlumniSubscriberDetails($employee_details_id);

	public function getAlumniSubscriptionDetails($id);

	public function getAlumniSubscription($id);

	public function getAlumniSubscriberList($organisation_id, $status);

	public function listAlumniSubscriptionDetails($id);

	public function getAlumniSubscriptionApplicationDetails($id);

	public function getAlumniSubscribingDetails($organisation_id);

	public function saveSubscriptionList(AlumniSubscriptionDetails $alumniInterface);

	public function saveSubscriptionDetails(AlumniSubscription $alumniObject);

	public function updateAlumniSubscription(UpdateAlumniSubscriberDetails $alumniInterface);

	public function renewAlumniSubscription($id);

	public function saveAlumniSubscription(AlumniSubscriberDetails $alumniInterface, $subscription_type, $subscription_charge);
	
	/*public function getRegisteredMemberList($memProgramme, $memYear, $memName);*/

	public function listSelectData($tableName, $columnName, $organisation_id);
	
	public function listSelectData1($tableName, $columnName, $organisation_id);

	public function updateAlumniEnquiry($status, $previousStatus, $id, $organisation_id);
	
	/*public function getAllAlumniStudent($organisation_id);*/

}
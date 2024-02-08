<?php

namespace Alumni\Service;

use Alumni\Mapper\AlumniMapperInterface;
//use Alumni\Model\AlumniMember;
use Alumni\Model\AlumniStudent;
use Alumni\Model\Alumni;
use Alumni\Model\AlumniRegistration;
use Alumni\Model\AlumniEvent;
use Alumni\Model\AlumniProfile;
use Alumni\Model\UpdateAlumni;
use Alumni\Model\AlumniResource;
use Alumni\Model\AlumniEnquiry;
use Alumni\Model\AlumniFaqs;
use Alumni\Model\AlumniContribution;
use Alumni\Model\AlumniSubscriptionDetails;
use Alumni\Model\AlumniSubscriberDetails;
use Alumni\Model\AlumniSubscription;
use Alumni\Model\UpdateAlumniSubscriberDetails;


class AlumniService implements AlumniServiceInterface
{
	/**
	 * @var \Blog\Mapper\PostMapperInterface
	*/
	
	protected $alumniMapper;
	
	
	public function __construct(AlumniMapperInterface $alumniMapper) {
		$this->alumniMapper = $alumniMapper;
	}
	public function getEmployeeDetailsId($emp_id)
	{
		return $this->alumniMapper->getEmployeeDetailsId($emp_id);
	}


	public function getAlumniDetailsId($cid)
	{
		return $this->alumniMapper->getAlumniDetailsId($cid);
	}
	
	public function getOrganisationId($username, $usertype)
	{
		return $this->alumniMapper->getOrganisationId($username, $usertype);
	}

	public function getUserDetails($username, $usertype)
	{
		return $this->alumniMapper->getUserDetails($username, $usertype);
	}

	public function getUserImage($username, $usertype)
	{
		return $this->alumniMapper->getUserImage($username, $usertype);
	}

	public function listAllAlumniNewRegistered($organisation_id)
	{
		return $this->alumniMapper->findAllAlumniNewRegistered($organisation_id);
	}

	public function getAlumniMemberList($alumniProgramme, $alumniBatch, $alumniName, $organisation_id)
	{
		return $this->alumniMapper->getAlumniMemberList($alumniProgramme, $alumniBatch, $alumniName, $organisation_id);
	}

	/*public function listAlumniNewRegistered()
	{
		return $this->alumniMapper->listAlumniNewRegistered();
	}

	public function findAlumniNewRegistered($id)
	{
		return $this->alumniMapper->findAlumniNewRegistered($id);
		
	}
        
	public function findAlumniNewRegisteredDetails($id) 
	{
		return $this->alumniMapper->findAlumniNewRegisteredDetails($id);
	}*/
	
	public function saveAlumniNewRegistered(AlumniRegistration $alumniObject) 
	{
		return $this->alumniMapper->saveAlumniNewRegistered($alumniObject);
	}

	/*public function listAllUpdatedAlumni()
	{
		return $this->alumniMapper->findAllUpdatedAlumni();
	}

	public function listUpdatedAlumni()
	{
		return $this->alumniMapper->findUpdatedAlumni();
	}

	public function findUpdatedAlumni($id)
	{
		return $this->alumniMapper->findUpdatedAlumni($id);
		
	}
        
	public function findUpdatedAlumniDetails($id) 
	{
		return $this->alumniMapper->findUpdatedAlumniDetails($id);
	}
	
	public function saveUpdatedAlumni(UpdateAlumni $alumniObject) 
	{
		return $this->alumniMapper->saveUpdatedAlumni($alumniObject);
	}


	public function listAllAlumni()
	{
		return $this->alumniMapper->findAllAlumni();
	}

	public function listAlumni()
	{
		return $this->alumniMapper->findAlumni();
	}

	public function findAlumni($id)
	{
		return $this->alumniMapper->findAlumni($id);
		
	}
        
	public function findAlumniDetails($id) 
	{
		return $this->alumniMapper->findAlumniDetails($id);
	}
	
	public function saveAlumni(Alumni $alumniObject) 
	{
		return $this->alumniMapper->saveAlumni($alumniObject);
	}

	public function listAllAlumniProfile()
	{
		return $this->alumniMapper->findAllAlumniProfile();
	}

	public function listAlumniProfile()
	{
		return $this->alumniMapper->findAlumniProfile();
	} */

	public function findAlumniProfile($id)
	{
		return $this->alumniMapper->findAlumniProfile($id);
		
	}
	
	public function getAlumniPersonalDetails($id)
	{
		return $this->alumniMapper->getAlumniPersonalDetails($id);
	}
	
	public function getAlumniPermanentAddress($id)
	{ 
		return $this->alumniMapper->getAlumniPermanentAddress($id);
	}
	
	public function getAlumniContactDetails($id)
	{
		return $this->alumniMapper->getAlumniContactDetails($id);
	}
	
	public function getAlumniEmploymentDetails($id)
	{
		return $this->alumniMapper->getAlumniEmploymentDetails($id);
	}
	
        
	/*public function findAlumniProfileDetails($id) 
	{
		return $this->alumniMapper->findAlumniProfileDetails($id);
	}
	
	public function saveAlumniProfile(AlumniProfile $alumniObject) 
	{
		return $this->alumniMapper->saveAlumniProfile($alumniObject);
	}*/

	public function listAllAlumniEvent($organisation_id)
	{
		return $this->alumniMapper->findAllAlumniEvent($organisation_id);
	}


	public function getEventEmailList($batch, $programme, $organisation)
	{
		return $this->alumniMapper->getEventEmailList($batch, $programme, $organisation);
	}


	public function getAlumniContributionEmailList($organisation)
	{
		return $this->alumniMapper->getAlumniContributionEmailList($organisation);
	}


	public function listAllContributionDetails($organisation_id)
	{
		return $this->alumniMapper->listAllContributionDetails($organisation_id);
	}

	/*public function findAlumniEvent($id)
	{
		return $this->alumniMapper->findAlumniEvent($id);
		
	}
        
	public function findAlumniEventDetails($id) 
	{
		return $this->alumniMapper->findAlumniEventDetails($id);
	}*/
	
	public function saveAlumniEvent(AlumniEvent $alumniObject) 
	{
		return $this->alumniMapper->saveAlumniEvent($alumniObject);
	}


	public function saveAlumniContribution(AlumniContribution $alumniObject)
	{
		return $this->alumniMapper->saveAlumniContribution($alumniObject);
	}
	
	public function saveAlumniResource(AlumniResource $alumniObject) 
	{
		return $this->alumniMapper->saveAlumniResource($alumniObject);
	}

	public function listAllAlumniResource($organisation_id)
	{
		return $this->alumniMapper->listAllAlumniResource($organisation_id);
	}
	
	public function saveAlumniEnquiry(AlumniEnquiry $alumniObject) 
	{
		return $this->alumniMapper->saveAlumniEnquiry($alumniObject);
	}

	public function listAllAlumniEnquiry($organisation_id)
	{
		return $this->alumniMapper->listAllAlumniEnquiry($organisation_id);
	}


	public function listAlumniEnquiry($alumni_id)
	{
		return $this->alumniMapper->listAlumniEnquiry($alumni_id);
	}

	public function checkAlumniSubscription($alumni_id)
	{
		return $this->alumniMapper->checkAlumniSubscription($alumni_id);
	}
	
	public function saveAlumniFaqs(AlumniFaqs $alumniObject) 
	{
		return $this->alumniMapper->saveAlumniFaqs($alumniObject);
	}
	
	public function listAllAlumniFaqs($organisation_id)
	{
		return $this->alumniMapper->listAllAlumniFaqs($organisation_id);
	}

	public function crossCheckSubscriptionDetails($subscription_details)
	{
		return $this->alumniMapper->crossCheckSubscriptionDetails($subscription_details);
	}

	public function crossCheckSubscriptionType($action_type, $id, $subscription_type, $organisation_id)
	{
		return $this->alumniMapper->crossCheckSubscriptionType($action_type, $id, $subscription_type, $organisation_id);
	}

	public function listAllAlumniSubscriptionList($organisation_id)
	{
		return $this->alumniMapper->listAllAlumniSubscriptionList($organisation_id);
	}


	public function listAlumniSubscriptionDetailList($organisation_id)
	{
		return $this->alumniMapper->listAlumniSubscriptionDetailList($organisation_id);
	}

	public function getAlumniSubscriptionList($organisation_id)
	{
		return $this->alumniMapper->getAlumniSubscriptionList($organisation_id);
	}


	public function checkRegisteredSubscriber($alumni_id)
	{
		return $this->alumniMapper->checkRegisteredSubscriber($alumni_id);
	}

	public function getAlumniSubscriberDetails($employee_details_id)
	{
		return $this->alumniMapper->getAlumniSubscriberDetails($employee_details_id);
	}

	public function getAlumniSubscriptionDetails($id)
	{
		return $this->alumniMapper->getAlumniSubscriptionDetails($id);
	}

	public function getAlumniSubscription($id)
	{
		return $this->alumniMapper->getAlumniSubscription($id);
	}

	public function getAlumniSubscriberList($organisation_id, $status)
	{
		return $this->alumniMapper->getAlumniSubscriberList($organisation_id, $status);
	}

	public function getAlumniSubscribingDetails($organisation_id)
	{
		return $this->alumniMapper->getAlumniSubscribingDetails($organisation_id);
	}


	public function saveSubscriptionList(AlumniSubscriptionDetails $alumniObject)
	{
		return $this->alumniMapper->saveSubscriptionList($alumniObject);
	}

	public function saveSubscriptionDetails(AlumniSubscription $alumniObject)
	{
		return $this->alumniMapper->saveSubscriptionDetails($alumniObject);
	}

	public function updateAlumniSubscription(UpdateAlumniSubscriberDetails $alumniObject)
	{
		return $this->alumniMapper->updateAlumniSubscription($alumniObject);
	}

	public function renewAlumniSubscription($id)
	{
		return $this->alumniMapper->renewAlumniSubscription($id);
	}

	public function saveAlumniSubscription(AlumniSubscriberDetails $alumniObject, $subscription_type, $subscription_charge)
	{
		return $this->alumniMapper->saveAlumniSubscription($alumniObject, $subscription_type, $subscription_charge);
	}

	public function listAlumniSubscriptionDetails($id)
	{
		return $this->alumniMapper->listAlumniSubscriptionDetails($id);
	}

	public function getAlumniSubscriptionApplicationDetails($id)
	{
		return $this->alumniMapper->getAlumniSubscriptionApplicationDetails($id);
	}
	
	/*public function getRegisteredMemberList($memProgramme, $memYear, $memName)
	{
		return $this->alumniMapper->getRegisteredMemberList($memProgramme, $memYear, $memName);
	}*/

	public function listSelectData($tableName, $columnName, $organisation_id)
	{
		return $this->alumniMapper->listSelectData($tableName, $columnName, $organisation_id);
	}

	public function listSelectData1($tableName, $columnName, $organisation_id)
	{
		return $this->alumniMapper->listSelectData1($tableName, $columnName, $organisation_id);
	}

	public function updateAlumniEnquiry($status, $previousStatus, $id, $organisation_id)
	{
		return $this->alumniMapper->updateAlumniEnquiry($status, $previousStatus, $id, $organisation_id);
	}
	
	/*public function getAllAlumniStudent($organisation_id)
	{
		return $this->alumniMapper->getAllAlumniStudent($organisation_id);
	}
	
	public function listSelectData2($tableName, $columnName, $organisation_id)
	{
		return $this->alumniMapper->listSelectData2($tableName, $columnName, $organisation_id);
	}*/
	
}
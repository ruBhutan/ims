<?php

namespace StudentSuggestions\Factory;

use StudentSuggestions\Service\StudentSuggestionsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentSuggestionsServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new StudentSuggestionsService(
			$serviceLocator->get('StudentSuggestions\Mapper\StudentSuggestionsMapperInterface')
		);
	}
	
}
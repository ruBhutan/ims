<?php

namespace OrgSettings\Model;

class OrganisationDocuments
{
	protected $id;
	protected $documents;
	protected $document_type;
	protected $organisation_id;
	 	 
	public function getId()
	{
		 return $this->id;
	}
	 
	public function setId($id)
	{
		 $this->id = $id;
	}
	 
	public function getDocuments()
	{
		return $this->documents;
	}
	
	public function setDocuments($documents)
	{
		$this->documents = $documents;
	}
	
	public function getDocument_Type()
	{
		return $this->document_type;
	}
	
	public function setDocument_Type($document_type)
	{
		$this->document_type = $document_type;
	}
	
	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}
	
	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}
}
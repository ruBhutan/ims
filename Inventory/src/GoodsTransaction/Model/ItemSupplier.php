<?php

namespace GoodsTransaction\Model;

class ItemSupplier
{
	protected $id;
	protected $supplier_name;
	protected $supplier_license_no;
	protected $supplier_tpn_no;
	protected $supplier_bank_acc_no;
	protected $supplier_contact_no;
	protected $supplier_address;
	protected $supplier_status;
	protected $organisation_id;


	protected $from_date;
	protected $to_date;
	protected $supporting_documents;
	protected $supplier_details_id;
	
	 	 
	 public function getId()
	 {
		 return $this->id;
	 }
	 
	 public function setId($id)
	 {
		 $this->id = $id;
	 }
	 
	 public function getSupplier_Name()
	 {
		return $this->supplier_name; 
	 }
	 	 
	 public function setSupplier_Name($supplier_name)
	 {
		 $this->supplier_name = $supplier_name;
	 }

	  public function getSupplier_License_No()
	 {
		return $this->supplier_license_no; 
	 }
	 	 
	 public function setSupplier_License_no($supplier_license_no)
	 {
		 $this->supplier_license_no = $supplier_license_no;
	 }

	 public function getSupplier_Tpn_No()
	 {
		return $this->supplier_tpn_no; 
	 }
	 	 
	 public function setSupplier_Tpn_no($supplier_tpn_no)
	 {
		 $this->supplier_tpn_no = $supplier_tpn_no;
	 }
	 
	 public function getSupplier_Bank_Acc_No()
	 {
		 return $this->supplier_bank_acc_no;
	 }
	 
	 public function setSupplier_Bank_Acc_No($supplier_bank_acc_no)
	 {
		 $this->supplier_bank_acc_no = $supplier_bank_acc_no;
	 }

	 public function getSupplier_Contact_No()
	 {
		 return $this->supplier_contact_no;
	 }
	 
	 public function setSupplier_Contact_No($supplier_contact_no)
	 {
		 $this->supplier_contact_no = $supplier_contact_no;
	 }

	public function getSupplier_Address()
	{
		return $this->supplier_address;
	}

	public function setSupplier_Address($supplier_address)
	{
		$this->supplier_address = $supplier_address;
	}

	public function getSupplier_Status()
	{
		return $this->supplier_status;
	}

	public function setSupplier_Status($supplier_status)
	{
		$this->supplier_status = $supplier_status;
	}

	public function getOrganisation_Id()
	{
		return $this->organisation_id;
	}

	public function setOrganisation_Id($organisation_id)
	{
		$this->organisation_id = $organisation_id;
	}


	public function getFrom_Date()
	{
		return $this->from_date;
	}

	public function setFrom_Date($from_date)
	{
		$this->from_date = $from_date;
	}


	public function getTo_Date()
	{
		return $this->to_date;
	}

	public function setTo_Date($to_date)
	{
		$this->to_date = $to_date;
	}

	public function getSupporting_Documents()
	{
		return $this->supporting_documents;
	}

	public function setSupporting_Documents($supporting_documents)
	{
		$this->supporting_documents = $supporting_documents;
	}

	public function getSupplier_Details_Id()
	{
		return $this->supplier_details_id;
	}

	public function setSupplier_Details_Id($supplier_details_id)
	{
		$this->supplier_details_id = $supplier_details_id;
	}
}	
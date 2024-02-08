<?php
/*
 * Bhutaneseprogramer @ bhutaneseprogrammer@gmail.com
 * 
 * Helper -- Currency code
 * 
 * get currency code
 * 
 */
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Accounts\Model\StatusTable;

class Statushelper extends AbstractHelper
{
	private $statusTable;
	
	public function __construct(StatusTable $statusTable)
	{
		$this->statusTable = $statusTable;
	}
	
	public function __invoke($status)
	{
		foreach($this->statusTable->get($status) as $row);
		echo "<span class='label label-".$row['label']."'> ".$row['status']." </span>";
	}
}

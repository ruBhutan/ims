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
use Accounts\Model\CurrencyTable;

class CurrencyCodehelper extends AbstractHelper
{
	private $currencyTable;
	
	public function __construct(CurrencyTable $currencyTable)
	{
		$this->currencyTable = $currencyTable;
	}
	
	public function __invoke()
	{
		return $this->currencyTable->getColumn(array('status' =>'1'),'code');
	}
}
<?php
/*
 * Bhutaneseprogramer @ bhutaneseprogrammer@gmail.com
 * 
 * Helper -- Currency format
 * 
 * convert Number to currency
 * 
 */
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Decimalhelper extends AbstractHelper
{
	public function __invoke($number)
	{		
		return number_format($number, 3, '.', ',');
	}
}

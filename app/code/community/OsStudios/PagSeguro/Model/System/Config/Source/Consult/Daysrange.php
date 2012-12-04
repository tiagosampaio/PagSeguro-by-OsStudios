<?php
/**
 * Os Studios PagSeguro Payment Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   OsStudios
 * @package    OsStudios_PagSeguro
 * @copyright  Copyright (c) 2012 Os Studios (www.osstudios.com.br)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Tiago Sampaio <tiago.sampaio@osstudios.com.br>
 */

/**
 * PagSeguro Installments Source
 *
 */

class OsStudios_PagSeguro_Model_System_Config_Source_Consult_Daysrange
{
	public function toOptionArray ()
	{
		$options = array();
        
		for( $y=1; $y<=30; $y++ )
		{
			$options[] = array('value' => $y, 'label' => Mage::helper('pagseguro')->__('From %s day(s) before to now', $y));
		}
        
		return $options;
	}

}
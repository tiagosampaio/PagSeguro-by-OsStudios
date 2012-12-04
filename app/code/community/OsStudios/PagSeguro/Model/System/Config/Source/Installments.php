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

class OsStudios_PagSeguro_Model_System_Config_Source_Installments
{
	public function toOptionArray ()
	{
		$options = array();
        
        $options[] 		= array('value' => 1 , 'label' => Mage::helper('pagseguro')->__('Deactivated (%sx)', 1));
        
        for($y=2; $y<=18; $y++){
        	$options[] 	= array('value' => $y, 'label' => Mage::helper('pagseguro')->__('Max %sx', $y));
        }
        
		return $options;
	}

}
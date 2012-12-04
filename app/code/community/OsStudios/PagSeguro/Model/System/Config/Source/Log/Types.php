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
 * PagSeguro Log Types
 *
 */

class OsStudios_PagSeguro_Model_System_Config_Source_Log_Types
{
	public function toOptionArray()
	{
		$options = array();
        
		$options[] = array('value' => 'begend_tags'			, 'label' => Mage::helper('pagseguro')->__('Beginning and Ending Tags'));
		$options[] = array('value' => 'return_data'			, 'label' => Mage::helper('pagseguro')->__('PagSeguro Return Data'));
		$options[] = array('value' => 'confimation_status'	, 'label' => Mage::helper('pagseguro')->__('PagSeguro Confirmation Status'));
		$options[] = array('value' => 'order_status'		, 'label' => Mage::helper('pagseguro')->__('PagSeguro Order Status'));
		$options[] = array('value' => 'log_separator'		, 'label' => Mage::helper('pagseguro')->__('Separator'));
		
		return $options;
	}

}
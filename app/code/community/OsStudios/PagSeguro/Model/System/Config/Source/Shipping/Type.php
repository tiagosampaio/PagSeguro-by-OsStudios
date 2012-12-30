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
 * PagSeguro Shipping Type Source
 *
 */

class OsStudios_PagSeguro_Model_System_Config_Source_Shipping_Type
{
	public function toOptionArray ()
	{
            $options = array();
        
            $options[] = array('value' => '1', 'label' => 'Encomenda normal (PAC).');
            $options[] = array('value' => '2', 'label' => 'SEDEX');
            $options[] = array('value' => '3', 'label' => 'Tipo de frete não especificado.');
        
            return $options;
	}

}
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
 * PagSeguro Adminhtml Returns Controller
 *
 */

class OsStudios_PagSeguro_Adminhtml_ReturnsController extends Mage_Adminhtml_Controller_Action
{
    
	public function updatesAction()
	{
		
		$model = Mage::getModel('pagseguro/data');
		
		$returns = Mage::getModel('pagseguro/returns');
		$returns->setReturnType(OsStudios_PagSeguro_Model_Returns::PAGSEGURO_RETURN_TYPE_CONSULT)
				->runReturns();
		
		$this->_redirect('adminhtml/sales_order/');
				
	}
    
}
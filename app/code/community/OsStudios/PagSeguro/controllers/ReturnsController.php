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
 * 
 * PagSeguro Returns Controller
 *
 */

class OsStudios_PagSeguro_ReturnsController extends OsStudios_PagSeguro_Controller_Front_Abstract
{
    
    /**
     * Used for two functionalities:
     * 
     * 1) Redirect the response from PagSeguro to set success page after finishing an order in PagSeguro
     * 2) Receive and control the requests from the auto data returns from PagSeguro when update order statuses
     * 
     */
    public function indexAction()
    {
        $pagseguro = $this->getPagSeguro();
        $request = $this->getRequest();
        
		if (!$request->isPost()) {
            // That is a $_GET. Redirect to set page.
            $orderId = Mage::getSingleton("core/session")->getPagseguroOrderId();
            
            if ($orderId) {
                $storeId = $this->getOrderStoreId($orderId);
                
                if ($pagseguro->getConfigData('use_return_page_cms', $storeId)) {
                    $url = $pagseguro->getConfigData('return_page', $storeId);
                    Mage::getSingleton("core/session")->setPagseguroOrderId(null);
                } else {
                    $url = 'pagseguro/pay/success';
                }
            } else {
                $url = '';
            }
            $this->_redirect($url);
        }
        
        if (($post = $request->getPost()) && $request->isPost()) {
        	
        	$returns = Mage::getModel('pagseguro/returns');
        	$data = Mage::getModel('pagseguro/data');
        	
        	if($data->isReturnApi($post))
        	{
        		$returns->setReturnType(OsStudios_PagSeguro_Model_Returns::PAGSEGURO_RETURN_TYPE_API);
        	} elseif ($data->isReturnDefault($post)) {
        		$returns->setReturnType(OsStudios_PagSeguro_Model_Returns::PAGSEGURO_RETURN_TYPE_DEFAULT);
        	} else {
        		/**
        		 * None of allowed post types was sent
        		 */
        		return false;
        	}
        	
        	/**
        	 * Process returns according to type
        	 */
        	$returns->setPostData($post)
        			->runReturns();
        			
        } else {
        	
            // That is a $_GET. Redirect to set page.
            $orderId = Mage::getSingleton("core/session")->getPagseguroOrderId();
            
            if ($orderId) {
                $storeId = $this->getOrderStoreId($orderId);
                
                if ($pagseguro->getConfigData('use_return_page_cms', $storeId)) {
                    $url = $pagseguro->getConfigData('return_page', $storeId);
                    Mage::getSingleton("core/session")->setPagseguroOrderId(null);
                } else {
                    $url = 'pagseguro/pay/success';
                }
            } else {
                $url = '';
            }
            $this->_redirect($url);
            
        }
    }
    
}
<?php
/**
 * Os Studios PagSeguro Api Payment Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   OsStudios
 * @package    OsStudios_PagSeguroApi
 * @copyright  Copyright (c) 2013 Os Studios (www.osstudios.com.br)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Tiago Sampaio <tiago.sampaio@osstudios.com.br>
 */

abstract class OsStudios_PagSeguroApi_Model_Abstract extends Mage_Core_Model_Abstract
{

	/**
	 * Provide Helper to children classes
	 *
	 * @return OsStudios_PagSeguroApi_Helper_Data
	 */
	protected function helper()
	{
		return Mage::helper('pagseguroapi');
	}

    /**
     * Provides any module config option
     *
     */
	protected function getConfigData($configNode)
	{
		return Mage::getStoreConfig('payment/pagseguro_api/'.$configNode);
	}

	/**
     * Returns the URL to generate the billets of PagSeguro
     * 
     * @param string $transactionId = PagSeguro Transaction ID
     * @return (string)
     */ 
    public function getPagSeguroBilletUrl($transactionId, $escapeHtml = true)
    {
        $url = $this->getConfigData('pagseguro_billet_url');

    	if(!$url) {
            //Mage::throwException($this->helper()->__('The PagSeguro Billet URL could not be retrieved.'));
    	}
    	
        $url .= '?resizeBooklet=n&code=' . $transactionId;
        if ($escapeHtml) {
            $url = $this->helper()->escapeHtml($url);
        }
        return $url;
    }

}
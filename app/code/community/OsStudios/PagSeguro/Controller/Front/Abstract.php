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
 * PagSeguro Controller Front Abstract
 *
 */

class OsStudios_PagSeguro_Controller_Front_Abstract extends Mage_Core_Controller_Front_Action
{
    
    /**
     * Return PagSeguro Singleton Object
     *
     * @return OsStudios_PagSeguro_Model_Hpp
     */
    public function getPagSeguro()
    {
        return Mage::getSingleton('pagseguro/hpp');
    }
    
    
    /**
     * Return Checkout Object
     *
     * @return Mage_Checkout_Model_Session
     */
    public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }
    
    
    /**
     * Return Order's Store ID
     * 
     */
    function getOrderStoreId($orderId) {
        return Mage::getModel('sales/order')->load($orderId)->getStoreId();
    }
    
}

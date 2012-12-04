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
 * PagSeguro Paylink Block
 *
 */

class OsStudios_PagSeguro_Block_Paylink extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('osstudios/pagseguro/paylink.phtml');
    }

    /**
     * Returns Current Order Object
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('current_order');
    }

    /**
     * Returns PagSeguro Singleton Object
     *
     * @return OsStudios_Pagseguro_Model_Payment
     */
    public function getPagSeguro()
    {
        return Mage::getSingleton('pagseguro/hpp');
    }

    /**
     * Check if the payment button can be shown
     *
     * @return boolean
     */
    public function isShowPaylink()
    {
        $order = $this->getOrder();
        return (bool) ($order->getPayment()->getMethod() == $this->getPagSeguro()->getCode() AND $order->getState() == Mage_Sales_Model_Order::STATE_NEW);
    }

    /**
     * Returns Payment URL
     *
     * @return string
     */
    public function getPaymentUrl()
    {
        return $this->getPagSeguro()->getOrderPlaceRedirectUrl($this->getOrder()->getId());
    }
}
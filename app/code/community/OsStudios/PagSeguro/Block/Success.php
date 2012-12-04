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
 * PagSeguro Payment Success Page Block
 *
 */

class OsStudios_PagSeguro_Block_Success extends Mage_Core_Block_Template
{

    /**
     * Init Infos and Prepare for Output
     */
    protected function _beforeToHtml()
    {
        $this->_prepareOrder();
        return parent::_beforeToHtml();
    }

    /**
     * Escapes HTML Entities.
     * Method created to older Magento versions compatibility.
     *
     * @param   mixed $data
     * @param   array $allowedTags
     * @return  string
     */
    public function escapeHtml($data, $allowedTags = null)
    {
        return Mage::helper('pagseguro')->escapeHtml($data, $allowedTags);
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
     * Get Session Order ID, load it and check if it can be viewed, printed, etc.
     */
    protected function _prepareOrder()
    {
        $orderId = Mage::getSingleton('core/session')->getPagseguroOrderId();
        if ($orderId) {
            
        	$order = Mage::getModel('sales/order')->load($orderId);
            
            if ($order->getId()) {
                
            	$isVisible = !in_array($order->getState(), Mage::getSingleton('sales/order_config')->getInvisibleOnFrontStates());
                $isHolded = (boolean) ($order->getState() == Mage_Sales_Model_Order::STATE_HOLDED);
                
                $this->addData(array(
                    'order_id'  => $order->getIncrementId(),
                    'is_order_visible' => $isVisible,
                    'is_order_holded'  => $isHolded,
                    'can_print_order' => $isVisible,
                    'can_view_order'  => (boolean) (Mage::getSingleton('customer/session')->isLoggedIn() && $isVisible),
                    'view_order_url' => $this->getUrl('sales/order/view/', array('order_id' => $orderId)),
                    'print_url' => $this->getUrl('sales/order/print', array('order_id'=> $orderId)),
                    'pagseguro_transaction_id'  => $order->getPayment()->getPagseguroTransactionId(),
                    'pagseguro_payment_method'  => $order->getPayment()->getPagseguroPaymentMethod(),
                    'pagseguro_boleto_url'  => Mage::getModel('pagseguro/data')->getPagSeguroBoletoUrl($order->getPayment()->getPagseguroTransactionId()),
                    'pagseguro_payment_url'  => $this->getPagSeguro()->getOrderPlaceRedirectUrl($order->getId()),
                ));
            }
        }
    }
}

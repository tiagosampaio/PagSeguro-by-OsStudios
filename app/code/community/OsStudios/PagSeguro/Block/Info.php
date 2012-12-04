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
 * PagSeguro Payment Info Block
 *
 */

class OsStudios_PagSeguro_Block_Info extends Mage_Payment_Block_Info
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('osstudios/pagseguro/info.phtml');
    }
    
    protected function _beforeToHtml()
    {
        $this->_prepareInfo();
        return parent::_beforeToHtml();
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
    
    protected function _prepareInfo()
    {
        $pagseguro = $this->getPagSeguro();
        if (!$order = $this->getInfo()->getOrder()) {
            $order = $this->getInfo()->getQuote();
        }
        
        $transactionId = $this->getInfo()->getPagseguroTransactionId();
        $paymentMethod = $this->getInfo()->getPagseguroPaymentMethod();
        
        if ($paymentMethod == 'Boleto' AND $order->getState() == Mage_Sales_Model_Order::STATE_HOLDED) {
            $paymentMethod .= ' (<a href="' . Mage::getModel('pagseguro/data')->getPagSeguroBoletoUrl($transactionId) . '" onclick="this.target=\'_blank\'">reemitir</a>)';
        }

        $this->addData(array(
            'show_paylink' 		=> (boolean) !$transactionId && $order->getState() == Mage_Sales_Model_Order::STATE_NEW,
            'pay_url' 			=> $pagseguro->getOrderPlaceRedirectUrl($order->getId()),
            'show_info' 		=> (boolean) $transactionId,
            'transaction_id' 	=> $transactionId,
            'payment_method' 	=> $paymentMethod,
        ));
    }
}
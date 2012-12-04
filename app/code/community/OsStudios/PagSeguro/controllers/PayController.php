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
 * PagSeguro Payment Controller
 *
 */

class OsStudios_PagSeguro_PayController extends OsStudios_PagSeguro_Controller_Front_Abstract
{
    
    /**
     * Redirect Customer to PagSeguro Checkout Page
     *
     */
    public function redirectAction()
    {
        $pagseguro = $this->getPagSeguro();
        $session = $this->getCheckout();
        $orderId = $this->getRequest()->getParam('order_id');
        
        if (empty($orderId)) {
            $orderId = $session->getLastOrderId();
            $session->clear(); //Cleans the cart session
        }

        $order = Mage::getModel('sales/order')->load($orderId);
        
        if ($order->getId()) {
        
            // Envia email de confirmação ao cliente
            if(!$order->getEmailSent()) {
            	$order->sendNewOrderEmail();
    			$order->setEmailSent(true);
    			$order->save();
            }
            
            $order_redirect = false;
            
            // Checks if Payment Method is really the PagSeguro Method
            if ($order->getPayment()->getMethod() == $pagseguro->getCode()) {
                
                switch ($order->getState()) {
                    
                    case Mage_Sales_Model_Order::STATE_NEW:
						// Stores Order ID in session and display redirect form to PagSeguro
                        Mage::getSingleton("core/session")->setPagseguroOrderId($orderId);
                        
                        $html = $pagseguro->setOrder($order)->createRedirectForm();
                        
                        $this->getResponse()->setHeader("Content-Type", "text/html; charset=ISO-8859-1", true);
                        $this->getResponse()->setBody($html);
                        break;
                        
                    case Mage_Sales_Model_Order::STATE_HOLDED:
						// Redirect to Print Billet Page
                        if ($order->getPayment()->getPagseguroPaymentMethod() == "Boleto") {
                            $this->_redirectUrl($pagseguro->getPagSeguroBoletoUrl($order->getPayment()->getPagseguroTransactionId(), false));
                            break;
                        }
                        
                    default:
						// Redirect to Order's Page
                        $order_redirect = true;
                        break;
                }
                
            } else {
                $order_redirect = true;
            }
            
            if ($order_redirect) {
                $params = array();
                $params['_secure'] = true;
                $params['order_id'] = $orderId;
                $this->_redirect('sales/order/view', $params);
            }
            
        } else {
            $this->_redirect('');
        }
    }
    
    
    /**
     * Shows success page after payment.
     * 
     */
    public function successAction()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        if (!empty($orderId)) {
            Mage::getSingleton("core/session")->setPagseguroOrderId($orderId);
        } else {
            $orderId = Mage::getSingleton("core/session")->getPagseguroOrderId();
        }
        
        if ($orderId) {
            $storeId = $this->getOrderStoreId($orderId);
            
            $pagseguro = $this->getPagSeguro();
            if ($pagseguro->getConfigData('use_return_page_cms', $storeId)) {
                $this->_redirect($pagseguro->getConfigData('return_page', $storeId));
            } else {
                $this->loadLayout();
                
                $session = $this->getCheckout();
                $lastOrderId = $session->getLastOrderId();
                Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($lastOrderId)));
                
                $this->renderLayout();
            }
            
            Mage::getSingleton("core/session")->setPagseguroOrderId(null);
        } else {
            $this->_redirect('');
        }
    }
    

    /**
     * Returns the installments block
     * 
     */
    public function installmentsAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}
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

class OsStudios_PagSeguro_Model_Returns_Types_Api extends OsStudios_PagSeguro_Model_Returns_Types_Abstract
{
	
    /**
     * Runs before process any return
     */
    protected function _beforeProcessReturn()
    {
        $this->log($this->__('%s--- Initializing API Return Process ---', self::TABS));
    }


    /**
     * Runs before process any return
     */
    protected function _afterProcessReturn()
    {
        $this->log($this->__('%s--- Finishing API Return Process ---', self::TABS));
    }
	
    
    /** 
     * Request XML data information
     * 
     * @return Varien_Simplexml_Config
     */
    public function _requestTransactionInformation()
    {

        $this->log($this->__('%sRequesting Transaction Information to PagSeguro.', self::TABS));

        $post = $this->getPost();

        $client = new Zend_Http_Client(implode('/', array($this->getPagSeguroTransactionsUrl(), 'notifications', $post['notificationCode'])));

        if( $this->getConfigData('use_curl') ) {
            $adapter = new Zend_Http_Client_Adapter_Curl();

            $config = array('timeout' => 30, 'curloptions' => array( CURLOPT_SSL_VERIFYPEER => false));
            $adapter->setConfig($config);
            $client->setAdapter($adapter);
        }

        try {

            $credentials = Mage::getModel('pagseguro/credentials');
            $this->_params['email'] = $this->getCredentials()->getAccountEmail();
            $this->_params['token'] = $this->getCredentials()->getToken();

            $client->setMethod(Zend_Http_Client::GET)
                       ->setParameterGet($this->_params);

            $content = $client->request();
            $body = $content->getBody();

            if($body == self::PAGSEGURO_RETURN_RESPONSE_UNAUTHORIZED) {
                    $this->_response = self::PAGSEGURO_RETURN_RESPONSE_UNAUTHORIZED;
                    $this->log($this->__('%sResponse from PagSeguro: %s', self::TABS, $this->_response));
                    return $this;
            } elseif(!Mage::helper('pagseguro')->isXml($body)) {
                    $this->_response = self::PAGSEGURO_RETURN_RESPONSE_ERROR;
                    $this->log($this->__('%sResponse from PagSeguro: %s', self::TABS, $this->_response));
                    return $this;
            }

            $xml = new Varien_Simplexml_Config($body);
            $this->log($this->__('%sXML data successfully returned.', self::TABS));

            return $xml;

        } catch (Mage_Core_Exception $e) {
            $this->log($this->__('%sValidation Error: %s.', self::TABS, $e->getMessage()));
        } catch (Exception $e) {
            $this->log($this->__('%sValidation Error: %s.', self::TABS, $e->getMessage()));
        }
    }
	
	
    /**
    * Process return
    * 
    * @return OsStudios_PagSeguro_Model_Returns_Types_Api
    */
    public function processReturn()
    {
       $this->_beforeProcessReturn();

       $xml = $this->_requestTransactionInformation();

       $data = $xml->getNode();

       $model = Mage::getModel('pagseguro/returns_types_transactions_transaction');
       $model->setTransactionType(self::PAGSEGURO_RETURN_TYPE_API)
             ->setTransactionData($data->asArray())
             ->processTransaction();

       $this->_response = self::PAGSEGURO_RETURN_RESPONSE_AUTHORIZED;
       $this->_success = true;

       $this->_afterProcessReturn();

       return $this;
    }
    
}

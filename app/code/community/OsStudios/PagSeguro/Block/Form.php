<?php
class OsStudios_PagSeguro_Block_Form extends Mage_Payment_Block_Form
{
    
    protected function _contruct()
    {
        return parent::_contruct();
    }
    
    public function _beforeToHtml()
    {    
        $this->_prepareForm();
        return parent::_beforeToHtml();
    }
    
    /**
     * Returns PagSeguro Api Singleton Object
     *
     * @return OsStudios_Pagseguro_Model_Api
     */
    public function getPagSeguroApi()
    {
        return Mage::getSingleton('pagseguro/api');
    }
    
    /**
     * Returns PagSeguro Hpp Singleton Object
     *
     * @return OsStudios_Pagseguro_Model_Hpp
     */
    public function getPagSeguroHpp()
    {
        return Mage::getSingleton('pagseguro/hpp');
    }
    
    /**
     * Returns PagSeguro Singleton Object
     *
     * @return OsStudios_Pagseguro_Model_Payment
     */
    public function getPagSeguro()
    {
        return $this->getPagSeguroHpp();
    }
    
    protected function _prepareForm()
    {
        return $this;
    }
    
    public function getShowMessage()
    {
        return true;
    }
    
    public function getMessage()
    {
        return Mage::helper('pagseguro')->getMethodConfigData('message', $this->getMethodCode());
    }
    
}
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

class OsStudios_PagSeguro_Model_Hpp extends OsStudios_PagSeguro_Model_Payment
{
    
    protected $_code = 'pagseguro_hpp';
    protected $_formBlockType = 'pagseguro/form';
    protected $_infoBlockType = 'pagseguro/info';
    
    protected $_canUseInternal = true;
    protected $_canUseForMultishipping = false;
    protected $_canCapture = true;
    
    
    /**
     * 
     * Set the $_POST information
     * 
     * @param Mage_Core_Controller_Request_Http $post
     */
    public function setPostData(Mage_Core_Controller_Request_Http $request)
    {    	
    	$post = $request->getPost();
    	
    	$this->setPost($post);
    	return $this;
    }
    
    
    /**
     * 
     * Process before processReturn method.
     * 
     * @return OsStudios_PagSeguro_Model_Payment
     * 
     * @uses $this->log()
     */
    public function _beforeProcessReturn()
    {
    	$this->log('<!--[ '.Mage::helper('pagseguro')->__('Beginning of Return').' ]-->');
            
        // Saves $_POST Data
        $this->log('<!--[ '.Mage::helper('pagseguro')->__('Post Data').' ]-->');
        $this->log($this->getPost());
        $this->log('<!--[ '.Mage::helper('pagseguro')->__('End of Post Data').' ]-->');
        
        return $this;
    }
    
    
    /**
     * 
     * Process before processReturn method.
     * 
     * @return OsStudios_PagSeguro_Model_Payment
     * 
     * @uses $this->log()
     */
    public function _afterProcessReturn()
    {
    	$this->log('<!--[ '.Mage::helper('pagseguro')->__('Ending of Return').' ]-->');
		$this->log(' ----------- >> ----------- ');
		
		return $this;
    }
    
    
	/**
	 * getOrderPlaceRedirectUrl
     * 
     * Cria a URL de redirecionamento ao PagSeguro, utilizando
     * o ID do pedido caso este seja informado
	 *
	 * @param int $orderId     ID pedido
	 *
	 * @return string
	 */
    public function getOrderPlaceRedirectUrl($orderId = 0)
	{
	   $params = array();
       $params['_secure'] = true;
       
	   if ($orderId != 0 && is_numeric($orderId)) {
	       $params['order_id'] = $orderId;
	   }
       
        return Mage::getUrl('pagseguro/pay/redirect', $params);
    }
    
    
	/**
	 * createRedirectForm
     * 
     * Cria o formulário de redirecionamento ao PagSeguro
	 *
	 * @return string
     * 
     * @uses $this->getCheckoutFormFields()
	 */
    public function createRedirectForm()
    {
    	$form = new Varien_Data_Form();
        $form->setAction(Mage::getModel('pagseguro/data')->getPagSeguroUrl())
            ->setId('pagseguro_checkout')
            ->setName('pagseguro_checkout')
            ->setMethod('POST')
            ->setUseContainer(true);
        
        $fields = $this->getCheckoutFormFields();
        foreach ($fields as $field => $value) {
            $form->addField($field, 'hidden', array('name' => $field, 'value' => $value));
        }
        
        $submit_script = 'document.getElementById(\'pagseguro_checkout\').submit();';
        
		$html  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		$html .= '<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="pt-BR">';
		$html .= '<head>';
		$html .= '<meta http-equiv="Content-Language" content="pt-br" />';
		$html .= '<meta name="language" content="pt-br" />';
		$html .= '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />';
		$html .= '<style type="text/css">';
		$html .= '* { font-family: Arial; font-size: 16px; line-height: 34px; text-align: center; color: #222222; }';
		$html .= 'small, a, a:link:visited:active, a:hover { font-size: 13px; line-height: normal; font-style: italic; }';
		$html .= 'a, a:link:visited:active { font-weight: bold; text-decoration: none; }';
		$html .= 'a:hover { font-weight: bold; text-decoration: underline; color: #555555; }';
		$html .= '</style>';
		$html .= '</head>';
		$html .= '<body onload="' . $submit_script . '">';
        $html .= 'Você será redirecionado ao <strong>PagSeguro</strong> em alguns instantes.<br />';
        $html .= '<small>Se a página não carregar, <a href="#" onclick="' . $submit_script . ' return false;">clique aqui</a>.</small>';
        $html .= $form->toHtml();
        $html .= '</body></html>';

        return utf8_decode($html);
        
    }
    
    
	/**
	 * getCheckoutFormFields
     * 
     * Gera os campos para o formulário de redirecionamento ao Pagseguro
	 *
	 * @return array
	 *
	 * @uses $this->getOrder()
	 */
    public function getCheckoutFormFields()
    {
        $order = $this->getOrder();
        
        // Utiliza endereço de cobrança caso produto seja virtual/para download
        $address = $order->getIsVirtual() ? $order->getBillingAddress() : $order->getShippingAddress();
        
        // Resgata CEP
        $cep = preg_replace('@[^\d]@', '', $address->getPostcode());
        
        // Dados de endereço
        if ($this->getConfigData('custom_address_model', $order->getStoreId())) {
            $endereco = $address->getStreet(1);
            $numero = $address->getStreet(2);
            $complemento = $address->getStreet(3);
            $bairro = $address->getStreet(4);
        } else {
            list($endereco, $numero, $complemento) = Mage::helper('pagseguro')->trataEndereco($address->getStreet(1));
            $bairro = $address->getStreet(2);
        }
        
        // Formata o telefone
        list($ddd, $telefone) = Mage::helper('pagseguro')->trataTelefone($address->getTelephone());
        
        // Monta os dados para o formulário
        $sArr = array(
                //'encoding'          => 'utf-8',
                //'email_cobranca'    => $this->getConfigData('account_email', $order->getStoreId()),
                'email_cobranca'    => Mage::getStoreConfig('payment/pagseguro_config/account_email', $order->getStoreId()),
                'Tipo'              => "CP",
                'Moeda'             => "BRL",
                'ref_transacao'     => $order->getRealOrderId(),
                'cliente_nome'      => $address->getFirstname() . ' ' . $address->getLastname(),
                'cliente_cep'       => $cep,
                'cliente_end'       => $endereco,
                'cliente_num'       => $numero,
                'cliente_compl'     => $complemento,
                'cliente_bairro'    => $bairro,
                'cliente_cidade'    => $address->getCity(),
                'cliente_uf'        => $address->getRegionCode(),
                'cliente_pais'      => $address->getCountry(),
                'cliente_ddd'       => $ddd,
                'cliente_tel'       => $telefone,
                'cliente_email'     => $order->getCustomerEmail(),
                );
        
        
        $i = 1;
        $items = $order->getAllVisibleItems();
        
		$shipping_amount = $order->getBaseShippingAmount();
        $tax_amount = $order->getBaseTaxAmount();
        $discount_amount = $order->getBaseDiscountAmount();
        
        $priceGrouping = $this->getConfigData('price_grouping', $order->getStoreId());
        $shippingPrice = $this->getConfigData('shipping_price', $order->getStoreId());
        
        if ($priceGrouping) {
            
            $order_total = $order->getBaseSubtotal() + $tax_amount + $discount_amount;
            if ($shippingPrice == 'grouped') {
                $order_total += $shipping_amount;
            }
            $item_descr = $order->getStoreName(2) . " - Pedido " . $order->getRealOrderId();
            $item_price = Mage::helper('pagseguro')->formatNumber($order_total);
            $sArr = array_merge($sArr, array(
                'item_descr_'.$i   => substr($item_descr, 0, 100),
                'item_id_'.$i      => $order->getRealOrderId(),
                'item_quant_'.$i   => 1,
                'item_valor_'.$i   => $item_price,
            ));
            $i++;
                
        } else {
            
            if ($items) {
                foreach ($items as $item) {
                    $item_price = 0;
                    $item_qty = $item->getQtyOrdered() * 1;
                    if ($children = $item->getChildrenItems()) {
                        foreach ($children as $child) {
                            $item_price += $child->getBasePrice() * $child->getQtyOrdered() / $item_qty;
                        }
                        $item_price = Mage::helper('pagseguro')->formatNumber($item_price);
                    }
                    if (!$item_price) {
        				$item_price = Mage::helper('pagseguro')->formatNumber($item->getBasePrice());
                    }
                    $sArr = array_merge($sArr, array(
                        'item_descr_'.$i   => substr($item->getName(), 0, 100),
                        'item_id_'.$i      => substr($item->getSku(), 0, 100),
                        'item_quant_'.$i   => $item_qty,
                        'item_valor_'.$i   => $item_price,
                    ));
                    $i++;
                }
            }
            
            if ($tax_amount > 0) {
                $tax_amount = Mage::helper('pagseguro')->formatNumber($tax_amount);
                $sArr = array_merge($sArr, array(
                    'item_descr_'.$i   => "Taxa",
                    'item_id_'.$i      => "taxa",
                    'item_quant_'.$i   => 1,
                    'item_valor_'.$i   => $tax_amount,
                ));
                $i++;
            }
                
            if ($discount_amount != 0) {
                $discount_amount = Mage::helper('pagseguro')->formatNumber($discount_amount);
                if (preg_match("/^1\.[23]/i", Mage::getVersion())) {
                    $discount_amount = -$discount_amount;
                }
                $sArr = array_merge($sArr, array(
                    'extras'   => $discount_amount,
                ));
            }
                
        }
        
        if ($shipping_amount > 0) {
            $shipping_amount = Mage::helper('pagseguro')->formatNumber($shipping_amount);
            switch ($shippingPrice) {
                case 'grouped':
                    if ($priceGrouping) {
                        break;
                    }
                case 'product':
                    // passa o valor do frete como um produto
                    $sArr = array_merge($sArr, array(
                        'item_descr_'.$i   => substr($order->getShippingDescription(), 0, 100),
                        'item_id_'.$i      => "frete",
                        'item_quant_'.$i   => 1,
                        'item_valor_'.$i   => $shipping_amount,
                    ));
                    $i++;
                    break;
                    
                case 'separated':
                default:
                    // passa o valor do frete separadamente
                    $sArr = array_merge($sArr, array('item_frete_1' => $shipping_amount));
                    
            }
        }
        
        $rArr = array();
        foreach ($sArr as $k => $v) {
            // troca caractere '&' por 'e'
            $value =  str_replace("&", "e", $v);
            $rArr[$k] =  $value;
        }
        
        return $rArr;
    }

}

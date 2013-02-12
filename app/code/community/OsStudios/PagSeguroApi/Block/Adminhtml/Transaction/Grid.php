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

class OsStudios_PagSeguroApi_Block_Adminhtml_Transaction_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

	public function __construct()
	{
		parent::__construct();
		$this->setId("transactionGrid");
		$this->setDefaultSort("id");
		$this->setDefaultDir("ASC");
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getModel("pagseguroapi/returns_transaction")->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
		$this->addColumn('reference', array(
			'header' => Mage::helper('pagseguroapi')->__('Reference'),
			'index' => 'reference',
		));

		$this->addColumn('code', array(
			'header' => Mage::helper('pagseguroapi')->__('Transaction ID'),
			'index' => 'code',
		));

		$this->addColumn('type', array(
			'header' => Mage::helper('pagseguroapi')->__('Type'),
			'index' => 'type',
			'type' => 'options',
			'options' => array(
				1 => 'Transação'
			),
		));

		$this->addColumn('payment_method_type', array(
			'header' => Mage::helper('pagseguroapi')->__('Payment Method Type'),
			'index' => 'payment_method_type',
			'type' => 'options',
			'options' => array(
				1 => 'Cartão de Crédito',
				2 => 'Boleto',
				3 => 'Débito online (TEF)',
				4 => 'Saldo PagSeguro',
				5 => 'Oi Paggo',
			),
		));

		$this->addColumn('payment_method_code', array(
			'header' => Mage::helper('pagseguroapi')->__('Payment Method Code'),
			'index' => 'payment_method_code',
		));

		$this->addColumn('status', array(
			'header' => Mage::helper('pagseguroapi')->__('Status'),
			'index' => 'status',
			'type' => 'options',
			'options'=> array(
				1 => $this->__('Waiting for Payment'),
				2 => $this->__('In Analisys'),
				3 => $this->__('Paid'),
				4 => $this->__('Available'),
				5 => $this->__('In Dispute'),
				6 => $this->__('Returned'),
				7 => $this->__('Canceled'),
			),
		));

		$this->addColumn('fee_amount', array(
			'header' => Mage::helper('pagseguroapi')->__('Fee Amount'),
			'index' => 'fee_amount',
			'type' => 'currency'
		));

		$this->addColumn('installment_count', array(
			'header' => Mage::helper('pagseguroapi')->__('Installment Count'),
			'index' => 'installment_count',
		));

		$this->addColumn('received_from', array(
			'header' => Mage::helper('pagseguroapi')->__('Received From'),
			'index' => 'received_from',
			'type' => 'options',
			'options' => array(
				1 => $this->__('Notifications'),
				2 => $this->__('Consults'),
			),
		));

		$this->addColumn('last_event_date', array(
			'header'    => Mage::helper('pagseguroapi')->__('Last Event Date'),
			'index'     => 'last_event_date',
			'type'      => 'datetime',
		));

		$this->addColumn('created_at', array(
			'header' => Mage::helper('pagseguroapi')->__('Created At'),
			'index' => 'created_at',
			'type' => 'datetime',
		));

		$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
		$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

		return parent::_prepareColumns();
	}

	public function getRowUrl($row)
	{
	   return;
	}
		
	protected function _prepareMassaction()
	{
		/*
		$this->setMassactionIdField('id');
		$this->getMassactionBlock()->setFormFieldName('ids');
		$this->getMassactionBlock()->setUseSelectAll(true);
		$this->getMassactionBlock()->addItem('remove_transaction', array(
				 'label'=> Mage::helper('pagseguroapi')->__('Remove Transaction'),
				 'url'  => $this->getUrl('* /adminhtml_transaction/massRemove'),
				 'confirm' => Mage::helper('pagseguroapi')->__('Are you sure?')
			));
		*/
		return $this;
	}
	
	/*
	static public function getOptionArray5()
	{
        $data_array=array(); 
		$data_array[0]='Aguardando pagamento';
		$data_array[1]='Em análise';
		$data_array[2]='Pago';
		$data_array[3]='Disponível';
		$data_array[4]='Em disputa';
		$data_array[5]='Devolvida';
		$data_array[6]='Cancelado';
        return($data_array);
	}
	*/

	/*
	static public function getValueArray5()
	{
        $data_array=array();
		foreach(Silksoftware_pagseguroapi_Block_Adminhtml_Transaction_Grid::getOptionArray5() as $k=>$v){
           $data_array[]=array('value'=>$k,'label'=>$v);		
		}
        return($data_array);

	}
	*/

}
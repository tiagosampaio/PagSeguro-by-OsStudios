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

abstract class OsStudios_PagSeguro_Model_Returns_Types_Abstract extends OsStudios_PagSeguro_Model_Returns
{
	
	const TABS = '		';
	
	/**
     * Handle the post data
     * 
     * @var (array)
     */
	protected $_post = null;
	
	/**
     * Handle the parameters used in consults
     * 
     * @var (array)
     */
    protected $_params = array();
	
	/**
	 * Handle the process result
	 * 
	 * @var (bool)
	 */
	protected $_success = false;
	
	/**
	 * Handle the response result
	 * 
	 * @var (mixed)
	 */
	protected $_response = null;
	
	
	/**
	 * Runs before process any return
	 */
	protected function _beforeProcessReturn()
	{
		
	}
	
	
	/**
	 * Runs before process any return
	 */
	protected function _afterProcessReturn()
	{
		
	}
	
	
	/**
	 * Sets the post data
	 * 
	 * @param (mixed) $post
	 */
	public function setPostData($post)
	{
		$this->_post = $post;
		$this->setPost($this->_post);
		return $this;
	}
	
	
	/**
	 * Return true if the returned has processed
	 * 
	 * @return (bool)
	 */
	public function isSuccess()
	{
		return $this->_success;
	}
	
	
	/**
	 * Return response of the return
	 * 
	 * @return (bool)
	 */
	public function getResponse()
	{
		return $this->_response;
	}
    
    
    /** 
	 * Process return
	 * 
	 * @return OsStudios_PagSeguro_Model_Returns_Types_Abstract
	 */
	public function processReturn()
	{
		return $this;
	}
    
}

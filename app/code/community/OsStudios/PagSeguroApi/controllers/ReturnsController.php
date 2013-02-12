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

class OsStudios_PagSeguroApi_PayController extends OsStudios_PagSeguroApi_Controller_Front_Action
{
    
    public function indexAction()
    {
        $request = $this->getRequest();
        
        if($this->_validateRequest($request)) {
            $post = $request->getPost();
            $url = Mage::getSingleton('pagseguroapi/data')->getPagSeguroNotificationUrl($post['notificationCode']);

            Mage::log($post, null, '$post.log');

            $consulter = Mage::getModel('pagseguroapi/consulter');
            $consulter->consultByNotificationId($post['notificationCode']);
        }
    }

    protected function _validateRequest($request)
    {
        if(!$request->isPost()) {
            return false;
        }

        $post = $request->getPost();

        if(!$post['notificationCode'] || !$post['notificationType']) {
            return false;
        }

        if(!($post['notificationType'] == 'transaction')) {
            return false;
        }

        /**
         * Validates Format: XXXXXX-XXXXXXXXXXXX-XXXXXXXXXXXX-XXXXXX
         */
        if(!preg_match('/^[0-9A-Z]{6}\-[0-9A-Z]{12}\-[0-9A-Z]{12}\-[0-9A-Z]{6}$/'), strtoupper($post['notificationCode'])) {
            return false;
        }

        return true;
    }
    
}
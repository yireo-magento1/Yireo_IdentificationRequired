<?php
/**
 * Yireo IdentificationRequired
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * IdentificationRequired controller for the frontend
 */
class Yireo_IdentificationRequired_PrecheckoutController extends Yireo_FormApi_Controller_Frontend_Form
{
    public function saveAction()
    {
        $check = Mage::app()->getRequest()->getParam('check');
        $redirect = base64_decode(Mage::app()->getRequest()->getParam('uenc'));
        if(empty($redirect)) $redirect = Mage::getUrl('checkout/onepage');

        $customer = Mage::getModel('customer/session')->getCustomer();
        if(!empty($customer) && $customer->getId() > 0) {
            try {
                $customer->setData('identificationrequired_agreement', 1);
                $customer->save();
            } catch(Exception $e) {}
        }

        Mage::app()->getResponse()->setRedirect($redirect);
    }

    /**
     * Display a page before the checkout
     *
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}

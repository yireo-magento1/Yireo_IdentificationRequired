<?php
/**
 * Yireo IdentificationRequired
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * IdentificationRequired controller for the frontend
 */
class Yireo_IdentificationRequired_PrecheckoutController extends Yireo_FormApi_Controller_Frontend_Form
{
    /**
     * Controller Action - Save the current precheckout action
     */
    public function saveAction()
    {
        Mage::helper('identificationrequired/precheckout')->setSessionFlag();

        $redirect = $this->getRedirectUrl();

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
     * Controller Action - Display a page before the checkout
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function getRedirectUrl()
    {
        $redirect = base64_decode(Mage::app()->getRequest()->getParam('uenc'));
        if(empty($redirect)) {
            $redirect = Mage::getUrl('checkout/onepage');
        }

        return $redirect;
    }
}

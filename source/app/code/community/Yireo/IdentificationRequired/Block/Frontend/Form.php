<?php
/**
 * Yireo IdentificationRequired
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

class Yireo_IdentificationRequired_Block_Frontend_Form extends Mage_Core_Block_Template
{
    /**
     * Constructor method
     */
    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('identificationrequired/form.phtml');
        $this->setPageTitle($this->__('My Identification'));
    }

    /**
     * Return the form model
     *
     * @return Yireo_FormApi_Model_Form
     */
    public function getForm()
    {
        $form = Mage::getModel('formapi/form');

        $currentStatus = $this->getStatus();
        if($currentStatus == 'accepted') $form->setFeature('disable', 1);

        $formXml = Mage::helper('identificationrequired')->getFormXml();
        $form->loadFile($formXml);

        $data = $this->getCustomer()->getData();
        $form->setFieldData($data);

        return $form;
    }

    /**
     * Return the current customer
     *
     * @return Mage_Customer_Model_Customer
     */
    public function getCustomer()
    {
        return Mage::getModel('customer/session')->getCustomer();
    }

    /**
     * Return the current identification status of a customer
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->getCustomer()->getData('identificationrequired_status');
    }
}
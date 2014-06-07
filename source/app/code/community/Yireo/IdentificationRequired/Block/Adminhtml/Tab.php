<?php
/**
 * Yireo IdentificationRequired for Magento
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright (C) 2014 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * IdentificationRequired tab-block
 */
class Yireo_IdentificationRequired_Block_Adminhtml_Tab extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    public function __construct()
    {
        $this->setTemplate('identificationrequired/tab.phtml');

    }

    public function getCustomtabInfo(){

        $customer = Mage::registry('current_customer');
        return null;
    }

    /**
     * Return Tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Identification');
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Identification');
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        $customer = Mage::registry('current_customer');
        return (bool)$customer->getId();
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Defines after which tab, this tab should be rendered
     *
     * @return string
     */
    public function getAfter()
    {
        return 'tags';
    }

    public function getForm()
    {
        $formXml = Mage::helper('identificationrequired')->getFormXml();
        $form = Mage::getModel('formapi/form')->loadFile($formXml);
        $form->setFieldData($this->getCustomerData());
        return $form;
    }

    public function getCustomer()
    {
        $customerId = Mage::app()->getRequest()->getParam('id');
        $customer = Mage::getModel('customer/customer')->load($customerId);
        return $customer;
    }

    public function getCustomerData()
    {
        return $this->getCustomer()->getData();
    }

    public function getCustomerDateOfBirth()
    {
        $customer = $this->getCustomer();
        $birth_date = $customer->getData('dob');
        if(empty($birth_date)) return $this->__('Unknown');
        $birth_date = strftime('%d %B %Y', strtotime($birth_date));
        return $birth_date;
    }

    public function getCustomerAge()
    {
        $customer = $this->getCustomer();
        $birth_date = strtotime($customer->getData('dob'));
        if(!is_numeric($birth_date)) return $this->__('Unknown');
        $age = intval(substr(date('Ymd') - date('Ymd', $birth_date), 0, -4));
        return $age.' '.$this->__('years');
    }
}

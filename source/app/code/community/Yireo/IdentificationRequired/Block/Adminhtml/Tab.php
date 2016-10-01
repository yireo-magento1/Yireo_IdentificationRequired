<?php
/**
 * Yireo IdentificationRequired for Magento
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * IdentificationRequired tab-block
 */
class Yireo_IdentificationRequired_Block_Adminhtml_Tab extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setTemplate('identificationrequired/tab.phtml');

        parent::__construct();
    }

    /**
     * Return the tab information
     *
     * @return null
     */
    public function getCustomtabInfo()
    {

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

    /**
     * Return the form
     *
     * @return bool|Yireo_FormApi_Model_Form
     */
    public function getForm()
    {
        $formXml = Mage::helper('identificationrequired')->getFormXml();
        $form = Mage::getModel('formapi/form')->loadFile($formXml);
        $form->setFieldData($this->getCustomerData());

        return $form;
    }

    /**
     * Return a customer entity
     *
     * @return Mage_Customer_Model_Customer
     */
    public function getCustomer()
    {
        $customerId = Mage::app()->getRequest()->getParam('id');
        $customer = Mage::getModel('customer/customer')->load($customerId);

        return $customer;
    }

    /**
     * Return an array with customer data
     *
     * @return mixed
     */
    public function getCustomerData()
    {
        return $this->getCustomer()->getData();
    }

    /**
     * Return the date of birth of a customer
     *
     * @return mixed|string
     */
    public function getCustomerDateOfBirth()
    {
        $customer = $this->getCustomer();
        $birth_date = $customer->getData('dob');

        if (empty($birth_date)) {
            return $this->__('Unknown');
        }

        $birth_date = strftime('%d %B %Y', strtotime($birth_date));
        return $birth_date;
    }

    /**
     * Return the customer age
     *
     * @return string
     */
    public function getCustomerAge()
    {
        $customer = $this->getCustomer();
        $birth_date = strtotime($customer->getData('dob'));

        if (!is_numeric($birth_date)) {
            return $this->__('Unknown');
        }

        $age = intval(substr(date('Ymd') - date('Ymd', $birth_date), 0, -4));

        return $age . ' ' . $this->__('years');
    }
}

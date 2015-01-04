<?php
/**
 * Yireo IdentificationRequired
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

class Yireo_IdentificationRequired_Block_Frontend_Form extends Mage_Core_Block_Template
{
    /*
     * Constructor method
     */
    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('identificationrequired/form.phtml');
        $this->setPageTitle($this->__('My Identification'));
    }

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

    public function getCustomer()
    {
        return Mage::getModel('customer/session')->getCustomer();
    }

    public function getStatus()
    {
        return $this->getCustomer()->getData('identificationrequired_status');
    }
}
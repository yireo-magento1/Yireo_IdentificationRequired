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
class Yireo_IdentificationRequired_IndexController extends Yireo_FormApi_Controller_Frontend_Form
{
    /**
     * Controller action - Save customer data
     */
    public function saveAction()
    {
        if($this->validate() == true) {

            $form = $this->getForm();
            $customer = Mage::getModel('customer/session')->getCustomer();

            $fieldNames = $this->getFieldNames();
            $validatedData = $form->getValidatedData();

            if (!empty($fieldNames) && !empty($validatedData)) {

                foreach ($validatedData as $postName => $postValue) {
                    if (in_array($postName, $fieldNames)) {
                        $customer->setData($postName, $postValue);
                    }
                }

                try {
                    $customer->save();
                    Mage::getModel('core/session')->addSuccess($this->__('Successfully saved your data'));
                } catch(Exception $e) {
                    Mage::getModel('core/session')->addError($this->__('An exception occurred while trying to save your data'));
                }
            }
        }

        $this->_redirect('identificationrequired/index/form');
    }

    /**
     * Controller action - Display fields of this customer
     */
    public function formAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Display fields of this customer
     */
    public function indexAction()
    {
        $this->_redirect('identificationrequired/index/form');
    }

    /**
     * Return the form XML file, used by Form API parent controller
     *
     * @return string
     */
    public function getFormXml()
    {
        return Mage::helper('identificationrequired')->getFormXml();
    }
}

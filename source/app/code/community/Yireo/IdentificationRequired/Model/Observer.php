<?php
/**
 * Yireo IdentificationRequired for Magento
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * IdentificationRequired Observer
 */
class Yireo_IdentificationRequired_Model_Observer
{
    /**
     * Event "customer_load_after"
     */
    public function customerLoadAfter($observer)
    {
        $customer = $observer->getEvent()->getDataObject();

        $collection = Mage::getModel('identificationrequired/value')->getCollection()
            ->addFieldToFilter('customer_id', (int)$customer->getId())
            ;

        foreach ($collection as $customField) {
            $customer->setData($customField->getField(), $customField->getValue());
        }

        return $this;
    }

    /**
     * Event "customer_save_after"
     */
    public function customerSaveAfter($observer)
    {
        $customer = $observer->getEvent()->getDataObject();
        if (empty($customer)) $customer = $observer->getEvent()->getCustomer();

        $form = Mage::helper('identificationrequired')->getForm();
        $fieldNames = $form->getFieldNames();

        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $table = Mage::getSingleton('core/resource')->getTableName('identificationrequired/value');

        // Loop through the fields and insert the new values
        foreach ($fieldNames as $fieldName) {

            $write->query('DELETE FROM `' . $table . '` WHERE `field`="' . $fieldName . '" AND `customer_id` = ' . (int)$customer->getId());

            $value = addslashes($customer->getData($fieldName));
            $query = 'INSERT INTO `' . $table . '` SET `field`="' . $fieldName . '", `customer_id` = ' . (int)$customer->getId() . ', `value`="' . $value . '"';
            $write->query($query);
        }

        return $this;
    }

    /**
     * Event "adminhtml_customer_save_after"
     */
    public function adminhtmlCustomerSaveAfter($observer)
    {
        $customer = $observer->getEvent()->getDataObject();
        if (empty($customer)) $customer = $observer->getEvent()->getCustomer();

        $form = Mage::helper('identificationrequired')->getForm();
        $form->validate();
        $validatedData = $form->getValidatedData();
        $fieldNames = $form->getFieldNames();

        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $table = Mage::getSingleton('core/resource')->getTableName('identificationrequired/value');

        // Loop through the fields and insert the new values
        foreach ($fieldNames as $fieldName) {

            // Fetch the value from POST
            if (isset($validatedData[$fieldName])) {
                $write->query('DELETE FROM `' . $table . '` WHERE `field`="' . $fieldName . '" AND `customer_id` = ' . (int)$customer->getId());

                $value = addslashes($validatedData[$fieldName]);
                $query = 'INSERT INTO `' . $table . '` SET `field`="' . $fieldName . '", `customer_id` = ' . (int)$customer->getId() . ', `value`="' . $value . '"';
                $write->query($query);
            }
        }

        return $this;
    }

    /**
     * Event "controller_action_predispatch"
     */
    public function controllerActionPredispatch($observer)
    {
        if (Mage::helper('identificationrequired')->isAjax()) {
            return $this;
        }

        // Get the variables
        $module = Mage::app()->getRequest()->getModuleName();
        $controller = Mage::app()->getRequest()->getControllerName();
        $action = Mage::app()->getRequest()->getActionName();
        $currentUrl = Mage::helper('core/url')->getCurrentUrl();

        $match = false;
        if (in_array($controller, array('checkout_onepage', 'onepage', 'multishipping'))) {
            $match = true;
        } elseif ($module == 'onestep') {
            $match = true;
        }

        if ($match == false) {
            return $this;
        }

        if (in_array($action, array('progress', 'shippingMethod', 'review', 'success'))) {
            return $this;
        }

        // Show this once per visit
        if (Mage::getSingleton('core/session')->getPrecheckoutDisplayed() == true) {
            Mage::getSingleton('core/session')->setPrecheckoutDisplayed(false);
            return $this;
        }

        $showWarning = false;

        $products = Mage::helper('identificationrequired')->getCartProductsWithIdentificationRequired();
        if (!empty($products)) {
            foreach($products as $product) {
                $rules = Mage::helper('identificationrequired')->getRulesByProduct($product);
                if(!empty($rules)) {
                    $match = false;
                    foreach($rules as $rule) {
                        if($rule->getUsePrecheckout() == 1) {
                            $match = true;
                            break;
                        }
                    }
                    if($match == true) {
                        $showWarning = true;
                        break;
                    }
                }
            }
        }

        if ($showWarning) {
            $url = Mage::getUrl('identificationrequired/precheckout/index', array('uenc' => base64_encode($currentUrl)));
            Mage::app()->getResponse()->setRedirect($url);
            return $this;
        }

        return $this;
    }

    public function salesOrderSaveAfter($observer)
    {
        $order = $observer->getEvent()->getObject();
        if(empty($order)) $order = $observer->getEvent()->getDataObject();
        if(empty($order)) $order = $observer->getEvent()->getOrder();

        $customerId = $order->getCustomerId();
        if($customerId > 0) {
            $customer = Mage::getModel('customer/customer')->load($customerId);
        } else {
            $customer = null;
        }

        $orderStatusHistory = null;
        foreach ($order->getAllItems() as $item) {

            $product = $item->getProduct();
            if( Mage::helper('identificationrequired')->hasCustomerIdentified($customer)) {
                $lockedDoShip = false;
            } elseif (Mage::helper('identificationrequired')->isProductWithIdentificationRequired($product) == true) {
                $lockedDoShip = true;
            } else {
                $lockedDoShip = false;
            }

            if ($item->getLockedDoShip() != $lockedDoShip) {

                $item->setLockedDoShip($lockedDoShip);
                $item->save();

                if($lockedDoShip == true) {
                    $orderStatusHistory = Mage::helper('identificationrequired')->__('Identification required. Locked shipping.');
                } else {
                    $orderStatusHistory = Mage::helper('identificationrequired')->__('Identification approved. Unlocked shipping.');
                }
            }
        }

        if(!empty($orderStatusHistory)) {
            $order->addStatusToHistory($order->getStatus(), $orderStatusHistory);
        }

        return $this;
    }
}


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
 * IdentificationRequired Observer
 */
class Yireo_IdentificationRequired_Model_Observer
{
    /**
     * Event "customer_load_after"
     *
     * @param $observer
     *
     * @return $this
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
     *
     * @param $observer
     *
     * @return $this
     */
    public function customerSaveAfter($observer)
    {
        $customer = $observer->getEvent()->getDataObject();
        if (empty($customer)) {
            $customer = $observer->getEvent()->getCustomer();
        }

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
     *
     * @param $observer
     *
     * @return $this
     */
    public function adminhtmlCustomerSaveAfter($observer)
    {
        $customer = $observer->getEvent()->getDataObject();
        if (empty($customer)) {
            $customer = $observer->getEvent()->getCustomer();
        }

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

    public function checkoutCartUpdateItemsAfter()
    {
        Mage::getSingleton('core/session')->setPrecheckoutDisplayed(false);

        return $this;
    }

    public function checkoutCartProductAddAfter($observer)
    {
        Mage::getSingleton('core/session')->setPrecheckoutDisplayed(false);

        return $this;
    }

    /**
     * Event "controller_action_predispatch"
     *
     * @param $observer
     *
     * @return $this
     */
    public function controllerActionPredispatch($observer)
    {
        if (Mage::helper('identificationrequired')->isAjax()) {
            return $this;
        }

        // Get the page variables
        $module = Mage::app()->getRequest()->getModuleName();
        $controller = Mage::app()->getRequest()->getControllerName();
        $action = Mage::app()->getRequest()->getActionName();

        // Define when to show the precheckout
        $actionSkip = array('progress', 'shippingMethod', 'review', 'success');
        $controllerMatches = array('checkout_onepage', 'onepage', 'multishipping');
        $moduleMatches = array('onestep');

        // Check for the precheckout
        if (in_array($controller, $controllerMatches) || in_array($module, $moduleMatches)) {
            if (!in_array($action, $actionSkip)) {
                $this->showPrecheckout();
                return $this;
            }
        }

        // Define when to show the checkout notice
        $controllerMatches = array('cart');
        $moduleMatches = array('checkout');

        // Check for the checkout notice
        if (in_array($controller, $controllerMatches) && in_array($module, $moduleMatches)) {
            $this->showCheckoutNotice();
            return $this;
        }

        return $this;
    }

    /**
     * @return bool
     */
    protected function showCheckoutNotice()
    {
        $showNotice = false;
        $products = Mage::helper('identificationrequired')->getCartProductsWithIdentificationRequired();

        if (!empty($products)) {
            foreach($products as $product) {
                $rules = Mage::helper('identificationrequired')->getRulesByProduct($product);
                if(!empty($rules)) {
                    $match = false;
                    foreach($rules as $rule) {
                        if($rule->getShowCheckoutNotice() == 1) {
                            $checkoutNotice = trim($rule->getCheckoutNotice());
                            if (!empty($checkoutNotice)) {
                                $match = true;
                                break;
                            }
                        }
                    }

                    if($match == true) {
                        $showNotice = true;
                        break;
                    }
                }
            }
        }

        if ($showNotice == false) {
            return false;
        }

        if (empty($checkoutNotice)) {
            return false;
        }

        //Mage::getSingleton('core/session')->addNotice($checkoutNotice);
    }

    /**
     * @return bool
     */
    protected function showPrecheckout()
    {
        $currentUrl = Mage::helper('core/url')->getCurrentUrl();

        // Show this once per visit
        /** @var Mage_Core_Model_Session $coreSession */
        $coreSession = Mage::getSingleton('core/session');
        if ($coreSession->getPrecheckoutDisplayed() == true) {
            //$coreSession->setPrecheckoutDisplayed(false);
            return false;
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

        if ($showWarning == false) {
            return false;
        }

        $url = Mage::getUrl('identificationrequired/precheckout/index', array('uenc' => base64_encode($currentUrl)));
        Mage::app()->getResponse()->setRedirect($url);
        return true;
    }

    /**
     * Event sales_order_save_after
     *
     * @param $observer
     *
     * @return $this
     */
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


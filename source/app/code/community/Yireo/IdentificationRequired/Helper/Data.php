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
 * IdentificationRequired helper
 */
class Yireo_IdentificationRequired_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @return string
     */
    public function getFormXml()
    {
        return 'identificationrequired/form.xml';
    }

    /**
     * @return mixed
     */
    public function getForm()
    {
        $formXml = Mage::helper('identificationrequired')->getFormXml();
        $form = Mage::getModel('formapi/form')->loadFile($formXml);
        return $form;
    }

    /**
     * @param $string
     * @return array|string
     */
    public function implode($string)
    {
        $array = explode(',', $string);
        if(!empty($array)) {
            foreach($array as $arrayIndex => $arrayValue) {
                if(empty($arrayValue)) {
                    unset($array[$arrayIndex]);
                } else {
                    $array[$arrayIndex] = trim($arrayValue);
                }
            }
        }
        return $array;
    }

    public function getCartItemsAsArray()
    {
        $cartItems = Mage::getSingleton('checkout/cart')->getItems();
        $cartArray = array();
        foreach($cartItems as $cartItem) {
            $cartProduct = $cartItem->getProduct();
            $cartArray[$cartProduct->getId()] = $cartProduct;
        }
        return $cartArray;
    }

    /**
     * @param $product
     * @return array
     */
    public function getRulesCollection()
    {
        static $rulesCollection = null;
        if($rulesCollection == null) {
            $rulesCollection = Mage::getModel('identificationrequired/rule')->getCollection();
            foreach($rulesCollection as $rule) {
                $rule->setProductIds(Mage::helper('identificationrequired')->implode($rule->getData('product_ids')));
                $rule->setCategoryIds(Mage::helper('identificationrequired')->implode($rule->getData('category_ids')));
            }
        }
        return $rulesCollection;
    }

    /**
     * @param $product
     * @return array
     */
    public function getRulesByProduct($product)
    {
        $rules = array();
        if(empty($product)) {
            return $rules;
        }

        $rulesCollection = Mage::helper('identificationrequired')->getRulesCollection();
        if(!empty($rulesCollection)) {
            foreach($rulesCollection as $rule) {

                $match = false;

                $productIds = $rule->getProductIds();
                if(!empty($productIds)) {
                    foreach($productIds as $productId) {
                        if($productId == $product->getId() || $productId == $product->getSku()) {
                            $match = true;
                            break;
                        }
                    }
                }

                $categoryIds = $rule->getCategoryIds();
                if(!empty($categoryIds)) {
                    foreach($categoryIds as $categoryId) {
                        if(in_array($categoryId, $product->getCategoryIds())) {
                            $match = true;
                            break;
                        }
                    }
                }

                if($match == true) {
                    $rule->setData('product', $product->getName());
                    $rules[] = $rule;
                }
            }
        }

        return $rules;
    }

    /**
     * @param $customer
     * @return bool
     */
    public function hasCustomerIdentified($customer)
    {
        if(empty($customer)) {
            $customer = Mage::getModel('customer/session')->getCustomer();
        }

        if(!empty($customer) && $customer->getData('identificationrequired_status') == 'accepted') {
            return true;
        }

        return false;
    }

    /**
     * @param $product
     * @return bool
     */
    public function isProductWithIdentificationRequired($product)
    {
        if(empty($product)) {
            return false;
        }

        $rules = Mage::helper('identificationrequired')->getRulesByProduct($product);
        if(!empty($rules)) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getCartProductsWithIdentificationRequired()
    {
        $return = array();
        $cartItems = Mage::getSingleton('checkout/cart')->getItems();
        foreach($cartItems as $cartItem) {
            $product = $cartItem->getProduct();
            $rules = Mage::helper('identificationrequired')->getRulesByProduct($product);
            if(!empty($rules)) {
                $return[] = $product;
                break;
            }
        }

        return $return;
    }

    /*
     * Helper-method to log something to the system-log
     *
     * @param string $string
     * @param mixed $mixed
     * @return null
     */
    public function log($string, $mixed = null)
    {
        if($mixed) {
            $string .= ': '.var_export($mixed, true);
        }
        Mage::log('[IdentificationRequired]: '.$string);
    }

    /*
     * Helper-method to quickly log a debug-entry
     *
     * @param string $string
     * @param mixed $mixed
     * @return null
     */
    public function debug($variable, $text = null)
    {
        $log = null;
        if(!empty($text)) $log .= $text." - ";
        if(is_object($variable)) $log .= get_class($variable).": ";
        $log .= var_export($variable, true);

        if(!is_dir(BP.DS.'var'.DS.'tmp')) @mkdir(BP.DS.'var'.DS.'tmp');
        $tmp_file = BP.DS.'var'.DS.'tmp'.DS.'identificationrequired.debug';

        file_put_contents($tmp_file, $log."\n", FILE_APPEND);
    }
}

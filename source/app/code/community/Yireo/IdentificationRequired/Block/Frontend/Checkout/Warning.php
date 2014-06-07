<?php
/**
 * Yireo IdentificationRequired
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright (C) 2014 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

class Yireo_IdentificationRequired_Block_Frontend_Checkout_Warning extends Mage_Core_Block_Template
{
    protected $productsIdentificationRequired = array();

    /*
     * Constructor method
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('identificationrequired/checkout/warning.phtml');
    }

    public function isProductWithIdentificationRequired()
    {
        $this->productsIdentificationRequired = Mage::helper('identificationrequired')->getCartProductsWithIdentificationRequired();
        return (count($this->productsIdentificationRequired) > 0) ? true : false;
    }

    public function getRuleNotices()
    {
        $ruleNotices = array();

        $cartItems = Mage::getSingleton('checkout/cart')->getItems();
        foreach($cartItems as $cartItem) {

            $product = $cartItem->getProduct();
            $rules = Mage::helper('identificationrequired')->getRulesByProduct($product);

            if(!empty($rules)) {
                foreach($rules as $rule) {
                    if($rule->getShowCheckoutNotice() == 1) {
                        $productCount = count($rule->getProducts());
                        $ruleNotice = $rule->getCheckoutNotice();
                        $ruleNotice = str_replace('%s', $rule->getProduct(), $ruleNotice);
                        $ruleNotice = str_replace('%d', $productCount, $ruleNotice);
                        if(!in_array($ruleNotice, $ruleNotices)) {
                            $ruleNotices[] = $ruleNotice;
                        }
                    }
                }
            }
        }
        return $ruleNotices;
    }
}

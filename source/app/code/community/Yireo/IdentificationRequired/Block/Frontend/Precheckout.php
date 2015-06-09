<?php
/**
 * Yireo IdentificationRequired
 *
 * @package     Yireo_IdentificationRequired
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright 2015 Yireo (http://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

class Yireo_IdentificationRequired_Block_Frontend_Precheckout extends Mage_Core_Block_Template
{
    /*
     * Constructor method
     */
    public function _construct()
    {
        parent::_construct();
        Mage::getSingleton('core/session')->setPrecheckoutDisplayed(true);
        $this->setTemplate('identificationrequired/precheckout.phtml');
    }

    public function getSaveUrl()
    {
        return Mage::getUrl('identificationrequired/precheckout/save');
    }

    public function getRedirectUrl()
    {
        $redirect = Mage::app()->getRequest()->getParam('uenc');
        if(empty($redirect)) $redirect = base64_encode(Mage::getUrl('checkout/onepage'));
        return $redirect;
    }

    public function getApplicableRules()
    {
        $return = array();
        $cartItems = Mage::helper('identificationrequired')->getCartItemsAsArray();

        $rules = Mage::helper('identificationrequired')->getRulesCollection();
        if(!empty($rules)) {
            foreach($rules as $rule) {
                if($rule->getUsePrecheckout() == 1) {

                    $match = array();

                    foreach($rule->getProductIds() as $productId) {
                        if(array_key_exists($productId, $cartItems)) {
                            $matches[$productId] = $cartItems[$productId];
                        }

                        if($productId == '*') {
                            foreach($cartItems as $cartItemProductId => $cartItem) {
                                $matches[$cartItemProductId] = $cartItem;
                            }
                        }
                    }

                    foreach($rule->getCategoryIds() as $categoryId) {
                        foreach($cartItems as $cartItem) {
                            if(in_array($categoryId, $cartItem->getCategoryIds())) {
                                $matches[$cartItem->getId()] = $cartItem;
                            }
                        }
                    }

                    if(!empty($matches)) {
                        $rule->setApplicableProducts($matches);
                        $rule->setAgreementText(Mage::helper('formapi/content')->getCmspageContents($rule->getAgreementPage()));
                        $return[] = $rule;
                    }
                }
            }
        }

        return $return;
    }
}

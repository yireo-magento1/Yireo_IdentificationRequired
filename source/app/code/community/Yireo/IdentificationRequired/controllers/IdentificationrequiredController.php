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
 * IdentificationRequired admin controller
 *
 * @category   IdentificationRequired
 * @package     Yireo_IdentificationRequired
 */
class Yireo_IdentificationRequired_IdentificationrequiredController extends Yireo_FormApi_Controller_Adminhtml_Generic
{
    /**
     * Common method
     *
     * @return Yireo_IdentificationRequired_AdminController
     */
    protected function _initAction()
    {
        $this->overviewBlock = 'identificationrequired/adminhtml_rule_overview';
        $this->editBlock = 'identificationrequired/adminhtml_rule_edit';

        $this->loadLayout()
            ->_setActiveMenu('system/identificationrequired')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('System'), Mage::helper('adminhtml')->__('System'))
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Identification Rules'), Mage::helper('adminhtml')->__('Identification Rules'))
        ;
        return $this;
    }

    /**
     * Store action
     */
    public function storeAction()
    {
        $ruleId = $this->getRequest()->getParam('rule_id', 0);
        $post = $this->getRequest()->getPost();

        if (empty($post['store_id'])) {
            $post['store_id'] = array(0);
        }

        if (empty($post['product_ids']) && empty($post['category_ids'])) {
            $this->getSession()->addError($this->__('Your rule is not matched against any products or categories. Hint: Enter * to match any product or category.'));
        }

        if ($post['show_product_notice'] == 1 && empty($post['product_notice'])) {
            $this->getSession()->addError($this->__('You have choosen to show the product notice, but have not configured a text yet.'));
        }

        if ($post['show_checkout_notice'] == 1 && empty($post['checkout_notice'])) {
            $this->getSession()->addError($this->__('You have choosen to show the checkout notice, but have not configured a text yet.'));
        }

        if ($post['use_precheckout'] == 1 && empty($post['precheckout_notice'])) {
            $this->getSession()->addError($this->__('You have choosen to show the pre-checkout notice, but have not configured a text yet.'));
        }

        $model = Mage::getModel('identificationrequired/rule');
        if(!empty($ruleId)) {
            $model->load($ruleId);
        } else {
            unset($post['rule_id']);
        }

        $model->setData($post);

        if($model->save() == true) {
            $this->getSession()->addSuccess($this->__('Saved rule succesfully'));
        }

        return $model->getId();
    }

    /**
     * Return the current session model
     *
     * @return Mage_Adminhtml_Model_Session
     */
    public function getSession()
    {
        return Mage::getModel('adminhtml/session');
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        // Delete the rule
        $ruleId = $this->getRequest()->getParam('rule_id', 0);

        if(Mage::getModel('identificationrequired/rule')->load($ruleId)->delete() == true) {
            $this->getSession()->addNotice($this->__('Deleted rule succesfully'));
        }

        // Redirect
        $this->_redirect('*/*/index');
    }

    protected function _isAllowed()
    {
        $aclResource = 'admin/system/identificationrequired';

        return Mage::getSingleton('admin/session')->isAllowed($aclResource);
    }
}

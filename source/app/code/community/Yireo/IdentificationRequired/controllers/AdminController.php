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
 * IdentificationRequired admin controller
 *
 * @category   IdentificationRequired
 * @package     Yireo_IdentificationRequired
 */
class Yireo_IdentificationRequired_AdminController extends Yireo_FormApi_Controller_Adminhtml_Generic
{
    /**
     * Common method
     *
     * @access protected
     * @param null
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
     *
     * @access public
     * @param null
     * @return null
     */
    public function storeAction()
    {
        // Delete the rule
        $rule_id = $this->getRequest()->getParam('rule_id', 0);
        $post = $this->getRequest()->getPost();

        $model = Mage::getModel('identificationrequired/rule');
        if(!empty($rule_id)) {
            $model->load($rule_id);
        } else {
            unset($post['rule_id']);
        }
        $model->setData($post);

        if($model->save() == true) {
            Mage::getModel('adminhtml/session')->addSuccess($this->__('Saved rule succesfully'));
        }

        return $model->getId();
    }


    /**
     * Delete action
     *
     * @access public
     * @param null
     * @return null
     */
    public function deleteAction()
    {
        // Delete the rule
        $rule_id = $this->getRequest()->getParam('rule_id', 0);

        if(Mage::getModel('identificationrequired/rule')->load($rule_id)->delete() == true) {
            Mage::getModel('adminhtml/session')->addNotice($this->__('Deleted rule succesfully'));
        }

        // Redirect
        $this->_redirect('identificationrequired/admin/index');
    }
}

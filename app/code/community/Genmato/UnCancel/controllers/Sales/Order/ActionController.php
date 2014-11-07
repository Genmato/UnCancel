<?php

class Genmato_UnCancel_Sales_Order_ActionController extends Mage_Adminhtml_Controller_Action
{

    public function uncancelAction()
    {
        $id = $this->getRequest()->getParam('id', false);

        if ($id) {
            if (Mage::getModel('genmato_uncancel/uncancel')->order($id)) {
                $this->_getSession()->addSuccess($this->__('Order has been uncanceled!'));
            } else {
                $this->_getSession()->addError($this->__('Unable to uncancel the order!'));
            }
        }
        $this->_redirectReferer();
    }


}
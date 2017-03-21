<?php

namespace Commerceshop\TwoFactor\Controller\Adminhtml\Index;

class Logout extends \Magento\Backend\Controller\Adminhtml\Index
{
    public function execute()
    {
        $this->_auth->logout();
        $this->messageManager->addSuccess(__('You have logged out.'));
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath($this->_helper->getHomePageUrl());
    }
}
?>
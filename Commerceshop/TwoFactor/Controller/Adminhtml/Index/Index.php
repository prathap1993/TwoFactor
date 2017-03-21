<?php

namespace Commerceshop\TwoFactor\Controller\Adminhtml\Index;


class Index extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
    public function _isAllowed()
    {
        
        return $this->_authorization->isAllowed('Commerceshop_TwoFactor::manage_item');
    }
}

?>
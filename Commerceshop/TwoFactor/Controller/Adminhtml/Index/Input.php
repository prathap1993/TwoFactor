<?php

namespace Commerceshop\TwoFactor\Controller\Adminhtml\Index;


class Input extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
	}
}

?>
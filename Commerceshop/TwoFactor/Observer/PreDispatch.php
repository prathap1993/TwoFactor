<?php

namespace Commerceshop\TwoFactor\Observer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
class PreDispatch implements ObserverInterface
{
    protected $_responseFactory;
    protected $_url;
    protected $_session;
    protected $_request;
    
    
    public function __construct(\Magento\Framework\App\ResponseFactory $responseFactory, \Magento\Framework\UrlInterface $url, \Magento\Backend\Model\Session $session, \Magento\Framework\App\RequestInterface $request)
    {
        $this->_responseFactory = $responseFactory;
        $this->_url             = $url;
        $this->_session         = $session;
        $this->_request         = $request;
        
        
    }
    protected function _isAllowed()
    {
        
        return $this->_authorization->isAllowed('Commerceshop_TwoFactor::manage_item');
    }
    public function execute(Observer $observer)
    {
        $login  = $this->_session->getIsloggedin();
        $otp    = $this->_session->getOtpdone();
        $action = $this->_request->getModuleName() . $this->_request->getControllerName() . $this->_request->getActionName();
        if (($login == 1) && ($action != "twofactorindexindex") && ($otp == 0)) {
            $CustomRedirectionUrl = $this->_url->getUrl('twofactor/index/index');
            $this->_responseFactory->create()->setRedirect($CustomRedirectionUrl)->sendResponse();
            exit();
        }
        
    }
}
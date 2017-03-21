<?php
namespace Commerceshop\TwoFactor\Block\Adminhtml\Index;
class Index extends \Magento\Backend\Block\Widget\Container
{
protected $_session;
protected $_backendUrl;
    public function __construct(\Magento\Backend\Block\Widget\Context $context,array $data = [],
    	\Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Backend\Model\Session $session)
    {
    	parent::__construct($context, $data);
        $this->_session = $session;
    	$this->_backendUrl = $backendUrl;
    }
    public function getformurl()
    {
        return $url = $this->_backendUrl->getUrl("twofactor/index/verify");
    }
    public function logout()
    {
    	return $url = $this->_backendUrl->getUrl("twofactor/index/logout");
    }
 	public function resend()
    {
    	return $url = $this->_backendUrl->getUrl("twofactor/index/resend");
    }
    
    /*public function sendmail()
    {
        return $url = $this->_backendUrl->getUrl("twofactor/index/sendmail");
    }*/
    public function getmessage()
    {
        if($this->_session->getOtpmessage()){
            $message = $this->_session->getOtpmessage();
            // $this->_session->unsOtpmessage();
            return $message;
        }
        return false;
    }
       
}
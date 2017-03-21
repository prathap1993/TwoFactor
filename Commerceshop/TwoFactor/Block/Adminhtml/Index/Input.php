<?php

namespace Commerceshop\TwoFactor\Block\Adminhtml\Index;
class Input extends \Magento\Framework\View\Element\Template
{
	protected $_backendUrl;
	public function __construct(\Magento\Backend\Block\Widget\Context $context,array $data = [],
    	\Magento\Backend\Model\UrlInterface $backendUrl)
    {
    	parent::__construct($context, $data);
    	$this->_backendUrl = $backendUrl;
    }
	public function _prepareLayout()
	{
    	return parent::_prepareLayout();
	}
	public function getFormAction()
    {
        return $url = $this->_backendUrl->getUrl("twofactor/index/post");
    }
}
<?php

namespace Commerceshop\TwoFactor\Controller\Adminhtml\Index;


class Verify extends \Magento\Backend\App\Action
{
        protected $_session;
        protected $_url;
        protected $_responseFactory;
        protected $_scopeConfig;
        protected $_resultPageFactory;
        protected $_transportBuilder;
        protected $_storeManager;
        public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Backend\Model\Session $session,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Controller\ResultFactory $result,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->_responseFactory = $responseFactory;
        $this->_storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
         $this->_session = $session;
         $this->_url = $url;
        parent::__construct($context);
    }
    protected function _isAllowed()
    {

            return $this->_authorization
                ->isAllowed('Commerceshop_TwoFactor::manage_item');
    }

    public function execute()
    {

        $email = $this->_scopeConfig->getValue(
            'trans_email/ident_general/email',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

    $otp = $this->getRequest()->getParam('OTP');
    if ($otp == $this->_session->getOtp()) {
    $this->_session->setOtpdone(1);
        $full_name = "Your Account Has Been Logged-In";
           $customObject = new \Magento\Framework\DataObject();
            $templateParams = [
                'full_name' => $full_name
            ];
            $customObject->setData($templateParams);
            $this->_transportBuilder->setTemplateIdentifier(
                'admin_login'
            )->setTemplateOptions(
                [
                    'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
            )->setTemplateVars(
                ['user' => $customObject]
            )->setFrom(
                ['email' => 'prathap.g@innoppl.com', 'name' => 'Magento Admin Account Logged-In']
            )->addTo(
                $email,
                $full_name
            );
            try {

             $transport = $this->_transportBuilder->getTransport();
             $send = $transport->sendMessage();
            } 
            catch (Exception $e) {
                
                  $e->getMessage(); 
            }

           $CustomRedirectionUrl = $this->_url->getUrl('admin/dashboard/index');
          $this->_responseFactory->create()->setRedirect($CustomRedirectionUrl)->sendResponse();
          exit();
        }
            else{
             $this->_session->setOtpmessage("Invalid OTP, Please try again.");
             $CustomRedirectionUrl = $this->_url->getUrl('twofactor/index/index');
             $this->_responseFactory->create()->setRedirect($CustomRedirectionUrl)->sendResponse();
             exit();
        }
    }
}
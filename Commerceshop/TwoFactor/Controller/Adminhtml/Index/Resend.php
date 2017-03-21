<?php

namespace Commerceshop\TwoFactor\Controller\Adminhtml\Index;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;

class Resend extends \Magento\Backend\App\Action
{
    protected $_directory_list;
    protected $_responseFactory;
    protected $_scopeConfig;
    protected $_url;
    protected $_session;
    protected $_authSession;
    protected $_resultPageFactory;
    protected $resultRedirect;
    protected $_transportBuilder;
    protected $_storeManager;
    public function __construct(
        /*\Magento\Framework\App\Action\Context $context,*/
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Framework\Controller\ResultFactory $result,
        \Magento\Backend\Model\Session $session,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->_responseFactory = $responseFactory;
        $this->_directory_list = $directory_list;
        $this->_url = $url;
        $this->_authSession = $authSession;
        $this->resultRedirect = $result;
        $this->_session = $session;
        $this->_storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
        parent::__construct($context);
    }
    
    public function execute() 
    {  

    	$this->_authSession->getUser();
       $email = $this->_authSession->getUser()->getEmail();
       $phone = $this->_authSession->getUser()->getPhone();
        if (isset($phone) && isset($email)) 
        {
            
            $myValue = rand(111111,999999);
            $this->_session->setOtp($myValue);
            $full_name = "Your Verification code is : ".$myValue;
	        $base = $this->_directory_list->getPath('app')."/code/Commerceshop/TwoFactor/lib/";
            require_once($base.'way2sms-api.php');
            $message = "Your Verification code is : ".$myValue;
            $mobileNumber = $phone;
           if(sendWay2SMS ( '9789822842' , 'manoj' , $mobileNumber , $message)){   
           $this->_session->setOtpmessage("We have Re-sent the OTP to your Registered Mobile Number & E-Mail");

           }
           $customObject = new \Magento\Framework\DataObject();
            $templateParams = [
                'full_name' => $full_name
            ];
            $customObject->setData($templateParams);
            $this->_transportBuilder->setTemplateIdentifier(
                'login_success'
            )->setTemplateOptions(
                [
                    'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
            )->setTemplateVars(
                ['user' => $customObject]
            )->setFrom(
                ['email' => 'prathap.g@innoppl.com', 'name' => 'Two Factor']
            )->addTo(
                $email,
                $full_name
            );
            try {

             $transport = $this->_transportBuilder->getTransport();
             $send = $transport->sendMessage();
             $this->_session->setOtpmessage("We have Re-sent the OTP to your Registered Mobile Number & E-Mail");
             $CustomRedirectionUrl = $this->_url->getUrl('twofactor/index/index');
             $resultRedirect = $this->resultRedirect->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
             $resultRedirect->setUrl($CustomRedirectionUrl);
             return $resultRedirect; 
            } 
            catch (Exception $e) {
                
                  $e->getMessage(); 
            }
        }
    }
}

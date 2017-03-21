<?php
namespace Commerceshop\TwoFactor\Observer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
class AdminLoginSucceeded implements ObserverInterface 
{
    protected $_responseFactory;
    protected $_url;
    protected $_authSession;
    protected $_session;
    protected $_directory_list;
    protected $_scopeConfig;
    protected $_transportBuilder;
    protected $_storeManager;
    public function __construct(\Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Backend\Model\Session $session,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager)
    {   
        $this->_scopeConfig = $scopeConfig;
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
        $this->_authSession = $authSession;
        $this->_session = $session;
        $this->_directory_list = $directory_list;
        $this->_storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
        
    }
    public function execute(Observer $observer) 
    {
       $this->_authSession->getUser();
       $email = $this->_authSession->getUser()->getEmail();
       $phone = $this->_authSession->getUser()->getPhone();
       $check = $this->_scopeConfig->getValue(
            'authentication/parameters/config',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    
       if ($check == 'enable') 
       {
            $auth = $this->_authSession;
            $auth->getUser()->getUserId();
            $this->_session->setIsloggedin(1);
            $this->_session->setOtpdone(0);
            $myValue = rand(111111,999999);
            $this->_session->setOtp($myValue);
//	    $this->_session->setOtp($myValue);
            $base = $this->_directory_list->getPath('app')."/code/Commerceshop/TwoFactor/lib/";
            require_once($base.'way2sms-api.php');
            $message = "Your Verification code is : ".$myValue;
            $mobileNumber = $phone;
            if(sendWay2SMS ( '9789822842' , 'manoj' , $mobileNumber , $message)){
            $this->_session->setOtpmessage("We have sent the OTP to your Registered Mobile Number & E-Mail");
             }
            $full_name = "Your Verification code is : ".$myValue;

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
            $transport = $this->_transportBuilder->getTransport();
            try {
                if ($transport->sendMessage()) {
                    $this->_session->setOtpmessage("We have sent the OTP to your registered E-Mail & Mobile Number");
                    exit();
                } 
            } catch (\Exception $e) {
                \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->debug($e->getMessage());
            }
       }
    }
}

<?php

namespace Commerceshop\TwoFactor\Controller\Adminhtml\Index;


class Sendmail extends \Magento\Backend\App\Action
{

    protected $_responseFactory;
    protected $_scopeConfig;
    protected $_resultPageFactory;
    protected $_transportBuilder;
    protected $_storeManager;
    public function __construct(
        /*\Magento\Framework\App\Action\Context $context,*/
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Controller\ResultFactory $result,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->_responseFactory = $responseFactory;
        $this->_storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
        parent::__construct($context);
    }
    
    public function execute() 
    {        
    	$email = $this->_scopeConfig->getValue(
            'trans_email/ident_general/email',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	echo $email;
        if (isset($email)) 
        {
           /*$name = $this->_scopeConfig->getValue(
            'trans_email/ident_general/name',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);*/
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
                ['email' => 'prathap.g@innoppl.com', 'name' => 'Account Logged-In']
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
        }
    }
}

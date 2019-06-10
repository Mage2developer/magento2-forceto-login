<?php
namespace Mage2\Login\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
 
class BeforeLogin implements ObserverInterface
{
    /**
     * @var customerSession
    */
    protected $customerSession;
    
    /**
     * @var responseFactory
    */
    protected $responseFactory;

    /**
     * @var url
    */
    protected $url;

    /**
     * @var scopeConfig
    */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->customerSession = $customerSession;
        $this->responseFactory = $responseFactory;
        $this->scopeConfig = $scopeConfig;
        $this->url = $url;
    }
 
    /**
     * customer register event handler
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $isEnable = $this->scopeConfig->getValue('mage2_login_section/general/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if ($isEnable) {
            $actionName = $observer->getEvent()->getRequest()->getFullActionName();
            
            $openActions = array(
                'customer_account_login',
                'customer_account_loginPost',
                'customer_account_create',
                'customer_account_createpost',
                'customer_account_forgotpassword',
                'customer_account_forgotpasswordpost',
                'customer_account_index',
                'newsletter_subscriber_new',
            );

            if (in_array($actionName, $openActions)) {
                return ; //if in allowed actions do nothing.
            }

            if (!$this->customerSession->isLoggedIn()) {
                $CustomRedirectionUrl = $this->url->getUrl('customer/account/login');
                $this->responseFactory->create()->setRedirect($CustomRedirectionUrl)->sendResponse();
                exit;
            }
        }
    }
}

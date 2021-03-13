<?php
/**
 * Product Name: Mage2 Force to Login
 * Module Name: Mage2_Login
 * Created By: Yogesh Shishangiya
 */

declare(strict_types=1);

namespace Mage2\Login\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Customer\Model\Session;

/**
 * Class BeforeLogin
 *
 * @package Mage2\Login\Observer
 */
class BeforeLogin implements ObserverInterface
{
    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * @var UrlInterface
     */
    protected $url;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * BeforeLogin constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param UrlInterface $url
     * @param ScopeConfigInterface $scopeConfig
     * @param Session $customerSession
     */
    public function __construct(
        ResponseFactory $responseFactory,
        UrlInterface $url,
        ScopeConfigInterface $scopeConfig,
        Session $customerSession
    ) {
        $this->customerSession = $customerSession;
        $this->responseFactory = $responseFactory;
        $this->scopeConfig     = $scopeConfig;
        $this->url             = $url;
    }

    /**
     * Customer register event handler
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $isEnable = $this->scopeConfig->getValue('mage2_login_section/general/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if ($isEnable) {
            $actionName = $observer->getEvent()->getRequest()->getFullActionName();

            $openActions = [
                'customer_account_login',
                'customer_account_loginPost',
                'customer_account_create',
                'customer_account_createpost',
                'customer_account_forgotpassword',
                'customer_account_forgotpasswordpost',
                'customer_account_index',
                'newsletter_subscriber_new',
            ];

            if (in_array($actionName, $openActions)) {
                return; //if in allowed actions do nothing.
            }

            if (!$this->customerSession->isLoggedIn()) {
                $CustomRedirectionUrl = $this->url->getUrl('customer/account/login');
                $this->responseFactory->create()->setRedirect($CustomRedirectionUrl)->sendResponse();
                exit;
            }
        }
    }
}

<?php
/**
 * Mage2developer
 * Copyright (C) 2021 Mage2developer
 *
 * @category Mage2developer
 * @package Mage2_Login
 * @copyright Copyright (c) 2021 Mage2developer
 * @author Mage2developer <mage2developer@gmail.com>
 */

declare(strict_types=1);

namespace Mage2\Login\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Response\RedirectInterface;

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
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var RedirectInterface
     */
    protected $redirect;

    /**
     * BeforeLogin constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param Session $customerSession
     * @param RedirectInterface $redirect
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Session $customerSession,
        RedirectInterface $redirect
    )
    {
        $this->customerSession = $customerSession;
        $this->scopeConfig     = $scopeConfig;
        $this->redirect        = $redirect;
    }

    /**
     * Customer register event handler
     *
     * @param Observer $observer
     * @return $this|void
     */
    public function execute(Observer $observer)
    {
        if ($this->customerSession->isLoggedIn()) {
            return;
        }

        $isEnable     = $this->scopeConfig->getValue('mage2_login_section/general/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $allowedPages = $this->scopeConfig->getValue('mage2_login_section/general/allowed_pages', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $arrPages = explode(",", $allowedPages);

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

        $openActions = array_merge($openActions, $arrPages);

        if ($isEnable) {
            $actionName = $observer->getEvent()->getRequest()->getFullActionName();

            if (in_array($actionName, $openActions)) {
                return; //if in allowed actions do nothing.
            }

            if (!$this->customerSession->isLoggedIn()) {
                $controller = $observer->getControllerAction();
                $this->redirect->redirect($controller->getResponse(), 'customer/account/login');

                return $this;
            }
        }
    }
}

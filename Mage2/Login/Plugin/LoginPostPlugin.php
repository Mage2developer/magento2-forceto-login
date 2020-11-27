<?php

namespace Mage2\Login\Plugin;

class LoginPostPlugin
{

    /**
     * @var scopeConfig
    */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ){
        $this->scopeConfig = $scopeConfig;
    }

    public function afterExecute(
        \Magento\Customer\Controller\Account\LoginPost $subject, $result)
    {
        $RedirectHome = $this->scopeConfig->getValue('mage2_login_section/general/redirect_home', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($RedirectHome){
            $result->setPath('/');
        }
        return $result;
    }

}
<?php

namespace JonathanMartz\WebApiManager\Plugin\Rest;

use JonathanMartz\WebApiManager\Model\RequestFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\UrlInterface;
use Magento\Webapi\Controller\Rest;
use Psr\Log\LoggerInterface;

/**
 * Class Api
 * @package JonathanMartz\WebApiManager\Plugin\Rest
 */
class Api
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var RequestFactory
     */
    protected $webapistats;

    /**
     * @var UrlInterface
     */
    private $url;
    /**
     * @var RemoteAddress
     */
    private $remote;
    /**
     * @var Session
     */
    private $customerSession;

    /**
     *
     */
    const CN_G_E = 'webapi-manager/general/enable';
    /**
     *
     */
    const CN_G_L = 'webapi-manager/general/limit';
    /**
     *
     */
    const CN_EN_CC = 'webapi-manager/endpoint/customer-create';
    /**
     *
     */
    const CN_EN_S = 'webapi-manager/endpoint/search';
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::CN_G_E);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function getEndpoint(string $name): bool
    {
        switch($name) {
            case "customer-create":
                return (bool)$this->scopeConfig->getValue(self::CN_EN_CC);
                break;
            case "search":
                return (bool)$this->scopeConfig->getValue(self::CN_EN_S);
                break;
        }

        return false;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return (int)$this->scopeConfig->getValue(self::CN_G_L);
    }

    /**
     * Api constructor.
     * @param LoggerInterface $logger
     * @param UrlInterface $url
     * @param RemoteAddress $remote
     * @param Session $customerSession
     * @param RequestFactory $webapistats
     */
    public function __construct(
        LoggerInterface $logger,
        UrlInterface $url,
        RemoteAddress $remote,
        Session $customerSession,
        RequestFactory $webapistats,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->logger = $logger;
        $this->url = $url;
        $this->remote = $remote;
        $this->customerSession = $customerSession;
        $this->webapistats = $webapistats;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param Rest $subject
     * @param callable $proceed
     * @param RequestInterface $request
     * @return mixed
     */
    public function aroundDispatch(
        Rest $subject,
        callable $proceed,
        RequestInterface $request
    ) {
        if($this->isEnabled()) {
            $id = $this->customerSession->getSessionId();
            $ip = $this->remote->getRemoteAddress();

            $model = $this->webapistats->create();
            $collection = $model->getCollection();
            $collection->addFieldToFilter('ip', ['eq' => sha1($ip)]);

            var_dump('User Requests: ' . count($collection));
        }

        return $proceed($request);
    }
}

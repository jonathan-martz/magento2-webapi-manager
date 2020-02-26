<?php

namespace JonathanMartz\WebApiManager\Plugin\Rest;

use JonathanMartz\WebApiLog\Model\ResourceModel\CollectionFactory;
use JonathanMartz\WebApiManager\Model\RequestFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\UrlInterface;
use Magento\Webapi\Controller\Rest;
use Psr\Log\LoggerInterface;
use function file_get_contents;
use function file_put_contents;
use function json_encode;

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
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var DirectoryList
     */
    private $directoryList;
    /**
     * @var File
     */
    private $file;
    /**
     * @var DriverInterface
     */
    private $driver;
    /**
     * @var Filesystem
     */
    private $filesytem;
    /**
     * @var Filesystem
     */
    private $filesystem;

    private $jsonResultFactory;
    /**
     * @var ResultFactory
     */
    private $resultFactory;

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
        ScopeConfigInterface $scopeConfig,
        CollectionFactory $collectionFactory,
        DirectoryList $directoryList,
        Filesystem $filesystem,
        File $file,
        JsonFactory $jsonResultFactory
    ) {
        $this->logger = $logger;
        $this->url = $url;
        $this->remote = $remote;
        $this->scopeConfig = $scopeConfig;
        $this->collectionFactory = $collectionFactory;
        $this->directoryList = $directoryList;
        $this->filesystem = $filesystem;
        $this->file = $file;
        $this->jsonResultFactory = $jsonResultFactory;
    }

    public function beforeDispatch(
        Rest $subject,
        RequestInterface $request
    ) {
        $ip = $this->remote->getRemoteAddress();

        if($this->isEnabled()) {
            //@todo use php session id to identify

            $filePath = "/webapi-manager/user/";
            $pdfPath = $this->directoryList->getPath('pub') . $filePath;
            $ioAdapter = $this->file;
            if(!is_dir($pdfPath)) {
                $ioAdapter->mkdir($pdfPath, 0775);
            }

            $filename = $pdfPath . '/' . sha1($ip . '-' . date('d.m.Y H')) . '.json';

            if(file_exists($filename)) {
                $file = file_get_contents($filename);
                file_put_contents($filename, (int)$file + 1);

                if($file > $this->getLimit()) {
                    $this->logger->critical('user blocked from request. (' . sha1($ip) . ')');
                    $data = ['message' => 'Your now blocked for the rest of the hour. Reason: to many requests.'];

                    die(json_encode($data));
                }
            }
            else {
                file_put_contents($filename, 1);
            }
        }
        else {
            $this->logger->info('user blocked from request. (' . sha1($ip) . ')');
            $data = ['message' => 'Api Endpoint disabled. Please contact the admin.'];
            die(json_encode($data));
        }
    }
}

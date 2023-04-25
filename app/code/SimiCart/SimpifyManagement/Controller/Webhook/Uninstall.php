<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Webhook;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface as IRequest;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Filesystem\DriverInterface;
use SimiCart\SimpifyManagement\Api\ShopRepositoryInterface;

class Uninstall implements HttpPostActionInterface, CsrfAwareActionInterface
{
    private IRequest $request;
    private Json $jsonSerializer;
    private DriverInterface $driver;
    private ShopRepositoryInterface $shopRepository;
    private ManagerInterface $eventManager;

    /**
     * Unintall action constructor
     *
     * @param IRequest $request
     * @param Json $jsonSerializer
     * @param DriverInterface $driver
     * @param ShopRepositoryInterface $shopRepository
     * @param ManagerInterface $eventManager
     */
    public function __construct(
        IRequest $request,
        Json $jsonSerializer,
        DriverInterface $driver,
        ShopRepositoryInterface $shopRepository,
        ManagerInterface $eventManager
    ) {
        $this->request = $request;
        $this->jsonSerializer = $jsonSerializer;
        $this->driver = $driver;
        $this->shopRepository = $shopRepository;
        $this->eventManager = $eventManager;
    }

    /**
     * Uninstalled a shop
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws FileSystemException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        //@codingStandardsIgnoreStart
        $shopData = $this->retrieveJsonPostData();
        if (isset($shopData['domain'])) {
            $shop = $this->shopRepository->getByDomain($shopData['domain']);
            $shop->uninstallShop();
            try {
                $this->shopRepository->save($shop);
                $this->eventManager->dispatch('shop_uninstalled_success', ['shop' => $shop]);
            } catch (\Exception $e) {
                // TODO: nothing, logged in repository
            }
        }
        exit;
        //@codingStandardsIgnoreEnd
    }

    /**
     * Returns the JSON encoded POST data, if any, as an object.
     *
     * @return Object|null
     * @throws FileSystemException
     */
    private function retrieveJsonPostData(): ?array
    {
        // get the raw POST data
        $rawData = $this->driver->fileGetContents('php://input');

        // this returns null if not valid json
        return $this->jsonSerializer->unserialize($rawData);
    }

    /**
     * Get request object
     *
     * @return IRequest
     */
    public function getRequest(): IRequest
    {
        return $this->request;
    }

    /**
     * Bypass validate csrf for webhok
     *
     * @param IRequest $request
     * @return true
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validateForCsrf(IRequest $request): ?bool
    {
        return true;
    }

    /**
     * @inheritDoc
     *
     * No exception for csrf validation webhook
     */
    public function createCsrfValidationException(IRequest $request): ?InvalidRequestException
    {
        return null;
    }
}

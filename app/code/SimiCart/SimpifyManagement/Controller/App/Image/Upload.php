<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\App\Image;

use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RequestInterface as IRequest;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\AuthorizationException;
use Magento\Integration\Model\Oauth\TokenFactory;

class Upload implements ActionInterface, CsrfAwareActionInterface
{
    private ImageUploader $imageUploader;
    private \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory;
    private \Magento\Framework\App\RequestInterface $request;
    private \Magento\Framework\App\ResponseInterface $response;
    private TokenFactory $tokenFactory;

    /**
     * @param Context $context
     * @param ImageUploader $imageUploader
     * @param JsonFactory $resultJsonFactory
     * @param RequestInterface $request
     * @param TokenFactory $tokenFactory
     */
    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ImageUploader $imageUploader,
        // import json result factory
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        // import request interface
        \Magento\Framework\App\RequestInterface $request,
        TokenFactory $tokenFactory
    ) {
        $this->response = $context->getResponse();
        $this->imageUploader = $imageUploader;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->request = $request;
        $this->tokenFactory = $tokenFactory;
    }

    /**
     * Upload file controller action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->getResponse()->setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
        $this->getResponse()->setHeader(
            'Access-Control-Expose-Headers',
            'Content-Encoding, Content-Length, Content-Type'
        );
        $this->getResponse()->setHeader('Access-Control-Allow-Headers', 'Content-Type, X-Simpify-Token');
        try {
            $this->isAllowed();
            $imageId = $this->request->getParam('param_name', 'image');
            $result = $this->imageUploader->saveFileToTmpDir($imageId);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultJsonFactory->create()->setData($result);
    }

    /**
     * Check if request is authorized
     *
     * @return bool
     * @throws AuthorizationException
     */
    protected function isAllowed(): bool
    {
        $token = $this->request->getHeader('X-Simpify-Token');
        if ($token) {
            $token = $this->tokenFactory->create()->loadByToken($token);
            if ($token->getId()) {
                return true;
            }
        }
        throw new AuthorizationException(__('Access denied'), null , 403);
    }

    /**
     * Bypass validate csrf for ajax request
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
     * No exception for csrf validation ajax request
     */
    public function createCsrfValidationException(IRequest $request): ?InvalidRequestException
    {
        return null;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function getResponse(): \Magento\Framework\App\ResponseInterface
    {
        return $this->response;
    }
}

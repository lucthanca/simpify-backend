<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Resolver;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use SimiCart\SimpifyManagement\Api\AppRepositoryInterface;
use SimiCart\SimpifyManagement\Api\Data\AppInterface;
use SimiCart\SimpifyManagement\Helper\UtilTrait;

class GetLastUpdatedApp implements \Magento\Framework\GraphQl\Query\ResolverInterface
{
    use UtilTrait;

    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder;
    protected AppRepositoryInterface $appRepository;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param AppRepositoryInterface $appRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder,
        AppRepositoryInterface $appRepository
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->appRepository = $appRepository;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!$context->getExtensionAttributes()->getIsSimpifyShop()) {
            throw new GraphQlAuthorizationException(__("Unauthorized!!!"));
        }
        try {
            $shopId = $context->getExtensionAttributes()->getSimpifyShopId();
            $sortOrder = $this->sortOrderBuilder->setField(AppInterface::UPDATED_AT)->setDirection('DESC');
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter(AppInterface::SHOP_ID, $shopId)->addSortOrder($sortOrder->create())->setPageSize(1);
            $result = $this->appRepository->getList($searchCriteria->create());
            if ($result->getTotalCount() === 0) {
                return null;
            }

            foreach ($result->getItems() as $app) {
                return $this->formatAppOutput($app);
            }
        } catch (\Exception $e) {
            throw new GraphQlNoSuchEntityException(__("Failed to fetch App."));
        }
        return null;
    }

    protected function formatAppOutput(AppInterface $app)
    {
        return $app->getData() +[
            'uid' => $this->base64UrlEncode($app->getId()),
            'model' => $app,
        ];
    }
}

<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Resolver;

use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\ArgumentApplier\Filter;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\ArgumentApplier\Sort;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use SimiCart\SimpifyManagement\Api\AppRepositoryInterface;
use SimiCart\SimpifyManagement\Api\Data\AppSearchResultsInterface;
use SimiCart\SimpifyManagement\Model\AppFactory;
use SimiCart\SimpifyManagement\Api\Data\AppInterface as IApp;
use SimiCart\SimpifyManagementGraphql\Exceptions\GraphQlUncommonErrorException;
use SimiCart\SimpifyManagementGraphql\Model\Formatter\AppFormatterTrait;

class GetApps implements ResolverInterface
{
    use AppFormatterTrait;

    /**
     * @var string
     */
    private const SPECIAL_CHARACTERS = '-+~/\\<>\'":*$#@()!,.?`=%&^';

    protected AppFactory $appFactory;
    protected ArgsCompositeProcessor $argsSelection;
    protected Builder $searchCriteriaBuilder;
    protected AppRepositoryInterface $appRepository;

    /**
     * GetApps constructor.
     *
     * @param AppFactory $appFactory
     * @param ArgsCompositeProcessor $argsSelection
     * @param Builder $searchCriteriaBuilder
     * @param AppRepositoryInterface $appRepository
     */
    public function __construct(
        AppFactory $appFactory,
        ArgsCompositeProcessor $argsSelection,
        Builder $searchCriteriaBuilder,
        AppRepositoryInterface $appRepository
    ) {
        $this->appFactory = $appFactory;
        $this->argsSelection = $argsSelection;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->appRepository = $appRepository;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $this->context = $context;
        $isSimpiShop = $context->getExtensionAttributes()->getIsSimpifyShop();
        if (!$isSimpiShop) {
            throw new GraphQlAuthorizationException(__('You are not authorized to access this resource'));
        }
        if (isset($args['currentPage']) && $args['currentPage'] < 1) {
            throw new GraphQlInputException(__('currentPage value must be greater than 0.'));
        }
        if (isset($args['pageSize']) && $args['pageSize'] < 1) {
            throw new GraphQlInputException(__('pageSize value must be greater than 0.'));
        }

        if (empty($args['filters'])) {
            $args['filters'] = [];
        }
        // Get for authorized user shop only
        $args['filters']['shop_uid'] = ['eq' => $context->getExtensionAttributes()->getSimpifyShopId()];

        $processedArgs = $this->argsSelection->process($info->fieldName, $args);
        try {
            $searchCriteria = $this->buildCriteria($processedArgs);
        } catch (InputException $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }
        try {
            $searchResult = $this->appRepository->getList($searchCriteria);
        } catch (\Exception $e) {
            throw new GraphQlUncommonErrorException(__($e->getMessage()));
        }

        return $this->extractDataFromResult($searchResult, $searchCriteria);
    }

    /**
     * Extract data from search result
     *
     * @param AppSearchResultsInterface $searchResult
     * @param SearchCriteriaInterface $searchCriteria
     * @return array
     * @throws GraphQlInputException
     */
    public function extractDataFromResult(
        AppSearchResultsInterface $searchResult,
        SearchCriteriaInterface $searchCriteria
    ) {
        $totalPages = 0;
        if ($searchResult->getTotalCount() > 0 && $searchCriteria->getPageSize() > 0) {
            $totalPages = ceil($searchResult->getTotalCount() / $searchCriteria->getPageSize());
        }

        if ($searchCriteria->getCurrentPage() > $totalPages && $searchResult->getTotalCount() > 0) {
            throw new GraphQlInputException(
                __(
                    'currentPage value %1 specified is greater than the %2 page(s) available.',
                    [$searchCriteria->getCurrentPage(), $totalPages]
                )
            );
        }

        $items = [];

        foreach ($searchResult->getItems() as $item) {
            $items[] = $this->formatAppOutput($item);
        }

        return [
            'items' => $items,
            'total_count' => $searchResult->getTotalCount(),
            'page_info' => [
                'total_pages' => $totalPages,
                'page_size' => $searchCriteria->getPageSize(),
                'current_page' => $searchCriteria->getCurrentPage(),
            ]
        ];
    }

    /**
     * Transform raw criteria data into SearchCriteriaInterface
     *
     * @param array $criteria
     * @return SearchCriteriaInterface
     * @throws InputException
     */
    protected function buildCriteria(array $criteria): SearchCriteriaInterface
    {
        $criteria[Filter::ARGUMENT_NAME] = $this->formatMatchFilters($criteria['filters']);
        $criteria[Sort::ARGUMENT_NAME][Iapp::UPDATED_AT] = ['DESC'];
        $searchCriteria = $this->searchCriteriaBuilder->build('appList', $criteria);

        $pageSize = $criteria['pageSize'] ?? 20;
        $currentPage = $criteria['currentPage'] ?? 1;
        $searchCriteria->setPageSize($pageSize)->setCurrentPage($currentPage);
        return $searchCriteria;
    }


    /**
     * Format match filters to behave like fuzzy match
     *
     * @param array $filters
     * @return array
     * @throws InputException
     */
    private function formatMatchFilters(array $filters): array
    {
        foreach ($filters as $filter => $condition) {
            $conditionType = current(array_keys($condition));
            if ($conditionType === 'match') {
                $searchValue = trim(str_replace(self::SPECIAL_CHARACTERS, '', $condition[$conditionType]));
                $matchLength = strlen($searchValue);
                if ($matchLength < 3) {
                    throw new InputException(__('Invalid match filter. Minimum length is %1.', 3));
                }
                unset($filters[$filter]['match']);
                $filters[$filter]['like'] = '%' . $searchValue . '%';
            }
        }
        return $filters;
    }
}

<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Exceptions;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;

class GraphQlUncommonErrorException extends NoSuchEntityException implements \GraphQL\Error\ClientAware
{
    const EXCEPTION_CATEGORY = 'graphql-uncommon-error';

    /**
     * @var boolean
     */
    private $isSafe;

    /**
     * @inheritDoc
     */
    public function __construct(Phrase $phrase = null, \Exception $cause = null, $code = 0, $isSafe = true)
    {
        $this->isSafe = $isSafe;
        parent::__construct($phrase, $cause, $code);
    }

    /**
     * @inheritDoc
     */
    public function isClientSafe()
    {
        return $this->isSafe;
    }

    /**
     * @inheritDoc
     */
    public function getCategory()
    {
        return self::EXCEPTION_CATEGORY;
    }
}

<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Resolver;

use Magento\Framework\GraphQl\Query\Resolver\ArgumentsProcessorInterface;

class ArgsCompositeProcessor implements ArgumentsProcessorInterface
{
    /**
     * @var ArgumentsProcessorInterface[]
     */
    private array $processors = [];

     public function __construct(
         array $processors = []
     ) {
        $this->processors = $processors;
     }

    /**
     * Get processors
     *
     * @return array|ArgumentsProcessorInterface[]
     */
    public function getProcessors(): array
    {
        return $this->processors;
    }

    /**
     * @inheritDoc
     */
    public function process(string $fieldName, array $args): array
    {
        $processedArgs = $args;
        foreach ($this->getProcessors() as $processor) {
            $processedArgs = $processor->process(
                $fieldName,
                $processedArgs
            );
        }

        return $processedArgs;
    }
}

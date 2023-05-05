<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Query;

use Magento\Framework\GraphQl\Query\Resolver\ArgumentsProcessorInterface;
use Magento\Framework\GraphQl\Query\Uid;

class UidArgsProcessor implements ArgumentsProcessorInterface
{
    protected ?string $id = null;
    protected ?string $uid = null;
    protected string $filterKey = 'filters';
    protected Uid $uidEncoder;

    /**
     * Constructor
     *
     * @param Uid $uidEncoder
     * @param string $id
     * @param string $uid
     */
    public function __construct(
        Uid $uidEncoder,
        string $id = 'uid',
        string $uid = 'uid'
    ) {
        $this->id = $id;
        $this->uid = $uid;
        $this->uidEncoder = $uidEncoder;
    }

    /**
     * Remapping fields. it will replace uid using id field and decode base64 uid
     *
     * @param string $fieldName
     * @param array $args
     * @return array
     * @throws \Magento\Framework\GraphQl\Exception\GraphQlInputException
     */
    public function process(string $fieldName, array $args): array
    {
        $filterKey = $this->filterKey;

        if ($this->id === $this->uid) {
            $this->decodeUid($args, $this->uid, $args[$filterKey][$this->uid] ?? []);
            return $args;
        }

        $this->decodeUid($args, $this->id, $args[$filterKey][$this->uid] ?? []);
        unset($args[$filterKey][$this->uid]);

        return $args;
    }

    /**
     * Decode uid to id
     *
     * @param array $args
     * @param string $fieldName
     * @param array $encodedUid
     * @return void
     * @throws \Magento\Framework\GraphQl\Exception\GraphQlInputException
     */
    protected function decodeUid(array &$args, string $fieldName, array $encodedUid)
    {
        $filterKey = $this->filterKey;
        $uidFilter = $encodedUid;
        if (isset($uidFilter['eq'])) {
            $args[$filterKey][$fieldName]['eq'] = $this->uidEncoder->decode((string) $uidFilter['eq']);
        } elseif (!empty($uidFilter['in'])) {
            foreach ($uidFilter['in'] as $uid) {
                $args[$filterKey][$fieldName]['in'][] = $this->uidEncoder->decode((string) $uid);
            }
        }
    }
}

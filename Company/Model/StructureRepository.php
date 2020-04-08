<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model;

use Javid\Company\Api\StructureRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Javid\Company\Model\ResourceModel\Structure as ResourceStructure;

/**
 * Repository for basic structure entity CRUD operations.
 */
class StructureRepository
{
    /**
     * @var \Javid\Company\Api\Data\StructureInterface[]
     */
    private $instances = [];

    /**
     * @var \Javid\Company\Model\StructureFactory
     */
    private $structureFactory;

    /**
     * @var \Javid\Company\Model\ResourceModel\Structure
     */
    private $structureResource;

    /**
     * @var \Javid\Company\Model\Structure\SearchProvider
     */
    private $searchProvider;

    /**
     * @param \Javid\Company\Model\StructureFactory           $structureFactory
     * @param \Javid\Company\Model\ResourceModel\Structure    $structureResource
     * @param \Javid\Company\Model\Structure\SearchProvider   $searchProvider
     */
    public function __construct(
        \Javid\Company\Model\StructureFactory $structureFactory,
        \Javid\Company\Model\ResourceModel\Structure $structureResource,
        \Javid\Company\Model\Structure\SearchProvider $searchProvider
    ) {
        $this->structureFactory = $structureFactory;
        $this->structureResource = $structureResource;
        $this->searchProvider = $searchProvider;
    }

    /**
     * Create structure service.
     *
     * @param \Javid\Company\Api\Data\StructureInterface $structure
     * @return int
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Javid\Company\Api\Data\StructureInterface $structure)
    {
        try {
            $this->structureResource->save($structure);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __(
                    'Could not save company: %1',
                    $e->getMessage()
                ),
                $e
            );
        }
        unset($this->instances[$structure->getId()]);
        return $structure->getId();
    }

    /**
     * Get structure service.
     *
     * @param int $structureId
     * @return \Javid\Company\Api\Data\StructureInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($structureId)
    {
        if (!isset($this->instances[$structureId])) {
            /** @var \Javid\Company\Api\Data\StructureInterface $structure */
            $structure = $this->structureFactory->create();
            $structure->load($structureId);
            if (!$structure->getId()) {
                throw new NoSuchEntityException(
                    __(
                        'No such entity with %fieldName = %fieldValue',
                        ['fieldName' => 'id', 'fieldValue' => $structureId]
                    )
                );
            }
            $this->instances[$structureId] = $structure;
        }
        return $this->instances[$structureId];
    }

    /**
     * Delete structure service.
     *
     * @param \Javid\Company\Api\Data\StructureInterface $structure
     * @return bool
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(\Javid\Company\Api\Data\StructureInterface $structure)
    {
        try {
            $structureId = $structure->getId();
            $this->structureResource->delete($structure);
        } catch (\Exception $e) {
            throw new StateException(
                __(
                    'Cannot delete structure with id %1',
                    $structure->getId()
                ),
                $e
            );
        }
        unset($this->instances[$structureId]);
        return true;
    }

    /**
     * Delete structure by ID service.
     *
     * @param int $structureId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById($structureId)
    {
        $structure = $this->get($structureId);
        return $this->delete($structure);
    }

    /**
     * Load Structure data collection by given search criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Javid\Company\Api\Data\StructureSearchResultsInterface
     * @throws \InvalidArgumentException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        return $this->searchProvider->getList($searchCriteria);
    }
}

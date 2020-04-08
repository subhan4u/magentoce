<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Model;

use Javid\Company\Api\Data\StructureInterface;

/**
 * Company hierarchy management class.
 */
class CompanyHierarchy implements \Javid\Company\Api\CompanyHierarchyInterface
{
    /**
     * @var \Javid\Company\Api\Data\HierarchyInterfaceFactory
     */
    private $hierarchyFactory;

    /**
     * @var \Javid\Company\Model\Company\Structure
     */
    private $structure;

    /**
     * @var \Javid\Company\Api\CompanyRepositoryInterface
     */
    private $companyRepository;

    /**
     * @param \Javid\Company\Api\Data\HierarchyInterfaceFactory $hierarchyFactory
     * @param \Javid\Company\Model\Company\Structure $structure
     * @param \Javid\Company\Api\CompanyRepositoryInterface $companyRepository
     */
    public function __construct(
        \Javid\Company\Api\Data\HierarchyInterfaceFactory $hierarchyFactory,
        \Javid\Company\Model\Company\Structure $structure,
        \Javid\Company\Api\CompanyRepositoryInterface $companyRepository
    ) {
        $this->hierarchyFactory = $hierarchyFactory;
        $this->structure = $structure;
        $this->companyRepository = $companyRepository;
    }

    /**
     * @inheritdoc
     */
    public function moveNode($id, $newParentId)
    {
        $this->structure->moveNode($id, $newParentId);
    }

    /**
     * @inheritdoc
     */
    public function getCompanyHierarchy($id)
    {
        $company = $this->companyRepository->get($id);
        $tree = $this->structure->getTreeByCustomerId($company->getSuperUserId());
        if (!$tree) {
            return [];
        }
        $data = $this->getTreeAsFlatObjectArray($tree);
        return $data;
    }

    /**
     * Gets tree as flat array of objects.
     *
     * @param \Magento\Framework\Data\Tree\Node $tree
     * @return array
     */
    private function getTreeAsFlatObjectArray(\Magento\Framework\Data\Tree\Node $tree)
    {
        $data = [];
        if ($tree->hasChildren()) {
            foreach ($tree->getChildren() as $child) {
                $data = array_merge($data, $this->getTreeAsFlatObjectArray($child));
            }
        }
        $data[] = $this->hierarchyFactory->create([
            'data' => [
                'structure_id' => $tree->getData('structure_id'),
                'structure_parent_id' => $tree->getData('parent_id'),
                'entity_id' => $tree->getData('entity_id'),
                'entity_type' => ($tree->getData('entity_type') == StructureInterface::TYPE_CUSTOMER)
                    ? \Javid\Company\Api\Data\HierarchyInterface::TYPE_CUSTOMER
                    : \Javid\Company\Api\Data\HierarchyInterface::TYPE_TEAM
            ]
        ]);
        return $data;
    }
}

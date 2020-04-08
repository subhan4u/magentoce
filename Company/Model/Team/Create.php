<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model\Team;

use Javid\Company\Api\Data\StructureInterface;

/**
 * Class for creating a team entity.
 */
class Create
{
    /**
     * @var \Javid\Company\Model\Company\Structure
     */
    private $structureManager;

    /**
     * @var \Javid\Company\Model\ResourceModel\Team
     */
    private $teamResource;

    /**
     * @var \Javid\Company\Api\CompanyRepositoryInterface
     */
    private $companyRepository;

    /**
     * @param \Javid\Company\Model\ResourceModel\Team $teamResource
     * @param \Javid\Company\Model\Company\Structure $structureManager
     * @param \Javid\Company\Api\CompanyRepositoryInterface $companyRepository
     */
    public function __construct(
        \Javid\Company\Model\ResourceModel\Team $teamResource,
        \Javid\Company\Model\Company\Structure $structureManager,
        \Javid\Company\Api\CompanyRepositoryInterface $companyRepository
    ) {
        $this->teamResource = $teamResource;
        $this->structureManager = $structureManager;
        $this->companyRepository = $companyRepository;
    }

    /**
     * Creates a team for a company which id is specified. Validates that the team is new and was not saved before.
     *
     * @param \Javid\Company\Api\Data\TeamInterface $team
     * @param int $companyId
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function create(\Javid\Company\Api\Data\TeamInterface $team, $companyId)
    {
        if ($team->getId()) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__('Could not create team'));
        }
        $company = $this->companyRepository->get($companyId);
        $companyTree = $this->structureManager->getTreeByCustomerId($company->getSuperUserId());
        $this->teamResource->save($team);
        $this->structureManager->addNode(
            $team->getId(),
            StructureInterface::TYPE_TEAM,
            $companyTree->getId()
        );
    }
}

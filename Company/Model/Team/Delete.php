<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model\Team;

use Magento\Framework\Exception\LocalizedException;

/**
 * Class for deleting a team entity.
 */
class Delete
{
    /**
     * @var \Javid\Company\Model\StructureRepository
     */
    private $structureRepository;

    /**
     * @var \Javid\Company\Model\Company\Structure
     */
    private $structureManager;

    /**
     * @var \Javid\Company\Model\ResourceModel\Team
     */
    protected $teamResource;

    /**
     * @param \Javid\Company\Model\ResourceModel\Team $teamResource
     * @param \Javid\Company\Model\StructureRepository $structureRepository
     * @param \Javid\Company\Model\Company\Structure $structureManager
     */
    public function __construct(
        \Javid\Company\Model\ResourceModel\Team $teamResource,
        \Javid\Company\Model\StructureRepository $structureRepository,
        \Javid\Company\Model\Company\Structure $structureManager
    ) {
        $this->teamResource = $teamResource;
        $this->structureRepository = $structureRepository;
        $this->structureManager = $structureManager;
    }

    /**
     * Deletes a team.
     *
     * @param \Javid\Company\Api\Data\TeamInterface $team
     * @return void
     * @throws LocalizedException
     */
    public function delete(\Javid\Company\Api\Data\TeamInterface $team)
    {
        $structure = $this->structureManager->getStructureByTeamId($team->getId());
        if ($structure) {
            $structureNode = $this->structureManager->getTreeById($structure->getId());
            if ($structureNode && $structureNode->hasChildren()) {
                throw new LocalizedException(
                    __(
                        'This team has child users or teams aligned to it and cannot be deleted.'
                        . ' Please re-align the child users or teams first.'
                    )
                );
            }
            $this->structureRepository->deleteById($structure->getId());
        }
        $this->teamResource->delete($team);
    }
}

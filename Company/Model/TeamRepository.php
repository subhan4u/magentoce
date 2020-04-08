<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Model;

use Magento\Framework\Exception\LocalizedException;

/**
 * A repository for managing team entity.
 */
class TeamRepository implements \Javid\Company\Api\TeamRepositoryInterface
{
    /**
     * @var \Javid\Company\Api\Data\TeamInterface[]
     */
    private $instances = [];

    /**
     * @var \Javid\Company\Model\TeamFactory
     */
    private $teamFactory;

    /**
     * @var \Javid\Company\Model\ResourceModel\Team
     */
    private $teamResource;

    /**
     * @var \Javid\Company\Model\Team\Delete
     */
    private $teamDeleter;

    /**
     * @var \Javid\Company\Model\Team\Create
     */
    private $teamCreator;

    /**
     * @var \Javid\Company\Model\Team\GetList
     */
    private $getLister;

    /**
     * @param TeamFactory $teamFactory
     * @param ResourceModel\Team $teamResource
     * @param \Javid\Company\Model\Team\Delete $teamDeleter
     * @param \Javid\Company\Model\Team\Create $teamCreator
     * @param \Javid\Company\Model\Team\GetList $getLister
     */
    public function __construct(
        \Javid\Company\Model\TeamFactory $teamFactory,
        \Javid\Company\Model\ResourceModel\Team $teamResource,
        \Javid\Company\Model\Team\Delete $teamDeleter,
        \Javid\Company\Model\Team\Create $teamCreator,
        \Javid\Company\Model\Team\GetList $getLister
    ) {
        $this->teamFactory = $teamFactory;
        $this->teamResource = $teamResource;
        $this->teamDeleter = $teamDeleter;
        $this->teamCreator = $teamCreator;
        $this->getLister = $getLister;
    }

    /**
     * @inheritdoc
     */
    public function create(\Javid\Company\Api\Data\TeamInterface $team, $companyId)
    {
        $this->checkRequiredFields($team);
        try {
            $this->teamCreator->create($team, $companyId);
        } catch (LocalizedException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Could not create team'),
                $e
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function save(\Javid\Company\Api\Data\TeamInterface $team)
    {
        $this->checkRequiredFields($team);
        if (!$team->getId()) {
            throw new LocalizedException(__(
                '"%fieldName" is required. Enter and try again.',
                ['fieldName' => 'id']
            ));
        } else {
            $this->get($team->getId());
        }
        try {
            $this->teamResource->save($team);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Could not update team'),
                $e
            );
        }
        unset($this->instances[$team->getId()]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function get($teamId)
    {
        if (!isset($this->instances[$teamId])) {
            /** @var Team $team */
            $team = $this->teamFactory->create();
            $team->load($teamId);
            if (!$team->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException(
                    __(
                        'No such entity with %fieldName = %fieldValue',
                        [
                            'fieldName' => 'id',
                            'fieldValue' => $teamId
                        ]
                    )
                );
            }
            $this->instances[$teamId] = $team;
        }
        return $this->instances[$teamId];
    }

    /**
     * @inheritdoc
     */
    public function delete(\Javid\Company\Api\Data\TeamInterface $team)
    {
        try {
            $this->teamDeleter->delete($team);
        } catch (LocalizedException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new LocalizedException(
                __(
                    'Cannot delete team with id %1',
                    $team->getId()
                ),
                $e
            );
        }
        unset($this->instances[$team->getId()]);
    }

    /**
     * @inheritdoc
     */
    public function deleteById($teamId)
    {
        $team = $this->get($teamId);
        $this->delete($team);
    }

    /**
     * @inheritdoc
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        return $this->getLister->getList($criteria);
    }

    /**
     * Checks if entity has all the required fields.
     *
     * @param \Javid\Company\Api\Data\TeamInterface $team
     * @return void
     * @throws LocalizedException
     */
    private function checkRequiredFields(\Javid\Company\Api\Data\TeamInterface $team)
    {
        if (!$team->getName()) {
            throw new LocalizedException(__(
                '"%fieldName" is required. Enter and try again.',
                ['fieldName' => 'name']
            ));
        }
    }
}

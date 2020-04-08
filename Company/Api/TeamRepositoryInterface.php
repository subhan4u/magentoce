<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Javid\Company\Api;

/**
 * Interface for basic CRUD operations for team entity.
 *
 * @api
 * @since 100.0.0
 */
interface TeamRepositoryInterface
{
    /**
     * Create a team in the company structure.
     *
     * @param \Javid\Company\Api\Data\TeamInterface $team
     * @param int $companyId
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function create(\Javid\Company\Api\Data\TeamInterface $team, $companyId);

    /**
     * Update a team in the company structure.
     *
     * @param \Javid\Company\Api\Data\TeamInterface $team
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Javid\Company\Api\Data\TeamInterface $team);

    /**
     * Returns data for a team in the company, by entity id.
     *
     * @param int $teamId
     * @return \Javid\Company\Api\Data\TeamInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($teamId);

    /**
     * Delete team.
     *
     * @param \Javid\Company\Api\Data\TeamInterface $team
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Javid\Company\Api\Data\TeamInterface $team);

    /**
     * Delete a team from the company structure.
     *
     * @param int $teamId
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById($teamId);

    /**
     * Returns the list of teams for the specified search criteria (team name or description).
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Javid\Company\Api\Data\TeamSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \InvalidArgumentException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}

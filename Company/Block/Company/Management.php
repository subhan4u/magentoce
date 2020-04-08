<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Block\Company;

use Javid\Company\Api\Data\StructureInterface;
use Javid\Company\Api\Data\TeamInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * Company management block.
 *
 * @api
 * @since 100.0.0
 */
class Management extends \Magento\Framework\View\Element\Template
{
    /**
     * Until what level the tree is open.
     *
     * @var string
     */
    private $level = 2;

    /**
     * Customer icon.
     *
     * @var string
     */
    private $iconCustomer = 'icon-customer';

    /**
     * Team icon.
     *
     * @var string
     */
    private $iconTeam = 'icon-company';

    /**
     * Open state.
     *
     * @var bool
     */
    private $stateOpen = true;

    /**
     * Closed bool.
     *
     * @var string
     */
    private $stateClosed = false;

    /**
     * @var \Magento\Authorization\Model\UserContextInterface
     */
    private $customerContext;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    private $jsonHelper;

    /**
     * @var bool|null
     */
    private $isSuperUser = null;

    /**
     * @var \Javid\Company\Model\Company\Structure
     */
    private $treeManagement;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Javid\Company\Api\CompanyManagementInterface
     */
    private $companyManagement;

    /**
     * @var \Javid\Company\Api\AuthorizationInterface
     */
    private $authorization;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Authorization\Model\UserContextInterface $customerContext
     * @param \Javid\Company\Model\Company\Structure $treeManagement
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param CustomerRepositoryInterface $customerRepository
     * @param \Javid\Company\Api\CompanyManagementInterface $companyManagement
     * @param \Javid\Company\Api\AuthorizationInterface $authorization
     * @param array $data [optional]
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Authorization\Model\UserContextInterface $customerContext,
        \Javid\Company\Model\Company\Structure $treeManagement,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        CustomerRepositoryInterface $customerRepository,
        \Javid\Company\Api\CompanyManagementInterface $companyManagement,
        \Javid\Company\Api\AuthorizationInterface $authorization,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerContext = $customerContext;
        $this->jsonHelper = $jsonHelper;
        $this->treeManagement = $treeManagement;
        $this->customerRepository = $customerRepository;
        $this->companyManagement = $companyManagement;
        $this->authorization = $authorization;
    }

    /**
     * Gets prepare tree array.
     *
     * @param \Magento\Framework\Data\Tree\Node $tree
     * @param int $level [optional]
     * @return array
     */
    private function getTreeAsArray(\Magento\Framework\Data\Tree\Node $tree, $level = 0)
    {
        $data = $this->getTreeItemAsArray($tree);
        if ($tree->hasChildren()) {
            $data['state']['opened'] = ($level < $this->level) ? $this->stateOpen : $this->stateClosed;
            foreach ($tree->getChildren() as $child) {
                $data['children'][] = $this->getTreeAsArray($child, ($level + 1));
            }
            $this->sortTreeArray($data['children']);
        }
        return $data;
    }

    /**
     * Sorts tree array.
     *
     * @param array $treeArray
     * @return void
     */
    private function sortTreeArray(array &$treeArray)
    {
        usort($treeArray, function ($elementA, $elementB) {
            return strcmp($elementA['text'], $elementB['text']);
        });
    }

    /**
     * Gets tree item as array.
     *
     * @param \Magento\Framework\Data\Tree\Node $tree
     * @return array
     */
    private function getTreeItemAsArray(\Magento\Framework\Data\Tree\Node $tree)
    {
        $data = [];
        if ($tree->getData(StructureInterface::ENTITY_TYPE) == StructureInterface::TYPE_TEAM) {
            $data['type'] = $this->iconTeam;
            $data['text'] = $this->escapeHtml($tree->getData(TeamInterface::NAME));
            $data['description'] = $this->escapeHtml($tree->getData(TeamInterface::DESCRIPTION));
        } else {
            $data['type'] = $this->iconCustomer;
            $data['text'] = $this->escapeHtml(
                $tree->getData(CustomerInterface::FIRSTNAME) .
                ' ' .
                $tree->getData(CustomerInterface::LASTNAME)
            );
            if ($this->customerContext->getUserId() == $tree->getData(StructureInterface::ENTITY_ID)) {
                $data['text'] .= ' ' . __('(me)');
            }
        }
        $data['attr']['data-tree-id'] = $tree->getData(StructureInterface::STRUCTURE_ID);
        $data['attr']['data-entity-id'] = $tree->getData(StructureInterface::ENTITY_ID);
        $data['attr']['data-entity-type'] = $tree->getData(StructureInterface::ENTITY_TYPE);
        return $data;
    }

    /**
     * Gets if current user is an SU.
     *
     * @return bool
     */
    public function isSuperUser()
    {
        if ($this->isSuperUser === null) {
            $this->isSuperUser = $this->authorization->isAllowed('Javid_Company::users_edit');
        }
        return $this->isSuperUser;
    }

    /**
     * Gets tree array.
     *
     * @return array
     */
    public function getTree()
    {
        $result = [];
        $customerId = $this->customerContext->getUserId();
        if ($customerId) {
            $tree = $this->treeManagement->getTreeByCustomerId($customerId);
            $this->treeManagement->addDataToTree($tree);
            $this->treeManagement->filterTree($tree, 'is_active', true);
            if ($tree->getData(StructureInterface::ENTITY_ID) == $customerId) {
                $this->isSuperUser = true;
            }
            $result = $this->getTreeAsArray($tree);
        }
        return $result;
    }

    /**
     * Get tree js options.
     *
     * @return array
     */
    public function getTreeJsOptions()
    {
        return [
            'hierarchyTree' => [
                'moveUrl'   => $this->getUrl('*/structure/manage'),
                'selectionLimit' => 1,
                'draggable' => $this->isSuperUser(),
                'initData'  => $this->getUrl('*/structure/get'),
                'adminUserRoleId' => 0
            ]
        ];
    }

    /**
     * Get json helper.
     *
     * @return \Magento\Framework\Json\Helper\Data
     */
    public function getJsonHelper()
    {
        return $this->jsonHelper;
    }

    /**
     * Has current customer company.
     *
     * @return bool
     */
    public function hasCustomerCompany()
    {
        $hasCompany = false;
        $customerId = $this->customerContext->getUserId();
        if ($customerId) {
            $company = $this->companyManagement->getByCustomerId($customerId);
            if ($company) {
                $hasCompany = true;
            }
        }

        return $hasCompany;
    }
}

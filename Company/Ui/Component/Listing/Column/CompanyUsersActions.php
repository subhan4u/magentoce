<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Javid\Company\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * Class CompanyUsersActions.
 */
class CompanyUsersActions extends Column
{
    /**
     * Url interface.
     *
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * Customer status Active.
     *
     * @var string
     */
    private $customerStatusActive = 'Active';

    /**
     * @var \Javid\Company\Api\RoleManagementInterface
     */
    private $roleManagement;

    /**
     * @var \Javid\Company\Api\AuthorizationInterface
     */
    private $authorization;

    /**
     * CompanyUsersActions constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param \Javid\Company\Api\RoleManagementInterface $roleManagement
     * @param \Javid\Company\Api\AuthorizationInterface $authorization
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        \Javid\Company\Api\RoleManagementInterface $roleManagement,
        \Javid\Company\Api\AuthorizationInterface $authorization,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
        $this->roleManagement = $roleManagement;
        $this->authorization = $authorization;
    }

    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if ($this->authorization->isAllowed('Javid_Company::users_edit') && isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $getUrl = $this->urlBuilder->getUrl('company/customer/get');
                $provider = 'company_users_listing.company_users_listing_data_source';
                $item[$this->getData('name')]['edit'] = [
                    'href' => '#',
                    'label' => __('Edit'),
                    'hidden' => false,
                    'type' => 'edit-user',
                    'options' => [
                        'getUrl' => $getUrl,
                        'getUserUrl' => $getUrl . '?customer_id=' . $item['entity_id'],
                        'saveUrl' => $this->urlBuilder->getUrl('company/customer/manage'),
                        'id' => $item['entity_id'],
                        'gridProvider' => $provider,
                        'adminUserRoleId' => $this->roleManagement->getCompanyAdminRoleId(),
                    ],
                ];
                $item[$this->getData('name')]['delete'] = [
                    'href' => '#',
                    'label' => __('Delete'),
                    'hidden' => false,
                    'id' => $item['entity_id'],
                    'type' => 'delete-user',
                    'options' => [
                        'setInactiveUrl' => $this->urlBuilder->getUrl('company/customer/delete'),
                        'deleteUrl' => $this->urlBuilder->getUrl('company/customer/permanentDelete'),
                        'id' => $item['entity_id'],
                        'gridProvider' => $provider,
                        'inactiveClass' => $this->getSetInactiveButtonClass($item),
                    ],
                ];
            }
        }

        return $dataSource;
    }

    /**
     * Get set inactive button class.
     *
     * @param array $userData
     * @return string
     */
    private function getSetInactiveButtonClass(array $userData)
    {
        return ($this->isShowSetInactiveButton($userData)) ? '' : '_hidden';
    }

    /**
     * Is show set inactive button.
     *
     * @param array $userData
     * @return bool
     */
    private function isShowSetInactiveButton(array $userData)
    {
        return (!empty($userData['status']) && $userData['status']->getText() == $this->customerStatusActive);
    }
}

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            roleTree: 'Javid_Company/js/role-tree',
            hierarchyTree: 'Javid_Company/js/hierarchy-tree',
            hierarchyTreePopup: 'Javid_Company/js/hierarchy-tree-popup'
        }
    },
    config: {
        mixins: {
            'mage/validation': {
                'Javid_Company/js/validation': true
            }
        }
    }
};

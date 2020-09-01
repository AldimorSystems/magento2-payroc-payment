/**
 * @category    AldimorSystems
 * @package     AldimorSystems_Payroc
 * @author      Alejandro Diaz
 * @copyright   AldimorSystems (https://aldimorsystems.com)
 */
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'aldimorsystems_payroc',
                component: 'AldimorSystems_Payroc/js/view/payment/method-renderer/payroc'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'Magento_Ui/js/form/element/abstract',
    'mageUtils'
], function (Abstract, utils) {
    'use strict';

    return Abstract.extend({
        defaults: {
            sourceCode: null,
            month : 0,
        },

        validate : function (month){
            if (month === 2){
                this.validation['less-than-equals-to'] =  29;

            }
            if (month === 4 || month === 6 || month === 9 || month === 11){
                this.validation['less-than-equals-to'] = 30;
            }
            return this._super();
        },
        /**
         * Toggle disabled state.
         *
         * @param {String} selected
         */
        monthPicker: function (selected) {
            this.validate(parseInt(selected));
        },
    });
});

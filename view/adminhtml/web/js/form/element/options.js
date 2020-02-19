define(
    [
    'jquery',
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'Magento_Ui/js/modal/modal'
    ], function ($,_, uiRegistry, select, modal) {
        'use strict';


        $(document).on(
            "change", '#productsGrid_table input[type="checkbox"]', function () {
                var field3 = uiRegistry.get('index = products');
                field3.value($('input[name="products"]').val());
            }
        );

        return select.extend(
            {


                initialize: function () {
                    this._super();
                    var selectjs = this;
                    var field3 = uiRegistry.get('index = products');
                    field3.hide();
                    $('#tab_rss_product_content').hide();
                    setTimeout(
                        function () {
                            selectjs.resetVisibility();
                        },500
                    )

                    return this;
                },
        
                resetVisibility: function () {
                    var field2 = uiRegistry.get('index = category');
            
                    if (this.value() == 1) {
                        $('#tab_rss_product_content').hide();
                    } else {
                        field2.hide();
                        $('#tab_rss_product_content').show();
                    }
                },

                onUpdate: function (value) {
                    var field2 = uiRegistry.get('index = category');
            
                    if (value == 1) {
                        $('#tab_rss_product_content').hide();
                        field2.show();    
                    } else {
                        field2.hide();
                        $('#tab_rss_product_content').show();
                    }
                    return this._super();

                }
            }
        );
    }
); 
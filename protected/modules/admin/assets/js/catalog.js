

(function($) {

    var tree = $('.categories .tree');

    tree.on('click', '.expand-button', function(e) {
        var self = $(this);
        var category_id = self.parent('li').data('id');
        var product_grid = $('#product_grid');
        var filterData = $('.filters', product_grid).find('input, select').serialize();
        product_grid.yiiGridView('update', {
            data: {
                Product: {
                    category_id: category_id,
                    name: $('#Product_name', product_grid).val()
                }
            }
        });
    });

}(jQuery));
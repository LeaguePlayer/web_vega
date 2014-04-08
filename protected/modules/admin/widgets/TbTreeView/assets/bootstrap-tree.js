!function($) {

    $.fn.tbtreeview = function(options) {
        var opt = $.extend({
        }, options || {});

        this.each(function() {
            var el = $(this);

            el.children('ul').attr('role', 'tree').find('ul').attr('role', 'group');
            el.find('li.closed').find(' > ul > li').hide();
            el.find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem');
            el.find('li').find(' > span').attr('title', 'Collapse this branch').on('click', function (e) {
                var self = $(this);
                $('.active', el).removeClass('active').find('.expand-button').children('i').removeClass('icon-white');
                self.parent('li').addClass('active').end().children('i').addClass('icon-white');

                if ( self.has('ul') ) {
                    var children = self.parent('li.parent_li').find(' > ul > li');
                    if (children.is(':visible')) {
                        children.hide('fast');
                        self.attr('title', 'Expand this branch').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
                    }
                    else {
                        children.show('fast');
                        self.attr('title', 'Collapse this branch').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
                    }
                }
            });
        });
    }

}(window.jQuery);
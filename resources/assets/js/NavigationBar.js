import $ from 'jquery';
import _ from 'lodash';

class NavigationBar
{
    constructor()
    {
        var $navigationBar = $('.navigation-bar');

        this.links = $navigationBar.data('navigationLinks');

        $navigationBar.removeAttr('data-navigation-links');

        this.$navigationBarContainer = $navigationBar.find('.navigation-container ul');

        console.log(this.$navigationBarContainer);

        this.drawNavigationLinks();

        $('a.dropdown-submenu').on('click', function(e){
            e.stopPropagation();

            e.preventDefault();

            $(this).next('ul').toggle();
        });
    }

    drawNavigationLinks()
    {
        _.map(this.links, (link) => {

            var hasChildren = Boolean(link.children && link.children.length);

            var $li = $('<li />');

            var $a = $('<a />', {text: link.title, href: link.slug});

            if (hasChildren) {
                $li.attr('class', 'dropdown');

                $a.attr({
                    href: '#',
                    'class': 'dropdown-toggle',
                    'data-toggle': 'dropdown',
                    'role': 'button',
                    'aria-haspopup': true,
                    'aria-expanded': false
                });

                $('<span />', {'class': 'caret'}).appendTo($a);
            }

            $a.appendTo($li);

            $li.appendTo(this.$navigationBarContainer);

            if (hasChildren) {
                this.drawChildrenLinks($li, link.children);
            }
        });
    }

    drawChildrenLinks($parent, children)
    {
        var $ul = $('<ul />', {'class': 'dropdown-menu'});

        _.map(children, (child) => {

            var hasChildren = Boolean(child.children && child.children.length);

            var $li = $('<li />');

            if (hasChildren) {
                $li.attr('class', 'dropdown-submenu');

                $('<a />', {
                    text: child.title,
                    href: '#',
                    'class': 'dropdown-submenu'
                }).appendTo($li);
            } else {
                $('<a />', {text: child.title, href: child.slug}).appendTo($li);
            }

            $li.appendTo($ul);

            if (hasChildren) {
                this.drawChildrenLinks($li, child.children);
            }
        });

        $ul.appendTo($parent);
    }
}

export default NavigationBar;
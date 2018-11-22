'use strict';

import $ from 'jquery';

class NavigationLinks
{
    loadLinks(onLoad)
    {
        $.ajax({
            url: '/api/admin/navigation_links',
            type: 'get',
            dataType: 'json',
            success: function (response) {
                if (response.links instanceof Array) {
                    this.links = response.links;
                }

                if (typeof onLoad === 'function') {
                    onLoad.call(null, this.links);
                }
            }.bind(this)
        });
    }

    constructor()
    {
        this.links = [];
    }
}

export default NavigationLinks;
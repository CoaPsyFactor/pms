'use-strict';

import NavigationBar from './NavigationBar';
import $ from 'jquery';
import Router from './Router';

$(document).ready(() => {
    let router = new Router();

    router.register('admin.*', () => {
        let adminRouter = new Router();

        adminRouter.register('admin/plugins', () => {
            console.log('Plugins');
        });

        adminRouter.register('admin/navigation', () => {
            console.log('Navigation');
        });

        adminRouter.register('admin/{module}', (module) => {
            console.log(module.charAt(0).toUpperCase() + module.slice(1).toLowerCase() );
        });

        adminRouter.run();
    });

    router.register('(?!admin).*', () => {
        new NavigationBar();
    });

    router.run();
});
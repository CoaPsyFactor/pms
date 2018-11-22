'use strict';

let createRouteRegex = (route) => {
    if (typeof route !== 'string' || 0 === route.length) {
        return null;
    }

    return {
        regex: new RegExp(route.replace(/\{\w+\}/g, '([\\W\\w\\X]+)') + '$' || ''),
        parametersCount: (route.match(/\{\w+\}/gi) || []).length
    };
};

class Router
{
    constructor()
    {
        this.routes = [];

        this.language = 'en';
    }

    register(route, callback)
    {

        if (typeof route !== 'string' || 0 === route.length) {
            return;
        }

        if ('/' === route.charAt(0)) {
            route = route.slice(1);
        }

        route = '/([\\w]+)/' + route;

        let regexRoute = createRouteRegex(route);

        if (null === regexRoute) {
            return;
        }

        if (false === this.routes[regexRoute.parametersCount] instanceof Array) {
            this.routes[regexRoute.parametersCount] = [];
        }

        this.routes[regexRoute.parametersCount].push({
            route: regexRoute.regex,
            callback: callback
        });
    }

    run()
    {
        let path = window.location.pathname;

        let routeMatched = false;

        let executeRoute = (route) => {
            let routeRegex = route.route;

            let matches = path.match(routeRegex);

            if (null === matches) {
                return false;
            }

            if (typeof route.callback === 'function') {
                matches.shift();

                this.language = matches.splice(0, 1);

                route.callback.apply(null, matches);

                routeMatched = true;
            }
        };

        this.routes.forEach((routes) => {
            if (routeMatched) {
                return false;
            }

            routes.forEach((route) => {
                if (routeMatched) {
                    return false;
                }

                executeRoute(route);
            });
        });
    }
}

export default Router;

;(function($) {
    'use strict';

    var app = {
        _routes: {},
        _routeDefault: '',
        _routeError: '',
        _currentController: null,
        _routeHooks: {},
        _anchors: {},

        init: function(config) {
           
            this._routeDefault = config.defaultView || '';
            this._routeError = config.errorView || '';
            this._anchors = config.anchors || {};

           
            if (config.routes) {
                for (var route in config.routes) {
                    this._routes[route] = config.routes[route];
                }
            }

           
            var self = this;
            $(window).on('hashchange', function() {
                self.run();
            });

           
            $(document).on('click', 'a[href^="#"]', function(e) {
                var href = $(this).attr('href');
                if (href && href !== '#') {
                    window.location.hash = href;
                }
            });

            
            this.run();

            return this;
        },

        route: function(config) {
            var route = config.view || '';

            this._routes[route] = {
                view: config.view,
                load: config.load,
                onCreate: config.onCreate,
                onReady: config.onReady
            };

            return this;
        },

        run: function() {
            var hash = window.location.hash.replace('#', '').split('?')[0].split('/')[0] || this._routeDefault;

           
            var route = this._routes[hash];

            if (!route && this._routeError) {
                hash = this._routeError;
                route = this._routes[hash];
            }

            if (!route) {
                console.error('SPApp: Route not found for hash: ' + hash);
                return;
            }

            
            if (route.onCreate && typeof route.onCreate === 'function') {
                route.onCreate();
            }

           
            if (route.load) {
                var container = this._anchors.main || '#main';
                var self = this;

                $(container).load(route.load, function(response, status) {
                    if (status === 'error') {
                        console.error('SPApp: Failed to load view: ' + route.load);
                        return;
                    }

                   
                    if (route.onReady && typeof route.onReady === 'function') {
                        route.onReady();
                    }

                   
                    window.scrollTo(0, 0);
                });
            }

            this._currentController = hash;

            return this;
        },

        getCurrentController: function() {
            return this._currentController;
        },

        loadView: function(view) {
            window.location.hash = '#' + view;
            return this;
        }
    };

    
    $.spapp = app;

})(jQuery);

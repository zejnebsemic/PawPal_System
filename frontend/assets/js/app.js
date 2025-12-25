function waitForForm(selector, callback, attempts = 0) {
  const $form = $(selector);

  if ($form.length > 0) {
    callback($form);
    return;
  }

  if (attempts > 20) {
    console.error("Form not mounted:", selector);
    return;
  }

  setTimeout(() => {
    waitForForm(selector, callback, attempts + 1);
  }, 50);
}

$(document).ready(function () {
    
    var app = $.spapp.init({
        defaultView: "home",
        templateDir: "pages/",
        anchors: {
            main: '#app-content'
        },
        routes: {
            'home': {
                view: 'home',
                load: 'pages/home.html',
                onCreate: function() {
                    if (typeof UserService !== 'undefined') {
                        UserService.generateMenuItems();
                    }
                },
                onReady: function() {
                   
                    const currentHash = window.location.hash;
                    if (currentHash !== '#login' && currentHash !== '#register') {
                        if (typeof initHomePage === 'function') {
                            initHomePage();
                        }
                    }
                }
            },
            'login': {
                view: 'login',
                load: 'pages/login.html',
                onCreate: function() {
                   
                },
                onReady: function() {
                    waitForForm("#login-form", function($form) {
                        if (typeof UserService !== 'undefined' && UserService.initLogin) {
                            UserService.initLogin($form);
                        }
                    });
                }
            },
            'register': {
                view: 'register',
                load: 'pages/register.html',
                onCreate: function() {
                    
                },
                onReady: function() {
                    waitForForm("#register-form", function($form) {
                        if (typeof UserService !== 'undefined' && UserService.initRegister) {
                            UserService.initRegister($form);
                        }
                    });
                }
            },
            'browse': {
                view: 'browse',
                load: 'pages/browse.html',
                onCreate: function() {
                    if (typeof UserService !== 'undefined') {
                        UserService.generateMenuItems();
                    }
                },
                onReady: function() {
                    if (typeof initBrowsePage === 'function') {
                        initBrowsePage();
                    }
                }
            },
            'pet-detail': {
                view: 'pet-detail',
                load: 'pages/pet-detail.html',
                onCreate: function() {
                    if (typeof UserService !== 'undefined') {
                        UserService.generateMenuItems();
                    }
                },
                onReady: function() {
                    if (typeof initPetDetailPage === 'function') {
                        initPetDetailPage();
                    }
                }
            },
            'profile': {
                view: 'profile',
                load: 'pages/profile.html',
                onCreate: function() {
                    if (typeof UserService === 'undefined' || !UserService.requireLogin()) return false;
                    UserService.generateMenuItems();
                },
                onReady: function() {
                    if (typeof initProfilePage === 'function') {
                        initProfilePage();
                    }
                }
            },
            'requests': {
                view: 'requests',
                load: 'pages/requests.html',
                onCreate: function() {
                    if (typeof UserService === 'undefined' || !UserService.requireLogin()) return false;
                    UserService.generateMenuItems();
                },
                onReady: function() {
                    if (typeof initRequestsPage === 'function') {
                        initRequestsPage();
                    }
                }
            },
            'admin': {
                view: 'admin',
                load: 'pages/admin.html',
                onCreate: function() {
                    if (typeof UserService === 'undefined' || !UserService.requireAdmin()) return false;
                    UserService.generateMenuItems();
                },
                onReady: function() {
                    if (typeof initAdminPage === 'function') {
                        initAdminPage();
                    }
                }
            },
            'reviews': {
                view: 'reviews',
                load: 'pages/reviews.html',
                onCreate: function() {
                    if (typeof UserService !== 'undefined') {
                        UserService.generateMenuItems();
                    }
                },
                onReady: function() {
                    if (typeof initReviewsPage === 'function') {
                        initReviewsPage();
                    }
                }
            },
            'shelters': {
                view: 'shelters',
                load: 'pages/shelters.html',
                onCreate: function() {
                    if (typeof UserService !== 'undefined') {
                        UserService.generateMenuItems();
                    }
                },
                onReady: function() {
                    if (typeof initSheltersPage === 'function') {
                        initSheltersPage();
                    }
                }
            }
        }
    });
    
   
    if (typeof UserService !== 'undefined') {
        const currentHash = window.location.hash;
        if (currentHash !== '#login' && currentHash !== '#register') {
            UserService.generateMenuItems();
        }
    }
    
    
    $(document).on('click', '#logout-btn', function(e) {
        e.preventDefault();
        if (typeof UserService !== 'undefined') {
            UserService.logout();
        }
    });
    
    
    $(window).on('hashchange', function() {
        if (typeof UserService !== 'undefined') {
            
            const currentHash = window.location.hash;
            if (currentHash !== '#login' && currentHash !== '#register') {
                if ($("#login-form").length && $("#login-form").data("validator")) {
                    $("#login-form").validate().destroy();
                }
                if ($("#register-form").length && $("#register-form").data("validator")) {
                    $("#register-form").validate().destroy();
                }
                UserService.generateMenuItems();
            }
        }
    });
});

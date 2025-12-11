var UserService = {
    init: function () {
        var token = localStorage.getItem("user_token");
        if (token && token !== undefined) {
            this.generateMenuItems();
        }
        
        if ($("#login-form").length) {
            $("#login-form").validate({
                submitHandler: function (form) {
                    var entity = Object.fromEntries(new FormData(form).entries());
                    UserService.login(entity);
                },
            });
        }
        
        if ($("#register-form").length) {
            $("#register-form").validate({
                rules: {
                    confirm_password: {
                        equalTo: "#password"
                    }
                },
                submitHandler: function (form) {
                    var entity = Object.fromEntries(new FormData(form).entries());
                    UserService.register(entity);
                },
            });
        }
    },
    
    login: function (entity) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/login",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                console.log(result);
                localStorage.setItem("user_token", result.data.token);
                localStorage.setItem("user", JSON.stringify(result.data.user));
                toastr.success("Logged in successfully");
                
                UserService.generateMenuItems();
                window.location.hash = "#home";
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(XMLHttpRequest?.responseText ? XMLHttpRequest.responseText : 'Error');
            },
        });
    },
    
    register: function (entity) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/register",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                console.log(result);
                toastr.success("Registration successful! Please login.");
                window.location.hash = "#login";
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(XMLHttpRequest?.responseText ? XMLHttpRequest.responseText : 'Error');
            },
        });
    },
    
    logout: function () {
        localStorage.removeItem("user_token");
        localStorage.removeItem("user");
        toastr.success("Logged out successfully");
        window.location.hash = "#login";
    },
    
    generateMenuItems: function () {
        const token = localStorage.getItem("user_token");
        if (!token) {
            $("#nav-login").show();
            $("#nav-logout").hide();
            $("#nav-profile").hide();
            $("#nav-admin").hide();
            $("#nav-requests").hide();
            return;
        }
        
        try {
            const user = Utils.parseJwt(token).user;
            
            if (user && user.role) {
                $("#nav-login").hide();
                $("#nav-logout").show();
                $("#nav-profile").show();
                
                if (user.role === Constants.ADMIN_ROLE) {
                    $("#nav-admin").show();
                    $("#nav-requests").hide();
                } else if (user.role === Constants.USER_ROLE) {
                    $("#nav-admin").hide();
                    $("#nav-requests").show();
                }
            }
        } catch (error) {
            console.error("Error parsing token:", error);
            this.logout();
        }
    },
    
    isLoggedIn: function () {
        const token = localStorage.getItem("user_token");
        if (!token) return false;
        
        try {
            const user = Utils.parseJwt(token).user;
            return user && user.role;
        } catch (error) {
            return false;
        }
    },
    
    isAdmin: function () {
        const token = localStorage.getItem("user_token");
        if (!token) return false;
        
        try {
            const user = Utils.parseJwt(token).user;
            return user && user.role === Constants.ADMIN_ROLE;
        } catch (error) {
            return false;
        }
    },
    
    getCurrentUser: function () {
        const token = localStorage.getItem("user_token");
        if (!token) return null;
        
        try {
            return Utils.parseJwt(token).user;
        } catch (error) {
            return null;
        }
    }
};
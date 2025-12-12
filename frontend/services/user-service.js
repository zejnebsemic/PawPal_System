var UserService = {

    init: function () {

        const token = localStorage.getItem("user_token");
        if (token) {
            this.generateMenuItems();
        }

        if ($("#login-form").length) {
            $("#login-form").validate({
                submitHandler: function (form) {
                    let entity = Object.fromEntries(new FormData(form).entries());
                    UserService.login(entity);
                }
            });
        }

        if ($("#register-form").length) {
            $("#register-form").validate({
                submitHandler: function (form) {
                    let entity = Object.fromEntries(new FormData(form).entries());
                    UserService.register(entity);
                }
            });
        }
    },

    login: function (entity) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/login",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            success: function (res) {
                localStorage.setItem("user_token", res.data.token);
                toastr.success("Login successful");
                window.location.href = "index.html";
            },
            error: function (err) {
                toastr.error(err.responseText || "Login failed");
            }
        });
    },

    register: function (entity) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/register",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            success: function () {
                toastr.success("Registration successful");
                window.location.href = "login.html";
            },
            error: function (err) {
                toastr.error(err.responseText || "Registration failed");
            }
        });
    },

    logout: function () {
        localStorage.clear();
        window.location.href = "login.html";
    },

    generateMenuItems: function () {

        const token = localStorage.getItem("user_token");
        if (!token) return;

        const user = Utils.parseJwt(token).user;

        $("#nav-login").hide();
        $("#nav-logout").show();
        $("#nav-profile").show();

        if (user.role === "admin") {
            $("#nav-admin").show();
            $("#nav-requests").hide();
        } else {
            $("#nav-admin").hide();
            $("#nav-requests").show();
        }
    }
};

let AuthService = {
    login: function(email, password, callback) {
        RestClient.post(
            "auth/login",
            JSON.stringify({ email: email, password: password }),
            function(response) {
                localStorage.setItem("user_token", response.data.token);
                localStorage.setItem("current_user", JSON.stringify(response.data));
                callback(true);
            },
            function() {
                callback(false);
            }
        );
    },

    register: function(data, callback) {
        RestClient.post(
            "auth/register",
            JSON.stringify(data),
            function(response) {
                callback(true);
            },
            function() {
                callback(false);
            }
        );
    },

    logout: function() {
        localStorage.removeItem("user_token");
        localStorage.removeItem("current_user");
    },

    getCurrentUser: function() {
        return JSON.parse(localStorage.getItem("current_user"));
    },

    requireAdmin: function () {
        let user = this.getCurrentUser();
        if (!user || user.role !== "admin") {
            window.location.hash = "#home";
            toastr.error("Admins only");
            return false;
        }
        return true;
    },

    updateNavigation: function() {
        let user = this.getCurrentUser();

        if (user) {
            $("#nav-login").hide();
            $("#nav-logout").show();
            $("#nav-profile").show();

            if (user.role === "admin") $("#nav-admin").show();
            else $("#nav-admin").hide();
        } else {
            $("#nav-login").show();
            $("#nav-logout").hide();
            $("#nav-admin").hide();
            $("#nav-profile").hide();
        }
    }
};

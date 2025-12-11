let UserService = {
    init: function () {
        console.log("UserService init called");
        
        var token = localStorage.getItem("token");
        if (token && window.location.pathname.includes("login.html")) {
            window.location.replace("dashboard.html");
        }
        
        $("#login-form").validate({
            submitHandler: function (form) {
                var entity = Object.fromEntries(new FormData(form).entries());
                UserService.login(entity);
            },
        });
        
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
        $.blockUI({ message: '<h3>Logging in...</h3>' });
        
        RestClient.post(
            "/auth/login",
            entity,
            function (result) {
                $.unblockUI();
                console.log(result);
                if (result.success) {
                    localStorage.setItem("token", result.data.token);
                    localStorage.setItem("user", JSON.stringify(result.data));
                    toastr.success("Logged in successfully");
                    window.location.replace("dashboard.html");
                } else {
                    toastr.error(result.error || "Login failed");
                }
            },
            function (XMLHttpRequest, textStatus, errorThrown) {
                $.unblockUI();
                toastr.error(XMLHttpRequest?.responseJSON?.error || XMLHttpRequest.responseText || 'Login failed');
            }
        );
    },
    
    register: function (entity) {
        $.blockUI({ message: '<h3>Registering...</h3>' });
        
        RestClient.post(
            "/auth/register",
            entity,
            function (result) {
                $.unblockUI();
                console.log(result);
                if (result.success) {
                    toastr.success("Registration successful! Please login.");
                    window.location.replace("login.html");
                } else {
                    toastr.error(result.error || "Registration failed");
                }
            },
            function (XMLHttpRequest, textStatus, errorThrown) {
                $.unblockUI();
                toastr.error(XMLHttpRequest?.responseJSON?.error || XMLHttpRequest.responseText || 'Registration failed');
            }
        );
    },
    
    logout: function () {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        toastr.success("Logged out successfully");
        window.location.replace("login.html");
    },
    
    getCurrentUser: function () {
        const userStr = localStorage.getItem("user");
        if (!userStr) return null;
        try {
            return JSON.parse(userStr);
        } catch (e) {
            console.error("Error parsing user data", e);
            return null;
        }
    },
    
    getToken: function () {
        return localStorage.getItem("token");
    },
    
    isAdmin: function () {
        const user = this.getCurrentUser();
        return user && user.role === Constants.ADMIN_ROLE;
    },
    
    isLoggedIn: function () {
        return this.getToken() !== null;
    },
    
    requireAuth: function (redirectUrl = "login.html") {
        if (!this.isLoggedIn()) {
            toastr.warning("Please login to continue");
            window.location.replace(redirectUrl);
            return false;
        }
        return true;
    },
    
    requireAdmin: function (redirectUrl = "dashboard.html") {
        if (!this.requireAuth()) return false;
        
        if (!this.isAdmin()) {
            toastr.error("Access denied: Admin privileges required");
            window.location.replace(redirectUrl);
            return false;
        }
        return true;
    },
    
    generateMenuItems: function () {
        const token = localStorage.getItem("token");
        if (!token) {
            return [
                { name: "Home", url: "index.html", icon: "🏠" },
                { name: "Pets", url: "pets.html", icon: "🐶" },
                { name: "Shelters", url: "shelters.html", icon: "🏠" },
                { name: "Login", url: "login.html", icon: "🔐" },
                { name: "Register", url: "register.html", icon: "📝" }
            ];
        }
        
        try {
            const user = Utils.parseJwt(token).user;
            const menuItems = [
                { name: "Home", url: "dashboard.html", icon: "🏠" },
                { name: "Pets", url: "pets.html", icon: "🐶" },
                { name: "Shelters", url: "shelters.html", icon: "🏠" }
            ];
            
            if (user.role === Constants.ADMIN_ROLE) {
                menuItems.push(
                    { name: "Admin Panel", url: "admin.html", icon: "👑" },
                    { name: "Users", url: "users.html", icon: "👥" },
                    { name: "Adoption Requests", url: "adoption-requests.html", icon: "📋" }
                );
            } else if (user.role === Constants.USER_ROLE) {
                menuItems.push(
                    { name: "My Profile", url: "profile.html?id=" + user.id, icon: "👤" },
                    { name: "My Requests", url: "my-requests.html", icon: "📋" }
                );
            }
            
            menuItems.push({ 
                name: "Logout", 
                url: "#", 
                icon: "🚪",
                onclick: "UserService.logout()",
                class: "text-danger" 
            });
            
            return menuItems;
            
        } catch (error) {
            console.error("Error parsing token:", error);
            localStorage.removeItem("token");
            localStorage.removeItem("user");
            return [
                { name: "Login", url: "login.html", icon: "🔐" }
            ];
        }
    },
    
    renderMenu: function (containerId = "navbar-menu") {
        const menuItems = this.generateMenuItems();
        const nav = document.getElementById(containerId);
        
        if (!nav) {
            console.error("Menu container not found:", containerId);
            return;
        }
        
        nav.innerHTML = "";
        
        menuItems.forEach(item => {
            const li = document.createElement("li");
            li.className = "nav-item mx-0 mx-lg-1";
            
            if (item.onclick) {
                li.innerHTML = `
                    <button class="btn btn-danger ms-3" onclick="${item.onclick}">
                        ${item.icon || ''} ${item.name}
                    </button>
                `;
            } else {
                li.innerHTML = `
                    <a class="nav-link py-3 px-0 px-lg-3 rounded ${item.class || ''}" href="${item.url}">
                        ${item.icon || ''} ${item.name}
                    </a>
                `;
            }
            
            nav.appendChild(li);
        });
    },
    
    canAccess: function (requiredRole, resourceOwnerId = null) {
        if (!this.isLoggedIn()) return false;
        
        const user = this.getCurrentUser();
        if (!user) return false;
        
        if (user.role === Constants.ADMIN_ROLE) return true;
        
        if (requiredRole && user.role !== requiredRole) return false;
        
        if (resourceOwnerId && user.id != resourceOwnerId) return false;
        
        return true;
    }
};
var UserService = {
 initLogin: function ($form) {
   $form.off();

   console.log("initLogin attached to form:", $form);

   $form.on("submit", function (e) {
     e.preventDefault();

     const email = $form[0].elements["email"]?.value;
     const password = $form[0].elements["password"]?.value;

     console.log("LOGIN PAYLOAD:", { email, password });

     if (!email || !password) {
       toastr.error("Email and password are required");
       return;
     }

     UserService.login({
       email: email.trim(),
       password: password
     });
   });
 },
 initRegister: function ($form) {
   $form.off("submit").on("submit", function (e) {
     e.preventDefault();

     const payload = {
       full_name: $form.find("input[name='name']").val(),
       email: $form.find("input[name='email']").val(),
       phone_number: $form.find("input[name='phone']").val(),
       password: $form.find("input[name='password']").val(),
       address: $form.find("input[name='address']").val()
     };

     console.log("REGISTER PAYLOAD:", payload);

     if (!payload.full_name || !payload.email || !payload.password) {
       toastr.error("All required fields must be filled");
       return;
     }

     UserService.register(payload, () => $form[0].reset());
   });
 },
 login: function (entity) {
   $.blockUI({ message: '<h3>Processing...</h3>' });
   
   const loginPayload = {
     email: entity.email,
     password: entity.password
   };
   
   $.ajax({
     url: Constants.PROJECT_BASE_URL + "auth/login",
     type: "POST",
     data: JSON.stringify(loginPayload),
     contentType: "application/json",
     dataType: "json",
     success: function (result) {
       $.unblockUI();
       const token = result.data?.token || (result.data && typeof result.data === 'object' && 'token' in result.data ? result.data.token : null);
       
       if (result.success && result.data && token) {
         localStorage.setItem("user_token", token);
         
         UserService.generateMenuItems();
         
         toastr.success("Login successful!");
         
         window.location.hash = "#home";
       } else {
         const errorMsg = result.error || result.responseJSON?.error || "Login failed: Invalid response";
         toastr.error(errorMsg);
       }
     },
     error: function (XMLHttpRequest, textStatus, errorThrown) {
       $.unblockUI();
       const errorMsg = XMLHttpRequest.responseJSON?.error 
         || XMLHttpRequest.responseJSON?.message
         || XMLHttpRequest.responseText 
         || 'An error occurred';
       toastr.error(errorMsg);
     },
   });
 },
 register: function (entity, successCallback) {
   $.blockUI({ message: '<h3>Processing...</h3>' });
   
   $.ajax({
     url: Constants.PROJECT_BASE_URL + "auth/register",
     type: "POST",
     data: JSON.stringify(entity),
     contentType: "application/json",
     dataType: "json",
     success: function (result) {
       $.unblockUI();
       if (result.success && result.data) {
         toastr.success("Registration successful! Please login.");
         if (successCallback) successCallback();
         window.location.hash = "#login";
       } else {
         const errorMsg = result.error || result.responseJSON?.error || "Registration failed: Invalid response";
         toastr.error(errorMsg);
       }
     },
     error: function (XMLHttpRequest, textStatus, errorThrown) {
       $.unblockUI();
       const errorMsg = XMLHttpRequest.responseJSON?.error 
         || XMLHttpRequest.responseJSON?.message
         || XMLHttpRequest.responseText 
         || 'An error occurred';
       toastr.error(errorMsg);
     },
   });
 },
 logout: function () {
   localStorage.clear();
   window.location.hash = "#login";
 },
        getCurrentUser: function() {
   const token = localStorage.getItem("user_token");
   if (!token) return null;
   const decoded = Utils.parseJwt(token);
   return decoded ? decoded.user : null;
        },
        isLoggedIn: function() {
   return this.getCurrentUser() !== null;
        },
        isAdmin: function() {
   const user = this.getCurrentUser();
   return user && user.role === Constants.ADMIN_ROLE;
        },
        hasRole: function(role) {
   const user = this.getCurrentUser();
   return user && user.role === role;
        },
        requireLogin: function() {
            if (!this.isLoggedIn()) {
     toastr.warning("Please log in to access this page");
     window.location.hash = "#login";
                return false;
            }
            return true;
        },
        requireAdmin: function() {
            if (!this.isLoggedIn()) {
     toastr.warning("Please log in to access this page");
     window.location.hash = "#login";
                return false;
            }
            if (!this.isAdmin()) {
     toastr.error("Access denied. Admin privileges required.");
     window.location.hash = "#home";
                return false;
            }
            return true;
        },
 initProfile: function() {
   $("#profile-form").validate({
     rules: {
       full_name: {
         required: true,
         minlength: 2,
         maxlength: 100
       },
       email: {
         required: true,
         email: true
       },
       phone: {
         required: true,
         pattern: /^[0-9+\s\-()]+$/,
         minlength: 10,
         maxlength: 20
       },
       address: {
         required: true,
         minlength: 5,
         maxlength: 200
       },
       city: {
         required: true,
         minlength: 2,
         maxlength: 100
       }
     },
     messages: {
       full_name: {
         required: "Please enter your full name",
         minlength: "Name must be at least 2 characters",
         maxlength: "Name cannot exceed 100 characters"
       },
       email: {
         required: "Please enter your email address",
         email: "Please enter a valid email address"
       },
       phone: {
         required: "Please enter your phone number",
         pattern: "Please enter a valid phone number",
         minlength: "Phone number must be at least 10 digits",
         maxlength: "Phone number cannot exceed 20 characters"
       },
       address: {
         required: "Please enter your address",
         minlength: "Address must be at least 5 characters",
         maxlength: "Address cannot exceed 200 characters"
       },
       city: {
         required: "Please enter your city",
         minlength: "City must be at least 2 characters",
         maxlength: "City cannot exceed 100 characters"
       }
     },
     submitHandler: function (form) {
       var entity = Object.fromEntries(new FormData(form).entries());
       const user = UserService.getCurrentUser();
       if (user && user.user_id) {
         UserService.updateProfile(user.user_id, entity);
         form.reset();
       } else {
         toastr.error("User not found. Please log in again.");
       }
     },
   });
 },
 updateProfile: function(userId, updates) {
   $.blockUI({ message: '<h3>Processing...</h3>' });
   RestClient.put('users/' + userId, updates, function(response) {
     $.unblockUI();
     if (response.success) {
       toastr.success("Profile updated successfully!");
       const token = localStorage.getItem("user_token");
       if (token) {
         const decoded = Utils.parseJwt(token);
         if (decoded && decoded.user) {
           Object.assign(decoded.user, updates);
           const newToken = btoa(JSON.stringify(decoded));
           localStorage.setItem("user_token", newToken);
         }
       }
     } else {
       toastr.error(response.error || "Failed to update profile");
     }
   }, function(xhr) {
     $.unblockUI();
     toastr.error(xhr?.responseJSON?.error || xhr?.responseJSON?.message || "Failed to update profile");
   });
 },
 generateMenuItems: function(){
   const token = localStorage.getItem("user_token");
   if (!token) {
     $('#nav-login').show();
     $('#nav-logout').hide();
     $('#nav-profile').hide();
     $('#nav-requests').hide();
     $('#nav-admin').hide();
     return;
   }
   
   const decoded = Utils.parseJwt(token);
   const user = decoded ? decoded.user : null;

   if (user && user.role){
     $('#nav-login').hide();
     $('#nav-logout').show();
     $('#nav-profile').show();
     $('#nav-requests').show();
     
     if (user.role === Constants.ADMIN_ROLE) {
       $('#nav-admin').show();
     } else {
       $('#nav-admin').hide();
     }
   } else {
     $('#nav-login').show();
     $('#nav-logout').hide();
     $('#nav-profile').hide();
     $('#nav-requests').hide();
     $('#nav-admin').hide();
   }
 }
};

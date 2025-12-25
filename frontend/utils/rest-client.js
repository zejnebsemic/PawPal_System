let RestClient = {
   _isPublicEndpoint: function(url) {
     const publicEndpoints = [
       "auth/login",
       "auth/register",
       "pets",
       "shelters"
     ];
     return publicEndpoints.some(endpoint => url.startsWith(endpoint));
   },
   get: function (url, callback, error_callback) {
     $.ajax({
       url: Constants.PROJECT_BASE_URL + url,
       type: "GET",
       dataType: "json",
       beforeSend: function (xhr) {
         const isPublic = RestClient._isPublicEndpoint(url);
         const token = localStorage.getItem("user_token");
         
         if (!isPublic && token) {
           xhr.setRequestHeader("Authorization", "Bearer " + token);
           console.log("RestClient.get: Setting Authorization header for protected endpoint:", url);
         } else if (isPublic) {
           console.log("RestClient.get: Public endpoint, skipping Authorization header:", url);
         } else {
           console.log("RestClient.get: No token found, not setting Authorization header");
         }
       },
       success: function (response) {
         if (callback) callback(response);
       },
       error: function (jqXHR, textStatus, errorThrown) {
         console.error("GET request failed:", url, jqXHR.status, jqXHR.responseText);
         const errorMsg = jqXHR.responseJSON?.error 
           || jqXHR.responseJSON?.message
           || jqXHR.responseText 
           || 'An error occurred';
         
         if (error_callback) {
           error_callback(jqXHR);
         } else {
           const isPublic = RestClient._isPublicEndpoint(url);
           if (!isPublic || jqXHR.status !== 401) {
             toastr.error(errorMsg);
           }
         }
       },
     });
   },
   request: function (url, method, data, callback, error_callback) {
     $.ajax({
       url: Constants.PROJECT_BASE_URL + url,
       type: method,
       beforeSend: function (xhr) {
         const isPublic = RestClient._isPublicEndpoint(url);
         const token = localStorage.getItem("user_token");
         
         if (!isPublic && token) {
           xhr.setRequestHeader("Authorization", "Bearer " + token);
           console.log("RestClient.request: Setting Authorization header for protected endpoint:", method, url);
           console.log("RestClient.request: Token length:", token.length);
         } else if (isPublic) {
           console.log("RestClient.request: Public endpoint, skipping Authorization header:", method, url);
         } else {
           console.log("RestClient.request: No token found, not setting Authorization header");
         }
       },
       contentType: "application/json",
       dataType: "json",
       data: data ? JSON.stringify(data) : null,
     })
       .done(function (response, status, jqXHR) {
         if (callback) callback(response);
       })
       .fail(function (jqXHR, textStatus, errorThrown) {
         const errorMsg = jqXHR.responseJSON?.error 
           || jqXHR.responseJSON?.message
           || jqXHR.responseText 
           || 'An error occurred';
         
         if (error_callback) {
           error_callback(jqXHR);
         } else {
           toastr.error(errorMsg);
         }
       });
   },
  post: function (url, data, callback, error_callback) {

    if (url === "adoption-requests" || url.includes("adoption-requests")) {
      const token = localStorage.getItem("user_token");
      const isLoggedIn = UserService && UserService.isLoggedIn && UserService.isLoggedIn();
      
      console.log("RestClient.post: adoption-requests detected, token:", token ? "exists" : "missing", "isLoggedIn:", isLoggedIn);
      
      if (!token || !isLoggedIn) {
        console.log("RestClient.post: BLOCKED - No authentication for adoption-requests");
        if (error_callback) {
          error_callback({ 
            status: 401, 
            responseJSON: { error: "Authentication required" },
            responseText: "Authentication required"
          });
        }
        return; 
      }
    }
    
    console.log("RestClient.post: Proceeding with POST to", url);
    RestClient.request(url, "POST", data, callback, error_callback);
  },
   delete: function (url, data, callback, error_callback) {
     RestClient.request(url, "DELETE", data, callback, error_callback);
   },
   patch: function (url, data, callback, error_callback) {
     RestClient.request(url, "PATCH", data, callback, error_callback);
   },
   put: function (url, data, callback, error_callback) {
     RestClient.request(url, "PUT", data, callback, error_callback);
   },
 };


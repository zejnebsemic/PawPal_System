let RequestService = {
   getAllRequests: function(callback, error_callback) {
     RestClient.get("adoption-requests", function(response) {
       if (response.success && response.data) {
         if (callback) callback(response.data);
       } else {
         if (callback) callback(response);
       }
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   getRequestById: function(id, callback, error_callback) {
     RestClient.get("adoption-requests/" + id, function(response) {
       if (response.success && response.data) {
         if (callback) callback(response.data);
       } else {
         if (callback) callback(response);
       }
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   getRequestsByUser: function(userId, callback, error_callback) {
     RestClient.get("adoption-requests/user/" + userId, function(response) {
       if (response.success && response.data) {
         if (callback) callback(response.data);
       } else {
         if (callback) callback(response);
       }
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   getPendingRequests: function(callback, error_callback) {
     RestClient.get("adoption-requests/pending", function(response) {
       if (response.success && response.data) {
         if (callback) callback(response.data);
       } else {
         if (callback) callback(response);
       }
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
  createRequest: function(request, callback, error_callback) {
    const token = localStorage.getItem("user_token");
    if (!token) {
      if (error_callback) {
        error_callback({ status: 401, responseJSON: { error: "Authentication required" } });
      }
      return; 
    }

    if (!UserService || !UserService.isLoggedIn || !UserService.isLoggedIn()) {
      if (error_callback) {
        error_callback({ status: 401, responseJSON: { error: "Authentication required" } });
      }
      return; 
    }

    $.blockUI({ message: '<h3>Processing...</h3>' });
    RestClient.post("adoption-requests", request, function(response) {
      $.unblockUI();
      toastr.success("Adoption request submitted successfully");
      if (callback) callback(response);
    }, function(xhr) {
      $.unblockUI();
      toastr.error(xhr?.responseJSON?.error || xhr?.responseJSON?.message || "Failed to submit adoption request");
      if (error_callback) error_callback(xhr);
    });
  },
   updateRequest: function(id, request, callback, error_callback) {
     RestClient.put("adoption-requests/" + id, request, function(response) {
       if (callback) callback(response);
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   deleteRequest: function(id, callback, error_callback) {
     if (confirm("Are you sure you want to delete this adoption request?")) {
       $.blockUI({ message: '<h3>Processing...</h3>' });
       RestClient.delete("adoption-requests/" + id, null, function(response) {
         $.unblockUI();
         toastr.success(response?.message || "Adoption request deleted successfully");
         if (callback) callback(response);
       }, function(xhr) {
         $.unblockUI();
         toastr.error(xhr?.responseJSON?.error || xhr?.responseJSON?.message || "Failed to delete adoption request");
         if (error_callback) error_callback(xhr);
       });
     }
   }
};

let RequestService = {
  getAllRequests: function (callback, error_callback) {
    RestClient.get("adoption-requests", function (response) {
      if (response && response.success) {
        callback && callback(response.data || []);
      } else {
        callback && callback([]);
      }
    }, function (xhr) {
      error_callback && error_callback(xhr);
    });
  },
  getRequestById: function (id, callback, error_callback) {
    RestClient.get("adoption-requests/" + id, function (response) {
      if (response && response.success) {
        callback && callback(response.data);
      } else {
        callback && callback(null);
      }
    }, function (xhr) {
      error_callback && error_callback(xhr);
    });
  },
  getMyRequests: function (callback, error_callback) {
    RestClient.get("adoption-requests/user", function (response) {
      if (response && response.success) {
        callback && callback(response.data || []);
      } else {
        callback && callback([]);
      }
    }, function (xhr) {
      error_callback && error_callback(xhr);
    });
  },
  getPendingRequests: function (callback, error_callback) {
    RestClient.get("adoption-requests/pending", function (response) {
      if (response && response.success) {
        callback && callback(response.data || []);
      } else {
        callback && callback([]);
      }
    }, function (xhr) {
      error_callback && error_callback(xhr);
    });
  },
  createRequest: function (request, callback, error_callback) {

    if (!UserService || !UserService.isLoggedIn || !UserService.isLoggedIn()) {
      error_callback && error_callback({
        status: 401,
        responseJSON: { error: "Authentication required" }
      });
      return;
    }

    $.blockUI({ message: '<h3>Processing...</h3>' });

    RestClient.post("adoption-requests", request, function (response) {
      $.unblockUI();
      toastr.success("Adoption request submitted successfully");
      callback && callback(response);
    }, function (xhr) {
      $.unblockUI();
      toastr.error(xhr?.responseJSON?.error || "Failed to submit adoption request");
      error_callback && error_callback(xhr);
    });
  },

  updateRequest: function (id, request, callback, error_callback) {
    RestClient.put("adoption-requests/" + id, request, function (response) {
      callback && callback(response);
    }, function (xhr) {
      error_callback && error_callback(xhr);
    });
  },

  deleteRequest: function (id, callback, error_callback) {
    $.blockUI({ message: '<h3>Processing...</h3>' });

    RestClient.delete("adoption-requests/" + id, null, function (response) {
      $.unblockUI();
      toastr.success(response?.message || "Adoption request deleted successfully");
      callback && callback(response);
    }, function (xhr) {
      $.unblockUI();
      toastr.error(xhr?.responseJSON?.error || "Failed to delete adoption request");
      error_callback && error_callback(xhr);
    });
  }
};

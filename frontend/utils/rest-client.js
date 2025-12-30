let RestClient = {
  _buildUrl: function (endpoint) {
    return Constants.PROJECT_BASE_URL + endpoint;
  },

 
  _isPublicEndpoint: function (url) {
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
      url: RestClient._buildUrl(url),
      type: "GET",
      dataType: "json",
      beforeSend: function (xhr) {
        const isPublic = RestClient._isPublicEndpoint(url);
        const token = localStorage.getItem("user_token");

        if (!isPublic && token) {
          xhr.setRequestHeader("Authorization", "Bearer " + token);
        }
      },
      success: function (response) {
        if (callback) callback(response);
      },
      error: function (jqXHR) {
        console.error("GET failed:", url, jqXHR.status, jqXHR.responseText);
        if (error_callback) {
          error_callback(jqXHR);
        } else {
          toastr.error(jqXHR.responseJSON?.error || "Request failed");
        }
      }
    });
  },

  
  request: function (url, method, data, callback, error_callback) {
    $.ajax({
      url: RestClient._buildUrl(url),
      type: method,
      contentType: "application/json",
      dataType: "json",
      data: data ? JSON.stringify(data) : null,
      beforeSend: function (xhr) {
        const isPublic = RestClient._isPublicEndpoint(url);
        const token = localStorage.getItem("user_token");

        if (!isPublic && token) {
          xhr.setRequestHeader("Authorization", "Bearer " + token);
        }
      }
    })
    .done(function (response) {
      if (callback) callback(response);
    })
    .fail(function (jqXHR) {
      console.error(method + " failed:", url, jqXHR.status, jqXHR.responseText);
      if (error_callback) {
        error_callback(jqXHR);
      } else {
        toastr.error(jqXHR.responseJSON?.error || "Request failed");
      }
    });
  },

  
  post: function (url, data, callback, error_callback) {

    
    if (url.startsWith("adoption-requests")) {
      const token = localStorage.getItem("user_token");
      if (!token) {
        toastr.warning("Please login first");
        return;
      }
    }

    RestClient.request(url, "POST", data, callback, error_callback);
  },

  
  put: function (url, data, callback, error_callback) {
    RestClient.request(url, "PUT", data, callback, error_callback);
  },

  
  patch: function (url, data, callback, error_callback) {
    RestClient.request(url, "PATCH", data, callback, error_callback);
  },

  
  delete: function (url, data, callback, error_callback) {
    RestClient.request(url, "DELETE", data, callback, error_callback);
  }
};

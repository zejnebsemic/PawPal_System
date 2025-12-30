let ShelterService = {
   getAllShelters: function(callback, error_callback) {
     RestClient.get("shelters", function(response) {
       if (response.success && response.data) {
         if (callback) callback(response.data);
       } else {
         if (callback) callback(response);
       }
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   getShelterById: function(id, callback, error_callback) {
     RestClient.get("shelters/" + id, function(response) {
       if (response.success && response.data) {
         if (callback) callback(response.data);
       } else {
         if (callback) callback(response);
       }
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   createShelter: function(shelter, callback, error_callback) {
     RestClient.post("shelters", shelter, function(response) {
       if (callback) callback(response);
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   updateShelter: function(id, shelter, callback, error_callback) {
     RestClient.put("shelters/" + id, shelter, function(response) {
       if (callback) callback(response);
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   deleteShelter: function(id, callback, error_callback) {
     RestClient.delete("shelters/" + id, null, function(response) {
       if (callback) callback(response);
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   }
};


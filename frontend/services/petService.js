let PetService = {
   getAllPets: function(callback, error_callback) {
     const currentHash = window.location.hash;
     if (currentHash === '#login' || currentHash === '#register') {
       console.log("PetService.getAllPets: BLOCKED - on auth page:", currentHash);
       return; 
     }
     
     RestClient.get("pets", function(response) {
       if (response.success && response.data) {
         if (callback) callback(response.data);
       } else {
         if (callback) callback(response);
       }
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   getPetById: function(id, callback, error_callback) {
     RestClient.get("pets/" + id, function(response) {
       if (response.success && response.data) {
         if (callback) callback(response.data);
       } else {
         if (callback) callback(response);
       }
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   createPet: function(pet, callback, error_callback) {
     RestClient.post("pets", pet, function(response) {
       if (callback) callback(response);
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   updatePet: function(id, pet, callback, error_callback) {
     RestClient.put("pets/" + id, pet, function(response) {
       if (callback) callback(response);
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   deletePet: function(id, callback, error_callback) {
     RestClient.delete("pets/" + id, null, function(response) {
       if (callback) callback(response);
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   filterPets: function(filters, allPets) {
     if (!allPets) return [];
     return allPets.filter(pet => {
       if (filters.type && pet.type && pet.type.toLowerCase() !== filters.type.toLowerCase()) return false;
       if (filters.size && pet.size && pet.size.toLowerCase() !== filters.size.toLowerCase()) return false;
       if (filters.search) {
         const search = filters.search.toLowerCase();
         return (pet.name && pet.name.toLowerCase().includes(search)) ||
                (pet.about && pet.about.toLowerCase().includes(search));
       }
       return true;
     });
   }
};

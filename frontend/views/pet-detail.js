function initPetDetailPage() {
  const petId = sessionStorage.getItem('currentPetId');
  
  if (!petId) {
    toastr.warning("No pet selected");
    window.location.hash = "#browse";
    return;
  }

  PetService.getPetById(petId, function(pet) {
    if (pet) {
      updatePetDetailPage(pet);
    } else {
      toastr.error("Pet not found");
      window.location.hash = "#browse";
    }
  }, function(xhr) {
    toastr.error("Failed to load pet details");
    window.location.hash = "#browse";
  });

  
  $('#adopt-btn').off('click');
  
  
  $('#adopt-btn').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    console.log("pet-detail: adopt-btn clicked, isLoggedIn:", UserService && UserService.isLoggedIn ? UserService.isLoggedIn() : "UserService not available");
    
    
    if (!UserService || !UserService.isLoggedIn || !UserService.isLoggedIn()) {
      toastr.warning("Please log in to submit an adoption request");
      window.location.hash = "#login";
      return false; 
    }
    
    
    requestAdoption(petId);
    return false;
  });
}

function updatePetDetailPage(pet) {
  const container = $('#app-content');
  
  
  container.find('.card-img-top').first().attr('src', Utils.normalizeImageUrl(pet.image_url, 'max.jpeg')).attr('alt', pet.name);
  
  container.find('.display-5').text(pet.name);
  container.find('.badge.bg-primary').text(pet.type ? pet.type.charAt(0).toUpperCase() + pet.type.slice(1) : 'Pet');
  
  const statusClass = pet.availability === 'available' ? 'bg-success' : 
                     pet.availability === 'pending' ? 'bg-warning' : 'bg-secondary';
  container.find('.badge.bg-success, .badge.bg-warning').removeClass('bg-success bg-warning')
    .addClass(statusClass)
    .text(pet.availability ? pet.availability.charAt(0).toUpperCase() + pet.availability.slice(1) : 'Available');
  
  const stats = container.find('.stat-icon').parent().parent();
  stats.eq(0).find('strong').text(pet.age ? pet.age + ' years' : 'N/A');
  stats.eq(1).find('strong').text(pet.size ? pet.size.charAt(0).toUpperCase() + pet.size.slice(1) : 'N/A');
  stats.eq(2).find('strong').text(pet.gender ? pet.gender.charAt(0).toUpperCase() + pet.gender.slice(1) : 'N/A');
  

  if (pet.about) {
    const aboutText = pet.about.split('.');
    container.find('h4:contains("About")').next('p').text(aboutText[0] + '.');
    if (aboutText.length > 1) {
      container.find('h4:contains("About")').next('p').next('p').text(aboutText.slice(1).join('.'));
    }
  }
}

function requestAdoption(petId) {
  console.log("requestAdoption called with petId:", petId, "isLoggedIn:", UserService && UserService.isLoggedIn ? UserService.isLoggedIn() : "UserService not available");
  
  if (!UserService || !UserService.isLoggedIn || !UserService.isLoggedIn()) {
    console.log("requestAdoption: User not logged in, returning early");
    toastr.warning("Please log in to submit an adoption request");
    window.location.hash = "#login";
    return; 
  }

  const user = UserService.getCurrentUser();
  if (!user || !user.user_id) {
    console.log("requestAdoption: User object invalid, returning early");
    toastr.warning("Please log in to submit an adoption request");
    window.location.hash = "#login";
    return; 
  }

  console.log("requestAdoption: User authenticated, proceeding with request");

  const request = {
    pet_id: petId,
    user_id: user.user_id,
    status: 'pending'
  };

  RequestService.createRequest(request, function(response) {
    if (response.success) {
      toastr.success("Adoption request submitted successfully!");
      window.location.hash = "#requests";
    } else {
      toastr.error(response.error || "Failed to submit request");
    }
  }, function(xhr) {
    if (xhr.status === 401) {
      toastr.warning("Please log in to submit an adoption request");
      window.location.hash = "#login";
    } else {
      toastr.error("Failed to submit adoption request");
    }
  });
}


let allPets = [];

function initBrowsePage() {
  
  const currentHash = window.location.hash;
  if (currentHash === '#login' || currentHash === '#register') {
    console.log("initBrowsePage: BLOCKED - on auth page:", currentHash);
    return; 
  }
  
 
  PetService.getAllPets(function(pets) {
    if (Array.isArray(pets)) {
      allPets = pets;
      renderPets(pets);
      updatePetCount(pets.length);
    } else if (pets && pets.data) {
      allPets = pets.data;
      renderPets(pets.data);
      updatePetCount(pets.data.length);
    }
  }, function(xhr) {
    
    const errorMsg = xhr.responseJSON?.error || xhr.responseText || "Failed to load pets";
    console.error("Failed to load pets:", errorMsg);
    toastr.error(errorMsg);
  });

  
  $('.filter-input').on('change', function() {
    filterPets();
  });

  $('#search').on('input', function() {
    filterPets();
  });

  
  $(document).off('click', '.adopt-btn');
  
  
  $(document).on('click', '.adopt-btn', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const petId = $(this).data('pet-id');
    
    
    if (!UserService || !UserService.isLoggedIn || !UserService.isLoggedIn()) {
      toastr.warning("Please log in to submit an adoption request");
      window.location.hash = "#login";
      return false; 
    }
    
    
    requestAdoption(petId);
    return false;
  });

 
  $(document).on('click', '.pet-card', function(e) {
    if (!$(e.target).closest('.adopt-btn').length) {
      const petId = $(this).data('pet-id');
      sessionStorage.setItem('currentPetId', petId);
      window.location.hash = "#pet-detail";
    }
  });
}

function renderPets(pets) {
  const container = $('#pets-grid');
  container.empty();
  
  if (!pets || pets.length === 0) {
    container.html('<div class="col-12 text-center"><p class="text-muted">No pets available</p></div>');
    return;
  }

  pets.forEach(pet => {
    const statusClass = pet.availability === 'available' ? 'bg-success' : 
                       pet.availability === 'pending' ? 'bg-warning' : 'bg-secondary';
    const statusText = pet.availability ? pet.availability.charAt(0).toUpperCase() + pet.availability.slice(1) : 'Available';
    const typeBadge = pet.type === 'dog' ? 'bg-primary' : pet.type === 'cat' ? 'bg-secondary' : 'bg-info';
    
    const petCard = `
      <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="card pet-card" data-pet-id="${pet.pet_id}">
          <span class="badge ${statusClass} pet-status">${statusText}</span>
          <img src="${Utils.normalizeImageUrl(pet.image_url, 'max.jpeg')}" class="card-img-top" alt="${pet.name}">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h5 class="card-title mb-0">${pet.name}</h5>
              <span class="badge ${typeBadge}">${pet.type ? pet.type.charAt(0).toUpperCase() + pet.type.slice(1) : 'Pet'}</span>
            </div>
            <p class="text-muted small mb-2">
              <i class="bi bi-geo-alt me-1"></i>Shelter
            </p>
            <div class="mb-2">
              <span class="badge bg-light text-dark me-1">${pet.age || 'N/A'} years</span>
              <span class="badge bg-light text-dark">${pet.size ? pet.size.charAt(0).toUpperCase() + pet.size.slice(1) : 'N/A'}</span>
            </div>
            <p class="card-text small">${pet.about || 'Loving pet looking for a home'}</p>
            ${pet.availability === 'available' ? 
              `<button class="btn btn-primary w-100 adopt-btn" data-pet-id="${pet.pet_id}">
                <i class="bi bi-heart me-1"></i>Adopt Me
              </button>` :
              `<button class="btn btn-secondary w-100" disabled>
                <i class="bi bi-clock me-1"></i>Adoption Pending
              </button>`
            }
          </div>
        </div>
      </div>
    `;
    container.append(petCard);
  });
}

function filterPets() {
  const filters = {
    type: $('#pet-type').val(),
    size: $('#size').val(),
    search: $('#search').val()
  };
  
  const filtered = PetService.filterPets(filters, allPets);
  renderPets(filtered);
  updatePetCount(filtered.length);
}

function updatePetCount(count) {
  $('.text-muted strong').first().text(count);
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


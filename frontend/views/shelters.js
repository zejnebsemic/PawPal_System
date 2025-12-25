function initSheltersPage() {
  ShelterService.getAllShelters(function(shelters) {
    if (!Array.isArray(shelters)) {
      shelters = shelters.data || [];
    }
    renderShelters(shelters);
  }, function(xhr) {
    toastr.error("Failed to load shelters");
  });
}

function renderShelters(shelters) {
  const container = $('#shelters-container');
  if (!container.length) return;
  
  container.empty();
  
  shelters.forEach(shelter => {
    const shelterCard = `
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">${shelter.name || 'Shelter'}</h5>
            <p class="card-text">
              <i class="bi bi-geo-alt me-2"></i>${shelter.address || 'Address not available'}<br>
              <i class="bi bi-telephone me-2"></i>${shelter.phone || 'Phone not available'}<br>
              <i class="bi bi-clock me-2"></i>${shelter.working_hours || 'Hours not available'}
            </p>
          </div>
        </div>
      </div>
    `;
    container.append(shelterCard);
  });
}


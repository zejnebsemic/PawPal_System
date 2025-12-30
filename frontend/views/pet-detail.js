function initPetDetailPage() {
    const petId = sessionStorage.getItem('currentPetId');

    if (!petId) {
        toastr.warning("No pet selected");
        window.location.hash = "#browse";
        return;
    }

    PetService.getPetById(
        petId,
        function (pet) {
            if (pet) {
                updatePetDetailPage(pet);
            } else {
                toastr.error("Pet not found");
                window.location.hash = "#browse";
            }
        },
        function () {
            toastr.error("Failed to load pet details");
            window.location.hash = "#browse";
        }
    );

    $('#adopt-btn')
        .off('click')
        .on('click', function (e) {
            e.preventDefault();

            if (!UserService || !UserService.isLoggedIn || !UserService.isLoggedIn()) {
                toastr.warning("Please log in to submit an adoption request");
                window.location.hash = "#login";
                return;
            }

            requestAdoption(petId);
        });
}

function updatePetDetailPage(pet) {
    const container = $('#app-content');

    container.find('.card-img-top')
        .first()
        .attr('src', Utils.normalizeImageUrl(pet.image_url, 'max.jpeg'))
        .attr('alt', pet.name);

    container.find('.display-5').text(pet.name);
    container.find('.badge.bg-primary').text(
        pet.type ? pet.type.charAt(0).toUpperCase() + pet.type.slice(1) : 'Pet'
    );

    const statusClass =
        pet.availability === 'available' ? 'bg-success' :
        pet.availability === 'pending' ? 'bg-warning' :
        'bg-secondary';

    container.find('.badge.bg-success, .badge.bg-warning, .badge.bg-secondary')
        .removeClass('bg-success bg-warning bg-secondary')
        .addClass(statusClass)
        .text(
            pet.availability
                ? pet.availability.charAt(0).toUpperCase() + pet.availability.slice(1)
                : 'Available'
        );
}

function requestAdoption(petId) {
    if (!UserService || !UserService.isLoggedIn || !UserService.isLoggedIn()) {
        toastr.warning("Please log in to submit an adoption request");
        window.location.hash = "#login";
        return;
    }
    const request = {
        pet_id: petId
    };

    RequestService.createRequest(
        request,
        function (response) {
            if (response.success) {
                toastr.success("Adoption request submitted successfully!");
                window.location.hash = "#requests";
            } else {
                toastr.error(response.error || "Failed to submit request");
            }
        },
        function (xhr) {
            if (xhr.status === 401) {
                toastr.warning("Please log in to submit an adoption request");
                window.location.hash = "#login";
            } else {
                toastr.error("Failed to submit adoption request");
            }
        }
    );
}

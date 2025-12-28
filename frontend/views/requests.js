function initRequestsPage() {
    if (!UserService.requireLogin()) return;

    loadUserRequests();

    $(document)
        .off('click', '.cancel-request-btn')
        .on('click', '.cancel-request-btn', function () {
            const requestId = $(this).data('request-id');

            if (!confirm('Are you sure you want to cancel this adoption request?')) return;

            RequestService.deleteRequest(
                requestId,
                function () {
                    toastr.success("Request cancelled successfully");
                    loadUserRequests();
                },
                function () {
                    toastr.error("Failed to cancel request");
                }
            );
        });
}

function loadUserRequests() {
    const user = UserService.getCurrentUser();
    if (!user) return;

    RequestService.getRequestsByUser(
        user.user_id,
        function (response) {
            let requests = Array.isArray(response) ? response : response.data || [];

            updateStats(requests);

            if (requests.length === 0) {
                $('#empty-state').removeClass('d-none');
                $('#requests-container').html('');
                return;
            }

            $('#empty-state').addClass('d-none');
            renderRequests(requests);
        },
        function () {
            toastr.error("Failed to load adoption requests");
        }
    );
}

function updateStats(requests) {
    $('.stat-card h3').eq(0).text(requests.length);
    $('.stat-card h3').eq(1).text(requests.filter(r => r.status === 'pending').length);
    $('.stat-card h3').eq(2).text(requests.filter(r => r.status === 'approved').length);
    $('.stat-card h3').eq(3).text(requests.filter(r => r.status === 'rejected').length);
}

function renderRequests(requests) {
    let html = '';

    requests.forEach(r => {
        html += `
        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-center">

                    <div class="col-md-8">
                        <h5 class="fw-bold mb-1">${r.pet_name || 'Pet'}</h5>
                        <p class="text-muted mb-2">
                            <i class="bi bi-geo-alt"></i> ${r.shelter_name || 'Shelter'}
                        </p>

                        <span class="badge bg-${statusColor(r.status)} me-2">
                            ${capitalize(r.status)}
                        </span>

                        <small class="text-muted">
                            Applied on: ${formatDate(r.created_at)}
                        </small>
                    </div>

                    <div class="col-md-4 text-end">
                        ${r.status === 'pending' ? `
                            <button class="btn btn-sm btn-outline-danger cancel-request-btn"
                                    data-request-id="${r.request_id}">
                                Cancel Request
                            </button>
                        ` : ''}
                    </div>

                </div>
            </div>
        </div>`;
    });

    $('#requests-container').html(html);
}

function statusColor(status) {
    if (status === 'approved') return 'success';
    if (status === 'rejected') return 'danger';
    return 'warning';
}

function capitalize(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
}

function formatDate(date) {
    return new Date(date).toLocaleDateString();
}

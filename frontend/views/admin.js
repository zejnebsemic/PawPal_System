function initAdminPage() {
    if (!UserService.requireAdmin()) return;

    $(document).off('click', '.approve-btn');
    $(document).off('click', '.reject-btn');

    
    loadAdminRequests();

    
    $(document).on('click', '.approve-btn', function () {
        const requestId = $(this).data('request-id');

        if (!confirm('Approve this adoption request?')) return;

        RequestService.updateRequest(requestId, { status: 'approved' }, function (response) {
            if (response && response.success) {
                toastr.success("Request approved successfully!");
                loadAdminRequests();
            } else {
                toastr.error(response?.error || "Failed to approve request");
            }
        }, function () {
            toastr.error("Failed to approve request");
        });
    });

  
    $(document).on('click', '.reject-btn', function () {
        const requestId = $(this).data('request-id');

        if (!confirm('Reject this adoption request?')) return;

        RequestService.updateRequest(requestId, { status: 'rejected' }, function (response) {
            if (response && response.success) {
                toastr.success("Request rejected successfully!");
                loadAdminRequests();
            } else {
                toastr.error(response?.error || "Failed to reject request");
            }
        }, function () {
            toastr.error("Failed to reject request");
        });
    });
}

function loadAdminRequests() {
    
    RequestService.getAllRequests(function (requests) {
        if (!Array.isArray(requests)) {
            requests = requests?.data || [];
        }

     
        const pendingCount = requests.filter(r => (r.status || '').toLowerCase() === 'pending').length;
        $('#admin-pending-count').text(pendingCount);

      
        renderAdminRequests(requests);

    }, function () {
        toastr.error("Failed to load adoption requests");
    });

 
    if (typeof PetService !== "undefined" && PetService.getAllPets) {
        PetService.getAllPets(function (pets) {
            const petsArray = Array.isArray(pets) ? pets : (pets?.data || []);
            $('#admin-total-pets').text(petsArray.length);
        }, function () {
            $('#admin-total-pets').text('0');
        });
    } else {
        $('#admin-total-pets').text('0');
    }
}

function renderAdminRequests(requests) {
    const tbody = $('#admin-requests-table tbody');
    tbody.empty();

    if (!requests.length) {
        tbody.append(`
            <tr>
                <td colspan="7" class="text-center text-muted py-4">
                    No adoption requests found.
                </td>
            </tr>
        `);
        return;
    }

    requests.forEach(function (r) {
        const status = (r.status || 'pending').toLowerCase();
        const badgeClass = status === 'approved' ? 'bg-success'
            : status === 'rejected' ? 'bg-danger'
            : 'bg-warning';

        const statusText = capitalize(status);

       
        const userText = r.user_email
            ? `${escapeHtml(r.user_email)}`
            : `User ID: ${escapeHtml(String(r.user_id ?? 'N/A'))}`;

        const petText = r.pet_name
            ? escapeHtml(r.pet_name)
            : `Pet ID: ${escapeHtml(String(r.pet_id ?? 'N/A'))}`;

        const shelterText = r.shelter_name
            ? escapeHtml(r.shelter_name)
            : (r.shelter_id ? `Shelter ID: ${escapeHtml(String(r.shelter_id))}` : 'N/A');

        const dateText = r.created_at ? formatDate(r.created_at) : 'N/A';

        let actionsHtml = '';
        if (status === 'pending') {
            actionsHtml = `
                <button class="btn btn-success btn-sm approve-btn" data-request-id="${r.request_id}">
                    <i class="bi bi-check-lg"></i>
                </button>
                <button class="btn btn-danger btn-sm reject-btn" data-request-id="${r.request_id}">
                    <i class="bi bi-x-lg"></i>
                </button>
            `;
        } else {
            actionsHtml = `
                <button class="btn btn-secondary btn-sm" disabled title="Already processed">
                    <i class="bi bi-lock"></i>
                </button>
            `;
        }

        const row = `
            <tr>
                <td>#${escapeHtml(String(r.request_id ?? ''))}</td>
                <td>${userText}</td>
                <td>${petText}</td>
                <td>${shelterText}</td>
                <td>${escapeHtml(dateText)}</td>
                <td><span class="badge ${badgeClass}">${statusText}</span></td>
                <td>
                    <div class="btn-group btn-group-sm">
                        ${actionsHtml}
                    </div>
                </td>
            </tr>
        `;

        tbody.append(row);
    });
}



function capitalize(text) {
    if (!text) return '';
    return text.charAt(0).toUpperCase() + text.slice(1);
}

function formatDate(dateStr) {
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return dateStr;
    return d.toLocaleDateString();
}

function escapeHtml(str) {
    return String(str)
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}

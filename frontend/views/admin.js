function initAdminPage() {
  if (!UserService.requireAdmin()) return;
  
  loadAdminRequests();
  
  $(document).on('click', '.approve-btn', function() {
    const requestId = $(this).data('request-id');
    if (confirm('Approve this adoption request?')) {
      RequestService.updateRequest(requestId, { status: 'approved' }, function(response) {
        if (response.success) {
          toastr.success("Request approved successfully!");
          loadAdminRequests();
        } else {
          toastr.error(response.error || "Failed to approve request");
        }
      }, function(xhr) {
        toastr.error("Failed to approve request");
      });
    }
  });

  $(document).on('click', '.reject-btn', function() {
    const requestId = $(this).data('request-id');
    if (confirm('Reject this adoption request?')) {
      RequestService.updateRequest(requestId, { status: 'rejected' }, function(response) {
        if (response.success) {
          toastr.success("Request rejected");
          loadAdminRequests();
        } else {
          toastr.error(response.error || "Failed to reject request");
        }
      }, function(xhr) {
        toastr.error("Failed to reject request");
      });
    }
  });
}

function loadAdminRequests() {
  RequestService.getAllRequests(function(requests) {
    if (!Array.isArray(requests)) {
      requests = requests.data || [];
    }
    
    const stats = {
      pending: requests.filter(r => r.status === 'pending').length,
      total: requests.length
    };

    $('#admin-pending-count').text(stats.pending);
    
    PetService.getAllPets(function(pets) {
      const petsArray = Array.isArray(pets) ? pets : (pets.data || []);
      $('#admin-total-pets').text(petsArray.length);
    });

    renderAdminRequests(requests);
  }, function(xhr) {
    toastr.error("Failed to load requests");
  });
}

function renderAdminRequests(requests) {
  const tbody = $('#admin-requests-table tbody');
  tbody.empty();

  requests.forEach(function(request) {
    const statusBadge = request.status === 'pending' ? 'bg-warning' :
                        request.status === 'approved' ? 'bg-success' : 'bg-danger';
    const statusText = request.status ? request.status.charAt(0).toUpperCase() + request.status.slice(1) : 'Pending';

    const actionButtons = request.status === 'pending' ?
      `<button class="btn btn-success btn-sm approve-btn" data-request-id="${request.request_id}">
        <i class="bi bi-check-lg"></i>
      </button>
      <button class="btn btn-danger btn-sm reject-btn" data-request-id="${request.request_id}">
        <i class="bi bi-x-lg"></i>
      </button>` :
      `<button class="btn btn-info btn-sm">
        <i class="bi bi-eye"></i>
      </button>`;

    const row = `
      <tr>
        <td>#${request.request_id}</td>
        <td>User ID: ${request.user_id}</td>
        <td>Pet ID: ${request.pet_id}</td>
        <td>${request.created_at || 'N/A'}</td>
        <td><span class="badge ${statusBadge}">${statusText}</span></td>
        <td>
          <div class="btn-group btn-group-sm">
            ${actionButtons}
          </div>
        </td>
      </tr>
    `;
    tbody.append(row);
  });
}


function initRequestsPage() {
  if (!UserService.requireLogin()) return;
  
  const user = UserService.getCurrentUser();
  if (!user) return;

  loadUserRequests(user.user_id);
  
  $(document).on('click', '.cancel-request-btn', function() {
    const requestId = $(this).data('request-id');
    if (confirm('Are you sure you want to cancel this adoption request?')) {
      RequestService.deleteRequest(requestId, function(response) {
        if (response.success) {
          toastr.success("Request cancelled successfully");
          loadUserRequests(user.user_id);
        } else {
          toastr.error(response.error || "Failed to cancel request");
        }
      }, function(xhr) {
        toastr.error("Failed to cancel request");
      });
    }
  });
}

function loadUserRequests(userId) {
  RequestService.getRequestsByUser(userId, function(requests) {
    if (!Array.isArray(requests)) {
      requests = requests.data || [];
    }
    
    const userStats = {
      total: requests.length,
      pending: requests.filter(r => r.status === 'pending').length,
      approved: requests.filter(r => r.status === 'approved').length,
      rejected: requests.filter(r => r.status === 'rejected').length
    };

    $('.stat-card h3').eq(0).text(userStats.total);
    $('.stat-card h3').eq(1).text(userStats.pending);
    $('.stat-card h3').eq(2).text(userStats.approved);
    $('.stat-card h3').eq(3).text(userStats.rejected);

    if (requests.length === 0) {
      $('#empty-state').removeClass('d-none');
      $('.tab-content .card').remove();
    } else {
      $('#empty-state').addClass('d-none');
      renderRequests(requests);
    }
  }, function(xhr) {
    toastr.error("Failed to load requests");
  });
}

function renderRequests(requests) {
  
}


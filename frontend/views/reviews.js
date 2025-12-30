function initReviewsPage() {
  $('.star-rating i').on('click', function() {
    const rating = $(this).data('rating') || parseInt($(this).index()) + 1;
    setRating(rating);
  });

  ReviewService.init();

  loadReviews();
}

function setRating(rating) {
  $('.star-rating i').each(function(index) {
    if (index < rating) {
      $(this).removeClass('bi-star').addClass('bi-star-fill');
    } else {
      $(this).removeClass('bi-star-fill').addClass('bi-star');
    }
  });
  $('#rating-value').val(rating);
}

function loadReviews() {
 
  if (!UserService || !UserService.isLoggedIn || !UserService.isLoggedIn()) {
    return;
  }

  ReviewService.getAllReviews(function(reviews) {
    if (!Array.isArray(reviews)) {
      reviews = reviews.data || [];
    }
    
  }, function(xhr) {
    if (xhr.status !== 401) {
      toastr.error("Failed to load reviews");
    }
  });
}


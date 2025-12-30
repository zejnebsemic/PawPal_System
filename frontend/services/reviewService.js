let ReviewService = {
   init: function () {
     $("#review-form").validate({
       rules: {
         name: {
           required: true,
           minlength: 2,
           maxlength: 100
         },
         pet_adopted: {
           required: true,
           minlength: 1,
           maxlength: 100
         },
         rating: {
           required: true,
           min: 1,
           max: 5
         },
         review: {
           required: true,
           minlength: 5,
           maxlength: 500
         }
       },
       messages: {
         name: {
           required: "Please enter your name",
           minlength: "Name must be at least 2 characters",
           maxlength: "Name cannot exceed 100 characters"
         },
         pet_adopted: {
           required: "Please enter the pet name",
           minlength: "Pet name is required",
           maxlength: "Pet name cannot exceed 100 characters"
         },
         rating: {
           required: "Please select a rating (1-5)",
           min: "Rating must be at least 1",
           max: "Rating cannot exceed 5"
         },
         review: {
           required: "Please write your review",
           minlength: "Review must be at least 5 characters",
           maxlength: "Review cannot exceed 500 character"
         }
       },
       submitHandler: function (form) {
         let payload = Object.fromEntries(new FormData(form).entries());
         payload.rating = parseInt($("#rating-value").val()) || 0;
         const user = UserService.getCurrentUser();
         if (user && user.user_id) {
           const reviewData = {
             user_id: user.user_id,
             shelter_id: 2,
             rating: payload.rating,
             comment: payload.review
           };
           ReviewService.createReview(reviewData);
           form.reset();
           $('.star-rating i').removeClass('bi-star-fill').addClass('bi-star');
           $("#rating-value").val(0);
         } else {
           toastr.warning("Please log in to submit a review");
           window.location.hash = "#login";
         }
       },
     });
   },
   getAllReviews: function(callback, error_callback) {
     RestClient.get("reviews", function(response) {
       if (response.success && response.data) {
         if (callback) callback(response.data);
       } else {
         if (callback) callback(response);
       }
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   getReviewById: function(id, callback, error_callback) {
     RestClient.get("reviews/" + id, function(response) {
       if (response.success && response.data) {
         if (callback) callback(response.data);
       } else {
         if (callback) callback(response);
       }
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   getReviewsByShelter: function(shelterId, callback, error_callback) {
     RestClient.get("reviews/shelter/" + shelterId, function(response) {
       if (response.success && response.data) {
         if (callback) callback(response.data);
       } else {
         if (callback) callback(response);
       }
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   getAverageRating: function(shelterId, callback, error_callback) {
     RestClient.get("reviews/shelter/" + shelterId + "/average", function(response) {
       if (response.success && response.data) {
         if (callback) callback(response.data);
       } else {
         if (callback) callback(response);
       }
     }, function(xhr) {
       if (error_callback) error_callback(xhr);
     });
   },
   createReview: function(review) {
     $.blockUI({ message: '<h3>Processing...</h3>' });
     RestClient.post("reviews", review, function(res){
       $.unblockUI();
       toastr.success("Review submitted successfully");
       ReviewService.getAllReviews(function(reviews) {
         
       });
     }, function(err){
       $.unblockUI();
       toastr.error(err?.responseJSON?.message || err?.responseJSON?.error || "Failed to submit review");
     });
   },
   updateReview: function(id, review, callback, error_callback) {
     $.blockUI({ message: '<h3>Processing...</h3>' });
     RestClient.put("reviews/" + id, review, function(response) {
       $.unblockUI();
       toastr.success("Review updated successfully");
       if (callback) callback(response);
     }, function(xhr) {
       $.unblockUI();
       toastr.error(xhr?.responseJSON?.error || xhr?.responseJSON?.message || "Failed to update review");
       if (error_callback) error_callback(xhr);
     });
   },
   deleteReview: function(id) {
     
     if (confirm("Are you sure you want to delete this review?")) {
       $.blockUI({ message: '<h3>Processing...</h3>' });
       RestClient.delete("reviews/" + id, null, function(response) {
         $.unblockUI();
         toastr.success(response?.message || "Review deleted successfully");
         ReviewService.getAllReviews(function(reviews) {
          
         });
       }, function(xhr) {
         $.unblockUI();
         toastr.error(xhr?.responseJSON?.error || xhr?.responseJSON?.message || "Failed to delete review");
       });
     }
   }
};


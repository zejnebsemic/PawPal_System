let ReviewService = {

    init: function () {

        $("#addReviewForm").validate({
            submitHandler: function (form) {
                let review = Object.fromEntries(new FormData(form).entries());
                ReviewService.addReview(review);
                form.reset();
            }
        });

        $("#editReviewForm").validate({
            submitHandler: function (form) {
                let review = Object.fromEntries(new FormData(form).entries());
                ReviewService.updateReview(review);
            }
        });

        ReviewService.getAllReviews();
    },

    addReview: function (review) {
        $.blockUI({ message: '<h3>Processing...</h3>' });

        RestClient.post(
            "reviews",
            JSON.stringify(review),
            function () {
                toastr.success("Review added successfully");
                $.unblockUI();
                ReviewService.closeModal();
                ReviewService.getAllReviews();
            },
            function (response) {
                $.unblockUI();
                toastr.error(response.responseJSON?.error || "Error adding review");
            }
        );
    },

    getAllReviews: function () {
        RestClient.get("reviews", function (res) {
            let data = res.data ?? res;

            Utils.datatable("reviews-table", [
                { data: 'review_id', title: 'ID' },
                { data: 'user_id', title: 'User' },
                { data: 'shelter_id', title: 'Shelter' },
                { data: 'rating', title: 'Rating' },
                { data: 'comment', title: 'Comment' },
                {
                    title: "Actions",
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-primary" onclick="ReviewService.openEditModal('${row.review_id}')">Edit</button>
                            <button class="btn btn-danger" onclick="ReviewService.openDeleteModal('${row.review_id}', '${row.comment}')">Delete</button>
                        `;
                    }
                }
            ], data, 10);
        });
    },

    getReviewById: function (id) {
        $.blockUI({ message: '<h3>Loading...</h3>' });

        RestClient.get(
            "reviews/" + id,
            function (res) {
                let r = res.data ?? res;

                $("#edit_review_id").val(r.review_id);
                $("#edit_rating").val(r.rating);
                $("#edit_comment").val(r.comment);

                $.unblockUI();
            },
            function () {
                $.unblockUI();
                toastr.error("Cannot load review");
            }
        );
    },

    openAddModal: function () {
        $("#addReviewModal").modal("show");
    },

    openEditModal: function (id) {
        $("#editReviewModal").modal("show");
        ReviewService.getReviewById(id);
    },

    openDeleteModal: function (id, comment) {
        $("#deleteReviewModal").modal("show");
        $("#delete_review_id").val(id);
        $("#delete-review-body").html(`Delete review: <b>${comment}</b>?`);
    },

    updateReview: function (review) {
        $.blockUI({ message: '<h3>Updating...</h3>' });

        RestClient.put(
            "reviews/" + review.review_id,
            JSON.stringify(review),
            function () {
                toastr.success("Review updated successfully");
                $.unblockUI();
                ReviewService.closeModal();
                ReviewService.getAllReviews();
            },
            function () {
                $.unblockUI();
                toastr.error("Cannot update review");
            }
        );
    },

    deleteReview: function () {
        let id = $("#delete_review_id").val();

        RestClient.delete(
            "reviews/" + id,
            null,
            function () {
                toastr.success("Review deleted");
                ReviewService.closeModal();
                ReviewService.getAllReviews();
            },
            function (response) {
                toastr.error(response.responseJSON?.error || "Error deleting review");
                ReviewService.closeModal();
            }
        );
    },

    closeModal: function () {
        $(".modal").modal("hide");
    }
};

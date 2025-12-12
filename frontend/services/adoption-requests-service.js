let AdoptionRequestService = {

    init: function () {

        $("#addRequestForm").validate({
            submitHandler: function (form) {
                let req = Object.fromEntries(new FormData(form).entries());
                AdoptionRequestService.addRequest(req);
                form.reset();
            }
        });

        $("#editRequestForm").validate({
            submitHandler: function (form) {
                let req = Object.fromEntries(new FormData(form).entries());
                AdoptionRequestService.updateRequest(req);
            }
        });

        AdoptionRequestService.getAllRequests();
    },

    addRequest: function (req) {
        $.blockUI({ message: '<h3>Processing...</h3>' });

        RestClient.post(
            "adoption-requests",
            JSON.stringify(req),
            function () {
                toastr.success("Request submitted successfully");
                $.unblockUI();
                AdoptionRequestService.closeModal();
                AdoptionRequestService.getAllRequests();
            },
            function (response) {
                $.unblockUI();
                toastr.error(response.responseJSON?.error || "Error submitting request");
            }
        );
    },

    getAllRequests: function () {
        RestClient.get("adoption-requests", function (res) {
            let data = res.data ?? res;

            Utils.datatable("requests-table", [
                { data: 'request_id', title: 'ID' },
                { data: 'user_id', title: 'User' },
                { data: 'pet_id', title: 'Pet' },
                { data: 'status', title: 'Status' },
                {
                    title: "Actions",
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-primary" onclick="AdoptionRequestService.openEditModal('${row.request_id}')">Edit</button>
                            <button class="btn btn-danger" onclick="AdoptionRequestService.openDeleteModal('${row.request_id}')">Delete</button>
                        `;
                    }
                }
            ], data, 10);
        });
    },

    getRequestById: function (id) {
        $.blockUI({ message: '<h3>Loading...</h3>' });

        RestClient.get(
            "adoption-requests/" + id,
            function (res) {
                let r = res.data ?? res;

                $("#edit_request_id").val(r.request_id);
                $("#edit_status").val(r.status);

                $.unblockUI();
            },
            function () {
                $.unblockUI();
                toastr.error("Cannot load request");
            }
        );
    },

    openAddModal: function () {
        $("#addRequestModal").modal("show");
    },

    openEditModal: function (id) {
        $("#editRequestModal").modal("show");
        AdoptionRequestService.getRequestById(id);
    },

    openDeleteModal: function (id) {
        $("#deleteRequestModal").modal("show");
        $("#delete_request_id").val(id);
    },

    updateRequest: function (req) {
        $.blockUI({ message: '<h3>Updating...</h3>' });

        RestClient.put(
            "adoption-requests/" + req.request_id,
            JSON.stringify(req),
            function () {
                toastr.success("Request updated successfully");
                $.unblockUI();
                AdoptionRequestService.closeModal();
                AdoptionRequestService.getAllRequests();
            },
            function () {
                $.unblockUI();
                toastr.error("Cannot update request");
            }
        );
    },

    deleteRequest: function () {
        let id = $("#delete_request_id").val();

        RestClient.delete(
            "adoption-requests/" + id,
            null,
            function () {
                toastr.success("Request deleted");
                AdoptionRequestService.closeModal();
                AdoptionRequestService.getAllRequests();
            },
            function (response) {
                toastr.error(response.responseJSON?.error || "Error deleting request");
                AdoptionRequestService.closeModal();
            }
        );
    },

    closeModal: function () {
        $(".modal").modal("hide");
    }
};

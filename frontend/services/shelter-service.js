let ShelterService = {

    init: function () {

        $("#addShelterForm").validate({
            submitHandler: function (form) {
                let shelter = Object.fromEntries(new FormData(form).entries());
                ShelterService.addShelter(shelter);
                form.reset();
            }
        });

        $("#editShelterForm").validate({
            submitHandler: function (form) {
                let shelter = Object.fromEntries(new FormData(form).entries());
                ShelterService.updateShelter(shelter);
            }
        });

        ShelterService.getAllShelters();
    },

    addShelter: function (shelter) {
        $.blockUI({ message: '<h3>Processing...</h3>' });

        RestClient.post(
            "shelters",
            JSON.stringify(shelter),
            function () {
                toastr.success("Shelter added successfully");
                $.unblockUI();
                ShelterService.closeModal();
                ShelterService.getAllShelters();
            },
            function (response) {
                $.unblockUI();
                toastr.error(response.responseJSON?.error || "Error adding shelter");
            }
        );
    },

    getAllShelters: function () {
        RestClient.get("shelters", function (res) {
            let data = res.data ?? res;

            Utils.datatable("shelters-table", [
                { data: 'shelter_id', title: 'ID' },
                { data: 'name', title: 'Name' },
                { data: 'location', title: 'Location' },
                { data: 'admin_name', title: 'Admin' },
                {
                    title: "Actions",
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-primary" onclick="ShelterService.openEditModal('${row.shelter_id}')">Edit</button>
                            <button class="btn btn-danger" onclick="ShelterService.openDeleteModal('${row.shelter_id}', '${row.name}')">Delete</button>
                        `;
                    }
                }
            ], data, 10);
        });
    },

    getShelterById: function (id) {
        $.blockUI({ message: '<h3>Loading...</h3>' });

        RestClient.get(
            "shelters/" + id,
            function (res) {
                let s = res.data ?? res;

                $("#edit_shelter_id").val(s.shelter_id);
                $("#edit_name").val(s.name);
                $("#edit_location").val(s.location);
                $("#edit_admin_id").val(s.admin_id);

                $.unblockUI();
            },
            function () {
                $.unblockUI();
                toastr.error("Cannot load shelter");
            }
        );
    },

    openAddModal: function () {
        $("#addShelterModal").modal("show");
    },

    openEditModal: function (id) {
        $("#editShelterModal").modal("show");
        ShelterService.getShelterById(id);
    },

    openDeleteModal: function (id, name) {
        $("#deleteShelterModal").modal("show");
        $("#delete_shelter_id").val(id);
        $("#delete-shelter-body").html(`Delete shelter <b>${name}</b>?`);
    },

    updateShelter: function (shelter) {
        $.blockUI({ message: '<h3>Updating...</h3>' });

        RestClient.put(
            "shelters/" + shelter.shelter_id,
            JSON.stringify(shelter),
            function () {
                toastr.success("Shelter updated successfully");
                $.unblockUI();
                ShelterService.closeModal();
                ShelterService.getAllShelters();
            },
            function () {
                $.unblockUI();
                toastr.error("Cannot update shelter");
            }
        );
    },

    deleteShelter: function () {
        let id = $("#delete_shelter_id").val();

        RestClient.delete(
            "shelters/" + id,
            null,
            function () {
                toastr.success("Shelter deleted");
                ShelterService.closeModal();
                ShelterService.getAllShelters();
            },
            function (response) {
                toastr.error(response.responseJSON?.error || "Error deleting shelter");
                ShelterService.closeModal();
            }
        );
    },

    closeModal: function () {
        $(".modal").modal("hide");
    }
};

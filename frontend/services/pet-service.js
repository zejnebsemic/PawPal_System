let PetService = {

    init: function () {

        $("#addPetForm").validate({
            submitHandler: function (form) {
                let pet = Object.fromEntries(new FormData(form).entries());
                PetService.addPet(pet);
                form.reset();
            }
        });

        $("#editPetForm").validate({
            submitHandler: function (form) {
                let pet = Object.fromEntries(new FormData(form).entries());
                PetService.updatePet(pet);
            }
        });

        PetService.getAllPets();
    },

    addPet: function (pet) {
        $.blockUI({ message: '<h3>Saving Pet...</h3>' });

        RestClient.post(
            "pets",
            JSON.stringify(pet),
            function (response) {
                toastr.success("Pet added successfully");
                $.unblockUI();
                PetService.closeModal();
                PetService.getAllPets();
            },
            function (response) {
                $.unblockUI();
                toastr.error(response.responseJSON?.error || "Error adding pet");
            }
        );
    },

    getAllPets: function () {
        RestClient.get("pets", function (res) {

            let data = res.data ?? res.data?.data ?? res;

            Utils.datatable("pets-table", [
                { data: 'pet_id', title: 'ID' },
                { data: 'name', title: 'Name' },
                { data: 'type', title: 'Type' },
                { data: 'age', title: 'Age' },
                { data: 'availability', title: 'Status' },
                {
                    title: "Actions",
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-primary" onclick="PetService.openEditModal('${row.pet_id}')">Edit</button>
                            <button class="btn btn-danger" onclick="PetService.openDeleteModal('${row.pet_id}', '${row.name}')">Delete</button>
                        `;
                    }
                }
            ], data, 10);
        });
    },

    getPetById: function (id) {
        $.blockUI({ message: '<h3>Loading...</h3>' });

        RestClient.get(
            "pets/" + id,
            function (res) {
                let pet = res.data ?? res;

                $("#edit_pet_id").val(pet.pet_id);
                $("#edit_name").val(pet.name);
                $("#edit_type").val(pet.type);
                $("#edit_age").val(pet.age);
                $("#edit_availability").val(pet.availability);

                $.unblockUI();
            },
            function () {
                $.unblockUI();
                toastr.error("Cannot load pet details");
            }
        );
    },

    openAddModal: function () {
        $("#addPetModal").modal("show");
    },

    openEditModal: function (id) {
        $("#editPetModal").modal("show");
        PetService.getPetById(id);
    },

    openDeleteModal: function (id, name) {
        $("#deletePetModal").modal("show");
        $("#delete_pet_id").val(id);
        $("#delete-pet-body").html(`Do you want to delete pet <b>${name}</b>?`);
    },

    updatePet: function (pet) {
        $.blockUI({ message: '<h3>Updating...</h3>' });

        RestClient.put(
            "pets/" + pet.pet_id,
            JSON.stringify(pet),
            function () {
                toastr.success("Pet updated successfully");
                $.unblockUI();
                PetService.closeModal();
                PetService.getAllPets();
            },
            function () {
                $.unblockUI();
                toastr.error("Cannot update pet");
            }
        );
    },

    deletePet: function () {
        let id = $("#delete_pet_id").val();

        RestClient.delete(
            "pets/" + id,
            null,
            function (response) {
                toastr.success("Pet deleted successfully");
                PetService.closeModal();
                PetService.getAllPets();
            },
            function (response) {
                toastr.error(response.responseJSON?.error || "Error deleting pet");
                PetService.closeModal();
            }
        );
    },

    closeModal: function () {
        $(".modal").modal("hide");
    }
};

var PetService = {
    endpoint: "pets",

    getAll: async function (successCallback, errorCallback) {
        try {
            const response = await RestClient.get(this.endpoint);
            if (response && response.success) {
                successCallback(response.data);
            } else {
                errorCallback("Unable to load pets.");
            }
        } catch (err) {
            errorCallback(err);
        }
    },

    getById: async function (id, successCallback, errorCallback) {
        try {
            const response = await RestClient.get(this.endpoint + "/" + id);
            if (response && response.success) {
                successCallback(response.data);
            } else {
                errorCallback("Pet not found.");
            }
        } catch (err) {
            errorCallback(err);
        }
    },

    create: async function (data, successCallback, errorCallback) {
        try {
            const response = await RestClient.post(this.endpoint, data);
            if (response && response.success) {
                successCallback(response.data);
            } else {
                errorCallback(response.error);
            }
        } catch (err) {
            errorCallback(err);
        }
    },

    update: async function (id, data, successCallback, errorCallback) {
        try {
            const response = await RestClient.put(this.endpoint + "/" + id, data);
            if (response && response.success) {
                successCallback(response.data);
            } else {
                errorCallback(response.error);
            }
        } catch (err) {
            errorCallback(err);
        }
    },

    delete: async function (id, successCallback, errorCallback) {
        try {
            const response = await RestClient.delete(this.endpoint + "/" + id);
            if (response && response.success) {
                successCallback();
            } else {
                errorCallback(response.error);
            }
        } catch (err) {
            errorCallback(err);
        }
    }
};

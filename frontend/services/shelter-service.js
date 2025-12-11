var ShelterService = {
    endpoint: "shelters",

    getAll: async function (successCallback, errorCallback) {
        try {
            const response = await RestClient.get(this.endpoint);
            response.success ? successCallback(response.data) : errorCallback(response.error);
        } catch (err) { errorCallback(err); }
    },

    getById: async function (id, successCallback, errorCallback) {
        try {
            const response = await RestClient.get(`${this.endpoint}/${id}`);
            response.success ? successCallback(response.data) : errorCallback("Shelter not found.");
        } catch (err) { errorCallback(err); }
    },

    getByName: async function (name, successCallback, errorCallback) {
        try {
            const response = await RestClient.get(`${this.endpoint}/name/${name}`);
            response.success ? successCallback(response.data) : errorCallback("Not found.");
        } catch (err) { errorCallback(err); }
    },

    getWithAdmins: async function (successCallback, errorCallback) {
        try {
            const response = await RestClient.get(`${this.endpoint}/admins`);
            response.success ? successCallback(response.data) : errorCallback("Unable to load.");
        } catch (err) { errorCallback(err); }
    },

    create: async function (data, successCallback, errorCallback) {
        try {
            const response = await RestClient.post(this.endpoint, data);
            response.success ? successCallback(response.data) : errorCallback(response.error);
        } catch (err) { errorCallback(err); }
    },

    update: async function (id, data, successCallback, errorCallback) {
        try {
            const response = await RestClient.put(`${this.endpoint}/${id}`, data);
            response.success ? successCallback(response.data) : errorCallback(response.error);
        } catch (err) { errorCallback(err); }
    },

    delete: async function (id, successCallback, errorCallback) {
        try {
            const response = await RestClient.delete(`${this.endpoint}/${id}`);
            response.success ? successCallback() : errorCallback(response.error);
        } catch (err) { errorCallback(err); }
    }
};

var AdoptionRequestService = {
    endpoint: "adoption-requests",

    getAll: async function (successCallback, errorCallback) {
        try {
            const res = await RestClient.get(this.endpoint);
            res.success ? successCallback(res.data) : errorCallback(res.error);
        } catch (err) { errorCallback(err); }
    },

    getById: async function (id, successCallback, errorCallback) {
        try {
            const res = await RestClient.get(`${this.endpoint}/${id}`);
            res.success ? successCallback(res.data) : errorCallback("Request not found.");
        } catch (err) { errorCallback(err); }
    },

    getByUser: async function (user_id, successCallback, errorCallback) {
        try {
            const res = await RestClient.get(`${this.endpoint}/user/${user_id}`);
            res.success ? successCallback(res.data) : errorCallback(res.error);
        } catch (err) { errorCallback(err); }
    },

    getPending: async function (successCallback, errorCallback) {
        try {
            const res = await RestClient.get(`${this.endpoint}/pending`);
            res.success ? successCallback(res.data) : errorCallback(res.error);
        } catch (err) { errorCallback(err); }
    },

    create: async function (data, successCallback, errorCallback) {
        try {
            const res = await RestClient.post(this.endpoint, data);
            res.success ? successCallback(res.data) : errorCallback(res.error);
        } catch (err) { errorCallback(err); }
    },

    update: async function (id, data, successCallback, errorCallback) {
        try {
            const res = await RestClient.put(`${this.endpoint}/${id}`, data);
            res.success ? successCallback(res.data) : errorCallback(res.error);
        } catch (err) { errorCallback(err); }
    },

    delete: async function (id, successCallback, errorCallback) {
        try {
            const res = await RestClient.delete(`${this.endpoint}/${id}`);
            res.success ? successCallback() : errorCallback(res.error);
        } catch (err) { errorCallback(err); }
    }
};

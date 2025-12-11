var ReviewService = {
    endpoint: "reviews",

    getAll: async function (successCallback, errorCallback) {
        try {
            const res = await RestClient.get(this.endpoint);
            res.success ? successCallback(res.data) : errorCallback(res.error);
        } catch (err) { errorCallback(err); }
    },

    getById: async function (id, successCallback, errorCallback) {
        try {
            const res = await RestClient.get(`${this.endpoint}/${id}`);
            res.success ? successCallback(res.data) : errorCallback("Review not found.");
        } catch (err) { errorCallback(err); }
    },

    getByShelter: async function (shelter_id, successCallback, errorCallback) {
        try {
            const res = await RestClient.get(`${this.endpoint}/shelter/${shelter_id}`);
            res.success ? successCallback(res.data) : errorCallback(res.error);
        } catch (err) { errorCallback(err); }
    },

    getAverageRating: async function (shelter_id, successCallback, errorCallback) {
        try {
            const res = await RestClient.get(`${this.endpoint}/shelter/${shelter_id}/average`);
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

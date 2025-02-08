import axios from 'axios'

const API_URL = import.meta.env.VITE_API_URL

const api = axios.create({
    baseURL: API_URL,
    timeout: 5000,
    headers: {
        'Content-Type': 'application/json'
    }
});

const apiService = {
    getData: (url, params) => api.get(`/${url}`, {params}),
    storeData: (url, data) => api.post(`/${url}`, data),
    updateData: (url, id, data) => api.put(`/${url}/${id}`, data),
    deleteData: (url, id) => api.delete(`/${url}/${id}`),
    getAll: (request) => { return Promise.all(request) },
    getBy: (url,id) => api.get(`/${url}/${id}`)
}


export default apiService;
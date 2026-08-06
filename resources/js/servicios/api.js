import axios from 'axios';

const api = axios.create({
    baseURL: '/',
    withCredentials: true,
    withXSRFToken: true,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    },
});

export async function obtenerCookieCsrf() {
    await api.get('/sanctum/csrf-cookie');
}

export default api;

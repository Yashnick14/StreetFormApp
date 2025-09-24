import axios from "axios";

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Attach Sanctum token from Blade <meta> or window.API_TOKEN
const tokenMeta = document.querySelector('meta[name="api-token"]');
const token = window.API_TOKEN || (tokenMeta ? tokenMeta.content : null);

if (token) {
    window.axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
}

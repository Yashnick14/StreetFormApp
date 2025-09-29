import axios from "axios";

window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Attach CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken.content;
}

// Attach Sanctum token
const tokenMeta = document.querySelector('meta[name="api-token"]');
const token = window.API_TOKEN || (tokenMeta ? tokenMeta.content : null);

if (token) {
    window.axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
}

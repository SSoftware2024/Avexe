import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.RESPONSE_DATA_KEY_INERTIA = 'response_data';
/**
 * React-specific bootstrap configuration for hybrid Livewire+React setup
 */

import axios from 'axios';

// Basic configuration for React
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

export default axios;

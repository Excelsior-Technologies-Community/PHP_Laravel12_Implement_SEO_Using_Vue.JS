import { createApp } from 'vue';
import router from './router';

import App from './App.vue';
import SeoPreview from './components/SeoPreview.vue';

createApp(App)
    .use(router)
    .component('SeoPreview', SeoPreview)
    .mount('#app');

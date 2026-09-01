import { createApp } from 'vue';
import router from './router';

import App from './App.vue';

import SeoPreview from './components/SeoPreview.vue';
import SeoHealthScore from './components/SeoHealthScore.vue';

createApp(App)
    .use(router)

    .component('SeoPreview', SeoPreview)
    .component('SeoHealthScore', SeoHealthScore)

    .mount('#app');
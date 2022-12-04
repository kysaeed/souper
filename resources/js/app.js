import './bootstrap';

//runtime-core.esm-bundler.js:38 [Vue warn]: Component provided template option but runtime compilation is not supported in this build of Vue. Configure your bundler to alias "vue" to "vue/dist/vue.esm-bundler.js".
// import { createApp } from 'vue';

import { createApp } from 'vue/dist/vue.esm-bundler.js';
import AppComponent from './Components/AppComponent.vue'


createApp(AppComponent)
    .mount('#app')

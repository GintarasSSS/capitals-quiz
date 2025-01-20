import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import {createApp} from "vue";

import App from "./App.vue";
import store from "@/store/index.js";
import BaseCard from "@/components/ui/BaseCard.vue";

createApp(App)
    .use(store)
    .component('base-card', BaseCard)
    .mount('#app');

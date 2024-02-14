import { createApp } from 'vue'
import App from './App.vue'
import UsersComponent from "./components/Users/UsersComponent.vue";
import UsersCreateComponent from "./components/Users/UsersCreateComponent.vue";
import {createRouter, createWebHistory} from "vue-router";

const routes = [
    { path: '/', component: UsersComponent },
    { path: '/create', component: UsersCreateComponent }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

const app = createApp(App)
    .use(router)
    .mount('#app');

export default app;

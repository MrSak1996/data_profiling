import { createRouter, createWebHistory } from "vue-router";
import Dashboard from '../components/Dashboard.vue';
import DefaultLayout from '../components/DefaultLayout.vue'; 
import sample from '../components/ExampleComponent.vue';
// import Login from '../views/Login.vue'; 
const routes = [

    {
        path: '/',
        redirect: '/dashboard',
        component: DefaultLayout,
        children:[
            { path:'/dashboard', name: 'Dashboard',component:Dashboard},
            { path:'/sample', name: 'Sample',component:sample},
            // { path: '/login', name: 'Login', component: Login },
        ]
    },
   

]
const router = createRouter({
    history:createWebHistory(),
    routes
})
export default router;
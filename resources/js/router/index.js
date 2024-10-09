import { createRouter, createWebHistory } from "vue-router";
import Dashboard from '../components/Dashboard.vue';
import DefaultLayout from '../components/DefaultLayout.vue';
import Login from '../components/Login.vue';
import Profile from '../components/user-management/profile.vue';
import UserAccounts from '../components/user-management/user-accounts.vue';
import CreateUser from '../components/user-management/create-user.vue';
const routes = [

    {
        path: '/',
        redirect: '/login-page',
        component: DefaultLayout,
        children: [
            {
                path: '/dashboard', name: 'Dashboard', component: Dashboard,
                meta: {
                    requiresAuth: true
                },
                beforeEnter: (to, from, next) => {
                    const token = localStorage.getItem('api_token');
                    axios.get('/api/authenticated', {
                        params: {
                            api_token: token
                        }
                    }).then(response => {
                        if (response.data.authenticated) {
                            next();
                        } else {
                            next({ name: 'Login' });
                        }
                    }).catch(() => {
                        next({ name: 'Login' });
                    });
                }
            },
            { path: '/login-page', name: 'Login', component: Login },
            { path: '/profile', name: 'profile', component: Profile },
            { path: '/user-accounts', name: 'user-accounts', component: UserAccounts },
            { path: '/create-user', name: 'create-user', component: CreateUser },
        ]
    }


]
const router = createRouter({
    history: createWebHistory(),
    routes
})
export default router;
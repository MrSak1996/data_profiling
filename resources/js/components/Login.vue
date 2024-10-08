<template>

    <div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Welcome to SSDPA
            </h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form class="space-y-6" @submit.prevent="loginUser">

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Username
                        </label>
                        <div class="mt-1">
                            <input v-model="form.username" id="email" type="text" autocomplete="email" required
                                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                placeholder="Enter your email address">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <div class="mt-1">
                            <input v-model="form.password" id="password" name="password" type="password"
                                autocomplete="current-password" required
                                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                                placeholder="Enter your password">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember_me" type="checkbox"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                                Remember me
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                                Forgot your password?
                            </a>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">

                            Sign in
                        </button>
                    </div>
                </form>
                <div class="mt-6">


                </div>
            </div>
        </div>
        <event-toast ref="toast"></event-toast>

    </div>
</template>

<script>
import EventToast from "./EventToast.vue";
export default {
    name: 'Login',
    components: {
        EventToast
    },
    data() {
        return {
            form: {
                email: '',
                password: '',
            },
        }
    },
    methods: {
        loginUser() {
            axios
                .post('/api/login', this.form)
                .then(response => {
                    if (response.data.status) {
                        // //this code is to make user id accessible globally
                        localStorage.setItem('userId', response.data.userId);
                        localStorage.setItem('api_token', response.data.api_token);

                        // localStorage.setItem('user_role', response.data.user_role);
                        // localStorage.setItem('isUpdatedPassword', response.data.isUpdatedPassword);
                        // console.log(response.data.isUpdatedPassword);

                        // this.showSuccessNotification('You are logged in');
                       this.triggerSuccess();
                        
                        setTimeout(() => {
                            this.$router.push({ name: 'Dashboard', query: { api_token: response.data.api_token } });
                        }, 1000);
                    } else {
                       this.triggerError();
                       
                        setTimeout(() => {
                            this.$router.push({ name: 'Login' });

                        }, 1000);
                    }
                })
                .catch(error => {

                    if (error.response && error.response.data) {
                        // Server returned an error response
                        this.errors = error.response.data.errors;
                    } else {
                        // Error occurred during the request
                        console.log(error);
                    }
                });
        },
        triggerSuccess() {
            // Call the toast method on the Toast component and pass the message and type
            this.$refs.toast.showToast('Login successfully.', 'success');
        },
        triggerError() {
            // Call the toast method on the Toast component and pass the message and type
            this.$refs.toast.showToast('Failed to login.', 'error');
        },
    }
}
</script>
<style>
#toast-container {
    position: fixed;
    top: 1rem;
    /* Adjust as needed */
    right: 1rem;
    /* Adjust as needed */
    z-index: 9999;
    /* Ensure the toast is above other elements */
}

.hidden {
    display: none;
}
</style>

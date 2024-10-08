<template>
    <div class="p-4 sm:ml-64">
        <navbar></navbar>
        <div class="p-4 border-2 border-gray-100 rounded-lg">
            <div class="flex-1 flex flex-col">
                <nav class="mb-2 flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                    aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                            <a href="#"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                                </svg>
                                User Management
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 block w-3 h-3 mx-1 text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <a href="#"
                                    class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">User Accounts</a>
                            </div>
                        </li>
                    </ol>
                </nav>
                <main class="flex-grow flex min-h-0 border-t">
                    <div class="w-full max-w-12xl mx-auto p-6 bg-white rounded-md shadow-md">
                        <h2 class="text-2xl font-bold mb-6 text-gray-700">
                            User Accounts
                        </h2>
                        <table id="example"
                class="border-t w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th style="width:5%;"></th>
                        <th class="px-4 py-2">Employee No.</th>
                        <th class="px-4 py-2">Username</th>
                        <th class="px-4 py-2">First Name</th>
                        <th class="px-4 py-2">Middle Name</th>
                        <th class="px-4 py-2">Last Name</th>
                        <th class="px-4 py-2">Extension Name</th>
                        <th class="px-4 py-2">Office Email</th>
                        <th class="px-4 py-2">Office Station</th>
                        <th class="px-4 py-2">Designation/Bureu</th>
                        <th class="px-4 py-2">Position</th>
                        <th class="px-4 py-2">Department</th>
                        <th class="px-4 py-2">Province</th>
                        <th class="px-4 py-2">Region</th>
                        <th class="px-4 py-2">User Role</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table content -->
                </tbody>
            </table>
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>
<script>
import navbar from "../layout/navbar.vue";

import { ref, onMounted, computed } from "vue";

export default {
    name: "profile",
    components: {
        navbar,
    },
};
</script>
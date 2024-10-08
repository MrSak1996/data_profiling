<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router'; // Import the useRoute hook
import axios from 'axios'; // Make sure to import axios

import admin_tabs from './admin_navigation.vue'; // Import your navigation component

const route = useRoute(); // Use the useRoute hook to access route information
const totalFiles = ref(0);
const totalUploadedRecords = ref(0);

const formattedTotalRecords = computed(() => totalUploadedRecords.value);
const formattedTotalFiles = computed(() => totalFiles.value.toLocaleString());

const fetchTotalFilesCount = async () => {
    try {
        const id = route.query.id; // Get the 'id' from the query parameters
        const response = await axios.get(`api/uploaded-files?id=${id}`); // Corrected to use backticks for string interpolation
        totalUploadedRecords.value = response.data.uploaded_files; // Assuming the API returns { uploaded_files: <count> }
    } catch (error) {
        console.error('Failed to fetch total files count:', error);
    }
};

const fetchUploadedFiles = async () => {
    try {
        const response = await axios.get('api/getFiles'); // Replace with your API endpoint
        totalFiles.value = response.data.count;
    } catch (error) {

    }
}


onMounted(() => {
    fetchTotalFilesCount();
    fetchUploadedFiles();
});
</script>

<template>
    <div>
        <section aria-label="main content" class="flex min-h-0 flex-col flex-auto border-l">
            <header class="bg-white border-t flex items-center py-1 px-4">
                <div class="flex">
                    <span class="ml-3 group relative">
                        <button role="details" aria-controls="info-popup"
                            class="text-blue-700 border-b border-dotted border-blue-700 focus:outline-none text-sm">
                            Data Profiling Application
                        </button>
                        <div role="tooltip" id="info-popup"
                            class="absolute pt-1 rounded-md rounded-t-lg right-0 transform translate-x-40 mx-auto hidden group-hover:block z-50">
                            <div class="border rounded-md rounded-t-lg shadow-lg bg-white w-full max-w-xs w-screen">
                                <header class="font-semibold rounded-t-lg bg-gray-300 px-4 py-2">
                                    System Overview
                                </header>
                                <div class="p-4 border-t">
                                    <p class="mb-4">
                                        These are new or open
                                        tickets that are assigned to
                                        you, unassinged in your
                                        group(s) or not assigned to
                                        any group.
                                    </p>
                                    <p class="mb-1">
                                        They are ordered by priority
                                        and requester update date
                                        (oldest first).
                                    </p>
                                </div>
                            </div>
                        </div>
                    </span>
                </div>

            </header>
            <div class="p-4  dark:border-gray-700">
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <div class="flex items-center h-24 rounded bg-blue-50 dark:bg-gray-800">
                        <div class="p-6 bg-indigo-400"><svg xmlns="http://www.w3.org/2000/svg"
                                class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg></div>
                        <div class="px-4 text-gray-700">
                            <h3 class="text-sm tracking-wider">Total Uploaded Files</h3>
                            <p class="text-3xl">{{ formattedTotalFiles }}</p>
                        </div>
                    </div>
                    <div class="flex items-center h-24 rounded bg-blue-50 dark:bg-gray-800">
                        <div class="p-6 bg-indigo-400"><svg xmlns="http://www.w3.org/2000/svg"
                                class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                                </path>
                            </svg></div>
                        <div class="px-4 text-gray-700">
                            <h3 class="text-sm tracking-wider">Total Uploaded Records</h3>
                            <p class="text-3xl">{{ formattedTotalRecords }}</p>
                        </div>
                    </div>
                    <div class="flex items-center h-24 rounded bg-blue-50 dark:bg-gray-800">
                        <div class="p-6 bg-indigo-400"><svg xmlns="http://www.w3.org/2000/svg"
                                class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                                </path>
                            </svg></div>
                        <div class="px-4 text-gray-700">
                            <h3 class="text-sm tracking-wider">Total Uploaded Records</h3>
                            <p class="text-3xl">{{ formattedTotalRecords }}</p>
                        </div>
                    </div>
                    <div class="flex items-center h-24 rounded bg-blue-50 dark:bg-gray-800">
                        <div class="p-6 bg-indigo-400"><svg xmlns="http://www.w3.org/2000/svg"
                                class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                                </path>
                            </svg></div>
                        <div class="px-4 text-gray-700">
                            <h3 class="text-sm tracking-wider">Total Uploaded Records</h3>
                            <p class="text-3xl">{{ formattedTotalRecords }}</p>
                        </div>
                    </div>

                </div>
                <!-- :checkDataMatches="checkDataMatches" -->
            </div>
            
             <admin_tabs></admin_tabs>
            
        </section>
    </div>
</template>

<style scoped></style>

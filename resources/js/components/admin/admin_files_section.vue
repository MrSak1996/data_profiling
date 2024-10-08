<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios'; // Make sure to import axios

const files = ref([]);

const getFiles = async () => {
    try {
        const response = await axios.get('api/getFiles'); // Replace with your API endpoint
        files.value = response.data.data;
    } catch (error) {
        console.error('Error fetching uploaded files:', error); // Log the error for debugging
    }
};

const viewFileData = async (id) => {
    try {
        // Change the URL query parameter with the new id
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('id', id); // Set or update the 'id' parameter
        window.location.href = currentUrl.href; // Reload the page with the new URL
    } catch (error) {
        console.error('Error reloading the page:', error);
    }
};


onMounted(() => {
    getFiles();
});
</script>

<template>
   <div>
        <section class="flex flex-col p-4 w-full max-w-xs flex-none bg-gray-100 min-h-0 overflow-auto">
            <h1 class="font-semibold mb-3">
                Download Files
            </h1>
            <ul>
                <li v-for="item in files" :key="item.filename">
                    <article tabindex="0" @click="viewFileData(item.id)"
                        class="cursor-pointer border rounded-md p-3 bg-white flex text-gray-700 mb-2 hover:border-green-500 focus:outline-none focus:border-green-500">
                        <span class="flex-none pt-1 pr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m-6 3.75 3 3m0 0 3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75" />
                            </svg>
                        </span>
                        <div class="flex-1">
                            <header class="mb-1">
                                <h1 class="inline">
                                    {{ item.file_name }}
                                </h1>
                            </header>

                            <footer class="text-gray-500 mt-2 text-sm">
                                Date Uploaded: {{ item.updated_at }}
                            </footer>
                        </div>
                    </article>
                </li>
            </ul>

        </section>
    </div>
</template>

<style scoped>
/* Add any scoped styles if necessary */
</style>


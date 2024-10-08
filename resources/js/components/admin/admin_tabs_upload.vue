<template>
    <div v-if="activeTab === 'upload'"
        class="flex flex-col items-start h-auto p-4 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <ProgressBar ref="progressBar" />
        <toast />

        <form @submit.prevent="uploadRecords" ref="mainForm" enctype="multipart/form-data"
            class="flex flex-col items-center w-full space-y-4">
            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file"
                    class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                            <span class="font-semibold">Click to upload</span> or drag and drop
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            SVG, PNG, JPG or GIF (MAX. 800x400px)
                        </p>
                    </div>
                    <input type="file" id="dropzone-file" name="selected_file" @change="handleFileChange"
                        ref="fileInput" accept=".xlsx, .xls, .csv" class="hidden" />
                </label>
            </div>
            <div class="flex justify-end w-full">
                <button type="submit" name="upload"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Upload File
                </button>
            </div>
        </form>

    </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import ProgressBar from '../progressBar.vue';
import toast from '../toast.vue';
export default {
    components: {
        ProgressBar,
        toast
    },
    props: ['activeTab'],
    data() {
        return {
            file: null,
            progressBar: 0,
            
        };
    },
    methods: {

        handleFileChange(event) {
            this.file = event.target.files[0];  // Store the file in the data property
            console.log("File selected:", this.file);
        },

        async uploadRecords() {
            if (!this.file) {
                alert("Please select a file.");
                return;
            }

            try {
                this.$refs.progressBar.startProgress();
                const formData = new FormData();
                formData.append("file", this.file); // Make sure this.file is a valid file object
                formData.append("filename", this.file.name);
                formData.append('userId', 1);
                // formData.append('userId', this.userId);

                const response = await axios.post("api/import_excel", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                    onUploadProgress: (progressEvent) => {
                        if (progressEvent.lengthComputable) {
                            const progress = Math.floor((progressEvent.loaded / progressEvent.total) * 100);
                            this.$refs.progressBar.setProgress(progress);
                        }
                    }
                });

                // Show the toast
                const toast = document.getElementById('toast-success');
                if (toast) {
                    console.log("Toast element found");
                    toast.classList.remove('hidden');
                    console.log("Toast is now visible");

                    // Optionally hide the toast after 1 second
                    setTimeout(() => {
                        toast.classList.add('hidden');
                        location.reload();
                    }, 1000); // 1 second delay
                } else {
                    console.error("Toast element not found");
                }
                this.$refs.progressBar.completeProgress();

            } catch (error) {
                if (error.response) {
                    // Server responded with a status code outside of the 2xx range
                    console.error("Error importing data:", error.response.data);
                    alert("An error occurred: " + (error.response.data.message || "Unknown server error"));
                } else if (error.request) {
                    // No response was received
                    console.error("No response received from the server.");
                    alert("An error occurred: No response from server.");
                } else {
                    // Something happened in setting up the request
                    console.error("Error in request setup:", error.message);
                    alert("An error occurred: " + error.message);
                }
            }
        },

    },
};
</script>

<style scoped>
/* Add any specific styles here if necessary */
</style>

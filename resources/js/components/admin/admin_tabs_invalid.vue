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
            duplicate_data: [],
            invalidData: [],
            progressBar: null
        };
    },
    methods: {
        async duplicateDataStaging() {
            try {
                const response = await axios.get('api/getDuplicateDataStaging');
                const data = response.data;
                this.duplicate_data = data.data;

                if (data.count === 0) {
                    console.error('No records found.');
                    return;
                }
                this.calculateVisiblePagesDuplicateStaging();
            } catch (error) {
                console.error('Failed to fetch data:', error);
            }
        },

        async getInvalidData() {
            try {
                this.$refs.progressBar.startProgress();
                const id = this.$route.query.id; // Get the 'id' from the URL query parameters

                // Use template literals correctly for the URL
                const response = await axios.get(`api/getInvalidData?id=${id}`, {
                    onDownloadProgress: (progressEvent) => {
                        if (progressEvent.lengthComputable) {
                            const progress = Math.floor((progressEvent.loaded / progressEvent.total) * 100);
                            this.$refs.progressBar.setProgress(progress);
                        }
                    }
                }); // Replace with your actual API endpoint

                // Ensure you're handling the response data correctly
                if (response.data.null_values === 0) {
                    this.invalidData = response.data;
                } else {
                    this.invalidData = response.data[0]; // Assuming this is correct
                }

                this.$refs.progressBar.completeProgress();

            } catch (error) {
                console.error('Error fetching invalid data:', error);
                this.$refs.progressBar.completeProgress(); // Complete progress in case of error
            }
        },

        async checkValidation() {
            try {
                this.$refs.progressBar.startProgress();
                const id = this.$route.query.id; // Get the 'id' from the URL query parameters

                const response = await axios.post(`api/checkValidation?id=${id}`, {
                    // Post data should be included here if required, e.g., { someKey: someValue }
                }, {
                    onDownloadProgress: (progressEvent) => {
                        if (progressEvent.lengthComputable) {
                            const progress = Math.floor((progressEvent.loaded / progressEvent.total) * 100);
                            this.$refs.progressBar.setProgress(progress);
                        }
                    }
                });

                this.$refs.progressBar.completeProgress();
                // Ensure you call getInvalidData() correctly with `this`
                await this.getInvalidData();

            } catch (error) {
                console.error('Error checking validation:', error);
                this.$refs.progressBar.completeProgress(); // Complete progress in case of error
            }
        },



    },

};
</script>

<style scoped>
/* Add any specific styles here if necessary */
</style>

<template>
    <div v-if="activeTab === 'invalid'"
        class="w-full h-auto p-4 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="relative overflow-x-auto">
            <div class="flex justify-start space-x-2 mb-2">
                <button type="button" @click="checkValidation"
                    class="flex items-center text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Run Data Validation
                </button>
                <button type="button"
                    class="flex items-center text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Download
                </button>
                <ProgressBar ref="progressBar" />

            </div>
            <div class="flex-grow flex min-h-0 border-t">
                <div class="flex flex-col p-4 w-full max-w-sm flex-none min-h-0 overflow-auto">
                    <div class="flex items-center p-4 bg-blue-50 rounded">
                        <div class="flex flex-shrink-0 items-center justify-center bg-green-200 h-16 w-16 rounded">

                            <svg class="w-6 h-6 fill-current text-green-700" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 448 512">
                                <path
                                    d="M448 80l0 48c0 44.2-100.3 80-224 80S0 172.2 0 128L0 80C0 35.8 100.3 0 224 0S448 35.8 448 80zM393.2 214.7c20.8-7.4 39.9-16.9 54.8-28.6L448 288c0 44.2-100.3 80-224 80S0 332.2 0 288L0 186.1c14.9 11.8 34 21.2 54.8 28.6C99.7 230.7 159.5 240 224 240s124.3-9.3 169.2-25.3zM0 346.1c14.9 11.8 34 21.2 54.8 28.6C99.7 390.7 159.5 400 224 400s124.3-9.3 169.2-25.3c20.8-7.4 39.9-16.9 54.8-28.6l0 85.9c0 44.2-100.3 80-224 80S0 476.2 0 432l0-85.9z" />
                            </svg>
                        </div>
                        <div class="flex-grow flex flex-col ml-4">
                            <span class="text-xl font-bold">{{ this.invalidData.null_values }}</span>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Total No. of NULL Data</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center mt-2 p-4 bg-blue-50 rounded">
                        <div class="flex flex-shrink-0 items-center justify-center bg-green-200 h-16 w-16 rounded">

                            <svg class="w-6 h-6 fill-current text-green-700" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 448 512">
                                <path
                                    d="M448 80l0 48c0 44.2-100.3 80-224 80S0 172.2 0 128L0 80C0 35.8 100.3 0 224 0S448 35.8 448 80zM393.2 214.7c20.8-7.4 39.9-16.9 54.8-28.6L448 288c0 44.2-100.3 80-224 80S0 332.2 0 288L0 186.1c14.9 11.8 34 21.2 54.8 28.6C99.7 230.7 159.5 240 224 240s124.3-9.3 169.2-25.3zM0 346.1c14.9 11.8 34 21.2 54.8 28.6C99.7 390.7 159.5 400 224 400s124.3-9.3 169.2-25.3c20.8-7.4 39.9-16.9 54.8-28.6l0 85.9c0 44.2-100.3 80-224 80S0 476.2 0 432l0-85.9z" />
                            </svg>
                        </div>
                        <div class="flex-grow flex flex-col ml-4">
                            <span class="text-xl font-bold">{{ this.invalidData.unwanted_char }} </span>

                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Total No. of Alphanumeric Data</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center mt-2 p-4 bg-blue-50 rounded">
                        <div class="flex flex-shrink-0 items-center justify-center bg-green-200 h-16 w-16 rounded">

                            <svg class="w-6 h-6 fill-current text-green-700" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 448 512">
                                <path
                                    d="M448 80l0 48c0 44.2-100.3 80-224 80S0 172.2 0 128L0 80C0 35.8 100.3 0 224 0S448 35.8 448 80zM393.2 214.7c20.8-7.4 39.9-16.9 54.8-28.6L448 288c0 44.2-100.3 80-224 80S0 332.2 0 288L0 186.1c14.9 11.8 34 21.2 54.8 28.6C99.7 230.7 159.5 240 224 240s124.3-9.3 169.2-25.3zM0 346.1c14.9 11.8 34 21.2 54.8 28.6C99.7 390.7 159.5 400 224 400s124.3-9.3 169.2-25.3c20.8-7.4 39.9-16.9 54.8-28.6l0 85.9c0 44.2-100.3 80-224 80S0 476.2 0 432l0-85.9z" />
                            </svg>
                        </div>
                        <div class="flex-grow flex flex-col ml-4">
                            <span class="text-xl font-bold">{{ this.invalidData.date_format }}</span>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Total No. of Invalid Date Format</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center mt-2 p-4 bg-blue-50 rounded">
                        <div class="flex flex-shrink-0 items-center justify-center bg-green-200 h-16 w-16 rounded">

                            <svg class="w-6 h-6 fill-current text-green-700" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 448 512">
                                <path
                                    d="M448 80l0 48c0 44.2-100.3 80-224 80S0 172.2 0 128L0 80C0 35.8 100.3 0 224 0S448 35.8 448 80zM393.2 214.7c20.8-7.4 39.9-16.9 54.8-28.6L448 288c0 44.2-100.3 80-224 80S0 332.2 0 288L0 186.1c14.9 11.8 34 21.2 54.8 28.6C99.7 230.7 159.5 240 224 240s124.3-9.3 169.2-25.3zM0 346.1c14.9 11.8 34 21.2 54.8 28.6C99.7 390.7 159.5 400 224 400s124.3-9.3 169.2-25.3c20.8-7.4 39.9-16.9 54.8-28.6l0 85.9c0 44.2-100.3 80-224 80S0 476.2 0 432l0-85.9z" />
                            </svg>
                        </div>
                        <div class="flex-grow flex flex-col ml-4">
                            <span class="text-xl font-bold">{{ this.invalidData.specialchar }} </span>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Total No. of Special Character</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center mt-2 p-4 bg-blue-50 rounded">
                        <div class="flex flex-shrink-0 items-center justify-center bg-green-200 h-16 w-16 rounded">

                            <svg class="w-6 h-6 fill-current text-green-700" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 448 512">
                                <path
                                    d="M448 80l0 48c0 44.2-100.3 80-224 80S0 172.2 0 128L0 80C0 35.8 100.3 0 224 0S448 35.8 448 80zM393.2 214.7c20.8-7.4 39.9-16.9 54.8-28.6L448 288c0 44.2-100.3 80-224 80S0 332.2 0 288L0 186.1c14.9 11.8 34 21.2 54.8 28.6C99.7 230.7 159.5 240 224 240s124.3-9.3 169.2-25.3zM0 346.1c14.9 11.8 34 21.2 54.8 28.6C99.7 390.7 159.5 400 224 400s124.3-9.3 169.2-25.3c20.8-7.4 39.9-16.9 54.8-28.6l0 85.9c0 44.2-100.3 80-224 80S0 476.2 0 432l0-85.9z" />
                            </svg>
                        </div>
                        <div class="flex-grow flex flex-col ml-4">
                            <span class="text-xl font-bold">{{ this.invalidData.below_2letters }} </span>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Total No. of Data <= 2 Character</span>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="flex min-h-0 flex-col flex-auto border-l p-4" style="height:500px; overflow-y: auto;">


                    <!-- <table class="border-t w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-2 w-32">File Name</th>
                                <th class="px-4 py-2 w-32">Column Name</th>
                                <th class="px-4 py-2">Invalid Data</th>
                                <th class="px-4 py-2">Validation Date</th>
                                <th class="px-4 py-2">Uploaded By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in fetchOnbintInvalid" :key="item.id" class="bg-white dark:bg-gray-800 border-b">
                                <td> {{ item.filename }} </td>
                                <td> {{ item.column_name }} </td>
                                <td> {{ item.invalid_data }} </td>
                                <td> {{ item.updated_at }} </td>
                                <td> masacluti </td>
                            </tr>
                        </tbody>
                    </table> -->

                </div>

            </div>

        </div>
    </div>
</template>
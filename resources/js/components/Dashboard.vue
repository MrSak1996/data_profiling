<template>
    <div class="flex-1 flex flex-col">
        <header aria-label="page caption" class="flex-none flex h-16 bg-gray-100 border-t px-4 items-center">
            <h1 id="page-caption" class="font-semibold text-lg">
                Dashboard
            </h1>
        </header>
        <main class="flex-grow flex min-h-0 border-t">
            <section class="flex flex-col p-4 w-full max-w-sm flex-none bg-gray-100 min-h-0 overflow-auto">
                <h1 class="font-semibold mb-3">
                    Download Files
                </h1>
                <ul>
                    <li v-for="item in uploadedFiles" :key="item.filename">
                        <article tabindex="0"
                            class="cursor-pointer border rounded-md p-3 bg-white flex text-gray-700 mb-2 hover:border-green-500 focus:outline-none focus:border-green-500">
                            <span class="flex-none pt-1 pr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m-6 3.75 3 3m0 0 3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75" />
                                </svg>
                            </span>
                            <div class="flex-1">
                                <header class="mb-1">
                                    <h1 class="inline">
                                        {{ item.filename }}
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
            <section aria-label="main content" class="flex min-h-0 flex-col flex-auto border-l">
                <!-- content navigation -->
                <nav class="bg-gray-100 flex p-4">
                    <!-- open tickets nav -->
                    <!-- <section aria-labelledby="open-tickets-tabs-label" class="mr-4 focus:outline-none">
                        <label id="open-tickets-tabs-label" class="font-semibold block mb-1 text-sm">Programs
                            <span class="font-normal text-gray-700">(current)</span>
                        </label>
                        <ul class="flex">
                            <li>
                                <button
                                    class="focus:outline-none focus:bg-yellow-200 p-2 rounded-l-md border border-r-0 bg-white flex flex-col items-center w-32">
                                    <p class="font-semibold text-lg">6</p>
                                    <p class="text-sm uppercase text-gray-600">(RFFA2) Rice Farmer Financial Assistance
                                        2021</p>
                                </button>
                            </li>
                            <li>
                                <button
                                    class="focus:outline-none focus:bg-yellow-200 p-2 rounded-l-md border border-r-0 bg-white flex flex-col items-center w-32">
                                    <p class="font-semibold text-lg">
                                        6
                                    </p>
                                    <p class="text-sm uppercase text-gray-600">
                                        (RFFA3) Rice Farmer Financial Assistance 2022
                                    </p>
                                </button>
                            </li>
                            <li>
                                <button
                                    class="focus:outline-none focus:bg-yellow-200 p-2 rounded-l-md border border-r-0 bg-white flex flex-col items-center w-32">
                                    <p class="font-semibold text-lg">
                                        6
                                    </p>
                                    <p class="text-sm uppercase text-gray-600">
                                        (RFFA4) Rice Farmer Financial Assistance 2023
                                    </p>
                                </button>
                            </li>
                            <li>
                                <button
                                    class="focus:outline-none focus:bg-yellow-200 p-2 border rounded-r-md bg-white flex flex-col items-center w-32 cursor-pointer">
                                    <p class="font-semibold text-lg">
                                        23
                                    </p>
                                    <p class="text-sm uppercase text-gray-600">
                                        Rice Fertilizer Discount Voucer 2022
                                    </p>
                                </button>
                            </li>
                            <li>
                                <button
                                    class="focus:outline-none focus:bg-yellow-200 p-2 border rounded-r-md bg-white flex flex-col items-center w-32 cursor-pointer">
                                    <p class="font-semibold text-lg">
                                        23
                                    </p>
                                    <p class="text-sm uppercase text-gray-600">
                                        Corn Fertilizer Discount Voucher 2022
                                    </p>
                                </button>
                            </li>
                            <li>
                                <button
                                    class="focus:outline-none focus:bg-yellow-200 p-2 border rounded-r-md bg-white flex flex-col items-center w-32 cursor-pointer">
                                    <p class="font-semibold text-lg">
                                        23
                                    </p>
                                    <p class="text-sm uppercase text-gray-600">
                                        2022-2023 Rice Fertilizer Discount for Dry Season
                                    </p>
                                </button>
                            </li>
                            <li>
                                <button
                                    class="focus:outline-none focus:bg-yellow-200 p-2 border rounded-r-md bg-white flex flex-col items-center w-32 cursor-pointer">
                                    <p class="font-semibold text-lg">
                                        23
                                    </p>
                                    <p class="text-sm uppercase text-gray-600">
                                        Rice FDV - Wet Season
                                    </p>
                                </button>
                            </li>
                        </ul>
                    </section> -->
                    <!-- stats nav -->
                    <!-- <section aria-labelledby="ticket-statistics-tabs-label" class="pb-2">
                        <label id="ticket-statistics-tabs-label" class="font-semibold block mb-1 text-sm">Ticket
                            Statistics
                            <span class="font-normal text-gray-700">(this week)</span></label>
                        <ul class="flex">
                            <li>
                                <button
                                    class="focus:outline-none focus:bg-yellow-200 p-2 rounded-l-md border border-r-0 bg-white flex flex-col items-center w-32">
                                    <p class="font-semibold text-lg">
                                        16
                                    </p>
                                    <p class="uppercase text-gray-600 text-sm">
                                        good
                                    </p>
                                </button>
                            </li>
                            <li>
                                <button
                                    class="focus:outline-none focus:bg-yellow-200 p-2 border border-r-0 bg-white flex flex-col items-center w-32">
                                    <p class="font-semibold text-lg">
                                        2
                                    </p>
                                    <p class="uppercase text-gray-600 text-sm">
                                        bad
                                    </p>
                                </button>
                            </li>

                            <li>
                                <button
                                    class="focus:outline-none focus:bg-yellow-200 p-2 border rounded-r-md bg-white flex flex-col items-center w-32">
                                    <p class="font-semibold text-lg">
                                        32
                                    </p>
                                    <p class="uppercase text-gray-600 text-sm">
                                        solved
                                    </p>
                                </button>
                            </li>
                        </ul>
                    </section> -->
                </nav>

                <!-- content caption -->
                <header class="bg-white border-t flex items-center py-1 px-4">
                    <div class="flex">
                        <h2 id="content-caption" class="font-semibold">
                            Tickets requiring your attention (6)
                        </h2>
                        <span class="ml-3 group relative">
                            <button role="details" aria-controls="info-popup"
                                class="text-blue-700 border-b border-dotted border-blue-700 focus:outline-none text-sm">
                                What is this?
                            </button>
                            <div role="tooltip" id="info-popup"
                                class="absolute pt-1 rounded-md rounded-t-lg right-0 transform translate-x-40 mx-auto hidden group-hover:block z-50">
                                <div class="border rounded-md rounded-t-lg shadow-lg bg-white w-full max-w-xs w-screen">
                                    <header class="font-semibold rounded-t-lg bg-gray-300 px-4 py-2">
                                        People are waiting for replies!
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
                    <div class="ml-auto">
                        <button title="See available tickets in this view" aria-label="play"
                            class="border rounded-md px-3 py-2 leading-none">
                            Play
                        </button>
                    </div>
                </header>
                <div class="p-4  dark:border-gray-700">
                    <div class="grid grid-cols-4 gap-4 mb-4">
                        <div class="flex items-center h-24 rounded bg-blue-50 dark:bg-gray-800">
                            <div class="p-6 bg-green-400"><svg xmlns="http://www.w3.org/2000/svg"
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
                            <div class="p-6 bg-blue-400"><svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2">
                                    </path>
                                </svg></div>
                            <div class="px-4 text-gray-700">
                                <h3 class="text-sm tracking-wider">Total Valid Record</h3>
                                <p class="text-3xl">39,265</p>
                            </div>
                        </div>
                        <div class="flex items-center h-24 rounded bg-blue-50 dark:bg-gray-800">
                            <div class="p-6 bg-red-400"><svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4">
                                    </path>
                                </svg></div>
                            <div class="px-4 text-gray-700">
                                <h3 class="text-sm tracking-wider">Total Invalid Records</h3>
                                <p class="text-3xl">34.12%</p>
                            </div>
                        </div>
                    </div>

                    <TabsTimelineModule :invalid_data="invalid_data" :checkDataMatches="checkDataMatches"
                        :totalRecords="formattedTotalRecords" />
                </div>



            </section>
        </main>
    </div>

    <main>
        <modal_upload :isOpen="isModalOpen" @close="closeModal" :uploadProgress="uploadProgress" />

        <div id="toast-container" class="fixed top-4 right-4 z-50">
            <div id="toast-success"
                class="hidden flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
                role="alert">
                <div
                    class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">Item moved successfully.</div>
                <button type="button"
                    class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    data-dismiss-target="#toast-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        </div>
    </main>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import $ from 'jquery';
import TabsTimelineModule from './TabsTimelineModule.vue';

import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-dt';
DataTable.use(DataTablesCore);

import axios from "axios";

import pagination from "./Pagination.vue";
import modal_upload from "./modal/modal_uploading.vue";
import onbint_uploaded_table from "./table/uploaded_table.vue";
import onbint_invalid_table from "./table/onbint_invalid_table.vue";


export default {
    name: "Dashboard",
    components: {
        pagination,
        modal_upload,
        onbint_uploaded_table,
        onbint_invalid_table,
        DataTable,
        DataTablesCore,
        TabsTimelineModule,
    },
    setup() {
        const file = ref(null);
        const mainForm = ref(null);
        const uploadProgress = ref(0);
        const totalFiles = ref(0);
        const totalUploadedRecords = ref(0);
        const isModalOpen = ref(false);
        const invalid_data = ref([]);
        const exampleTable = ref(null);



        // PAGINATION
        const onbint_staging_data = ref([]); // Data array
        const currentPage = ref(1);
        const itemsPerPage = ref(10); // Items per page

        const startItem = computed(() => (currentPage.value - 1) * itemsPerPage.value + 1);
        const endItem = computed(() => Math.min(currentPage.value * itemsPerPage.value, totalItems.value));
        const totalItems = computed(() => onbint_staging_data.value.length);

        const unmatchedRecords = ref([]);
        const matchedRecords = ref([]);
        const uploadedFiles = ref([]);
        const matchedCount = ref(0);
        const processingTime = ref(0);



        const openModal = () => {
            isModalOpen.value = true;
        };


        const closeModal = () => {
            isModalOpen.value = false;
        };

        const handleFileChange = (event) => {
            file.value = event.target.files[0];
        };

        const saveExcelData = async () => {
            if (!file.value) {
                alert("Please select a file.");
                return;
            }

            const formData = new FormData();
            formData.append("file", file.value);
            formData.append("filename", file.value.name);

            // This will correctly print the filename

            try {
                openModal();
                const response = await axios.post(
                    "api/import_excel",
                    formData,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                        onUploadProgress: (progressEvent) => {
                            uploadProgress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        }
                    }
                );

                // Debugging: Check if response is received
                console.log("API response:", response.data);

                // Show the toast
                const toast = document.getElementById('toast-success');

                // Debugging: Check if toast element is found
                if (toast) {
                    console.log("Toast element found");

                    // Remove hidden class to display the toast
                    toast.classList.remove('hidden');
                    console.log("Toast is now visible");

                    // Optionally hide the toast after 10 seconds
                    setTimeout(() => {
                        toast.classList.add('hidden');
                        location.reload();
                    }, 50000); // 10 seconds delay
                } else {
                    console.error("Toast element not found");
                }
            } catch (error) {
                console.error("Error importing data:", error);
            }

        };

        const fetchTotalFilesCount = async () => {
            try {
                const response = await axios.get('api/uploaded-files'); // Replace with your API endpoint

                //output

                // Ensure the response contains the expected data
                totalUploadedRecords.value = response.data[0].uploaded_files;

            } catch (error) {
                console.error('Failed to fetch total files count:', error);
            }
        };

        const fetchUploadedFiles = async () => {
            try {
                const response = await axios.get('api/countUploadedFiles'); // Replace with your API endpoint
                uploadedFiles.value = response.data.data;
                console.log(response.data);
                totalFiles.value = response.data.count;
            } catch (error) {

            }
        }

        const getInvalidData = async () => {
            try {
                const response = await axios.get('api/getInvalidData'); // Replace with your actual API endpoint
                invalid_data.value = response.data;
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        const getOnbintStaging = async () => {
            try {
                const response = await axios.get('api/getOnbintStaging');
                const data = response.data;

                // Arrays to hold valid and invalid rows
                const validRows = [];
                const invalidRows = [];

                // Validate data fields before initializing DataTable
                data.forEach((row) => {
                    let isValidRow = true;

                    // Iterate over each field and validate it
                    Object.keys(row).forEach((field) => {
                        if (!isValidField(field, row[field])) {
                            // Mark the field as invalid by wrapping it in a span with a class
                            row[field] = `<span class="invalid-field">${row[field]}</span>`;
                            isValidRow = false; // Mark the row as invalid
                        }
                    });

                    // Separate valid and invalid rows
                    if (isValidRow) {
                        validRows.push(row);
                    } else {
                        invalidRows.push(row);
                    }
                });

                // Combine invalid rows first, then valid rows
                const sortedData = [...invalidRows, ...validRows];

                // Destroy any existing DataTable instance to avoid conflicts
                if ($.fn.DataTable.isDataTable('#example')) {
                    $('#example').DataTable().clear().destroy();
                }

                $('#example').DataTable({
                    retrieve: true,
                    data: sortedData,
                    ordering: false,
                    paging: true,
                    pageLength: 10,
                    columns: [
                        { data: 'RSBSASYSTEMGENERATEDNUMBER' },
                        { data: 'FIRSTNAME' },
                        { data: 'MIDDLENAME' },
                        { data: 'LASTNAME' },
                        { data: 'EXTENSIONNAME' },
                        { data: 'IDNUMBER' },
                        { data: 'GOVTIDTYPE' },
                        { data: 'STREETNO_PUROKNO' },
                        { data: 'BARANGAY' },
                        { data: 'CITYMUNICIPALITY' },
                        { data: 'DISTRICT' },
                        { data: 'PROVINCE' },
                        { data: 'REGION' },
                        { data: 'BIRTHDATE' },
                        { data: 'PLACEOFBIRTH' },
                        { data: 'MOBILENO' },
                        { data: 'SEX' },
                        { data: 'NATIONALITY' },
                        { data: 'PROFESSION' },
                        { data: 'SOURCEOFFUNDS' },
                        { data: 'MOTHERMAIDENNAME' },

                    ],
                    // Adjust the width of the first column
                    columnDefs: [
                        { width: '190px', targets: 0 }, // Adjust the width of the first column (150px as an example)
                    ],
                    autoWidth: false, // Disable automatic width calculation to respect custom widths
                    // Add some styling for invalid fields
                    createdRow: function (row, data, dataIndex) {
                        $(row).find('.invalid-field').css('color', 'red');
                    },
                });

            } catch (error) {
                console.error('Error fetching data:', error);
            }
        };


        const checkDataMatches = async () => {
            try {
                const response = await axios.get('/api/checkDataMatches'); // Your API endpoint
                const data = response.data;

                // Set the unmatched records
                unmatchedRecords.value = data.unmatched_records.map(record => record.onbint_model);
                matchedRecords.value = data.matched_records.map(record => record.onbint_model);

                // Initialize DataTable after data is set
            } catch (error) {
                console.error('Failed to fetch data:', error);
            }


        };






        // Validation function to check fields based on rules
        const isValidField = (fieldName, field) => {
            // Ensure field is treated as a string
            const fieldStr = String(field || '').trim();

            if (fieldStr === '') {
                return false; // Check for null, undefined, or empty string
            }

            switch (fieldName) {
                case 'FIRSTNAME':
                case 'MIDDLENAME':
                case 'LASTNAME':
                    return /^[a-zA-Z\s]+$/.test(fieldStr) && fieldStr.length >= 2; // Only letters and spaces, and at least 2 letters

                case 'BIRTHDATE':
                    return /^\d{4}-\d{2}-\d{2}$/.test(fieldStr); // Date format yyyy-mm-dd

                case 'MOBILENO':
                    return /^\d{11}$/.test(fieldStr); // Exactly 11 digits

                default:
                    return true;
            }
        };




        const onPageChange = async (page) => {
            currentPage.value = page;
            // Fetch data for the new page  
            getOnbintStaging();
        }

        const displayedItems = computed(() => {
            const start = (currentPage.value - 1) * itemsPerPage.value;
            const end = start + itemsPerPage.value;
            return onbint_staging_data.value.slice(start, end);
        });



        // Function to return the class for a field
        const getFieldClass = (fieldName, field) => {
            return !isValidField(fieldName, field) ? 'bg-red-500 text-white' : ''; // Apply red background if invalid
        };

        // Function to determine the row class (optional)
        const getRowClass = (item) => {
            // Example: if the item has any invalid field, make the row background red
            return Object.keys(item).some((key) => !isValidField(key, item[key]))
                ? 'bg-red-100' // Apply light red background if any field is invalid
                : '';
        };


        // Computed property to format the total files count
        const formattedTotalRecords = computed(() => totalUploadedRecords.value);
        const formattedTotalFiles = computed(() => totalFiles.value.toLocaleString());

        // Fetch data when the component is mounted
        onMounted(() => {
            fetchTotalFilesCount();
            fetchUploadedFiles();
            getInvalidData();
            getOnbintStaging();

        });

        return {
            exampleTable,
            file,
            invalid_data,
            onbint_staging_data,
            mainForm,
            handleFileChange,
            saveExcelData,
            modal_upload,
            isModalOpen,
            openModal,
            closeModal,
            uploadProgress,
            formattedTotalRecords,
            formattedTotalFiles,
            fetchUploadedFiles,
            currentPage,
            itemsPerPage,
            getFieldClass,
            getRowClass,
            onPageChange,
            displayedItems,
            startItem,
            endItem,
            totalItems,
            checkDataMatches,
            unmatchedRecords,
            matchedRecords,
            uploadedFiles
        };
    },
};  
</script>

<style scoped>
table.dataTable th:nth-child(1) {
    width: 20px;
    max-width: 20px;
    word-break: break-all;
    white-space: pre-line;
}

table.dataTable td:nth-child(1) {
    width: 20px;
    max-width: 20px;
    word-break: break-all;
    white-space: pre-line;
}

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

.bg-red-500 {
    background-color: red;
    color: white;
    /* Optional: for better text visibility */
}

.bg-red-100 {
    background-color: #fee2e2;
    /* A lighter shade of red */
}

thead {
    display: table-header-group;
    /* Keeps header fixed */
}

tbody {
    display: block;
    /* Makes the body scrollable */
    max-height: 500px;
    /* Adjust based on your needs */
    overflow-y: auto;
    /* Allows vertical scrolling */
}

tr {
    display: table;
    /* Keeps rows as table-row */
    width: 100%;
    /* Ensures rows are full width */
    table-layout: fixed;
    /* Prevents uneven columns */
}

th,
td {
    width: auto;
    /* Ensures table cells don't collapse */
}
</style>
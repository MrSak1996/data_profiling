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
            totalDuplicateData_staging: 0,
            duplicates: [],
            progressBar: 0,
            isDropdownOpen: false
        }
    },
    methods: {
        async getDuplicateDataFarmersReg() {
            try {
                this.$refs.progressBar.startProgress();
                const id = this.$route.query.id; // Get the 'id' from the URL query parameters

                const response = await axios.get(`api/findDuplicates?id=${id}`, {
                    onDownloadProgress: (progressEvent) => {
                        if (progressEvent.lengthComputable) {
                            const progress = Math.floor(
                                (progressEvent.loaded / progressEvent.total) * 100
                            );
                            this.$refs.progressBar.setProgress(progress);
                        }
                    },
                });

                const data = response.data;
                const dup_data = response.data;
                console.log(dup_data);

                if (!data || data.length === 0) {
                    console.error('No records found.');
                    this.$refs.progressBar.completeProgress(); // Ensure progress bar is completed
                    return;
                }

                this.duplicates = data;
                this.totalDuplicateData_staging = data.length;

                // Check if DataTable exists and clear it
                if ($.fn.DataTable.isDataTable("#dedup_table")) {
                    $("#dedup_table").DataTable().clear().destroy();
                }

                // Initialize DataTable with new data
                const matchingTable = $("#dedup_table").DataTable({
                    data: this.duplicates,
                    columns: this.getTableColumns(),
                    pageLength: 10,
                    columnDefs: [{ width: "10px", targets: 0 }],
                    autoWidth: false,
                    createdRow: function (row, data) {
                        $(row).find(".invalid-field").css("color", "red");
                    },
                });

                this.setupDetailRowToggle(matchingTable, "#dedup_table");
                this.$refs.progressBar.completeProgress();
            } catch (error) {
                console.error('Failed to fetch data:', error);
                this.$refs.progressBar.completeProgress(); // Ensure progress bar is completed on error
            }
        },
        async exportDedup() {
            try {
                const id = this.$route.query.id; // Get the 'id' from the URL query parameters
                window.location.href = `api/findDuplicates?id=${id}&export=true`;
                 
            } catch (error) {
            }
        },

        getTableColumns() {
            return [
                {
                    className: "details-control text-center",
                    orderable: false,
                    data: null,
                    defaultContent: `<button type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="15" height="15" fill="currentColor">
                    <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/>
                </svg>
            </button>`,
                },
                { data: "RSBSASYSTEMGENERATEDNUMBER" },
                { data: "FIRSTNAME" },
                { data: "MIDDLENAME" },
                { data: "LASTNAME" },
                { data: "SEX" },
                { data: "BIRTHDATE" },
                { data: "STREETNO_PUROKNO" },
                { data: "BARANGAY" },
                { data: "CITYMUNICIPALITY" },
                { data: "PROVINCE" },
                { data: "REGION" },
                // { data: "REGION", visible: false },
                // { data: "BIRTHDATE", visible: false },
                // { data: "PLACEOFBIRTH", visible: false },
                // { data: "MOBILENO", visible: false },
                // { data: "SEX", visible: false },
                // { data: "NATIONALITY", visible: false },
                // { data: "PROFESSION", visible: false },
                // { data: "SOURCEOFFUNDS", visible: false },
                // { data: "MOTHERMAIDENNAME", visible: false },
            ];
        },

        setupDetailRowToggle(table, tableId) {
            $(`${tableId} tbody`).on("click", "td.details-control", (event) => {
                const tr = $(event.currentTarget).closest("tr");
                const row = table.row(tr);
                const tdf = tr.find("td:first");

                tdf.html(""); // Clear content of first cell

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tdf.append(this.getDetailButtonHTML());
                    tr.removeClass("shown");
                } else {
                    // Open this row
                    row.child(this.format(row.data())).show();
                    tdf.append(this.getDetailButtonHTML(true));
                    tr.addClass("shown");
                    row.child().css("background-color", "#b4b4b4");
                }
            });
        },

        getDetailButtonHTML(isShown = false) {
            return `
        <button type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="15" height="15" fill="currentColor">
                <path d="${isShown ? 'M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z' : 'M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z'}"/>
            </svg>
        </button>`;
        },

        format(data) {
            let tb = '<table class="border-t text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">';
            tb += '<thead class="text-xs text-gray-700 uppercase ">';
            tb += '<tr>';
            tb += '<th class="px-4 py-2"><b>Details (' + data.RSBSASYSTEMGENERATEDNUMBER + ')</b></th>'; // Changed to 'Details' to reflect the new content
            tb += '</tr></thead><tbody>';

            // Iterate through the duplicates array
            data.duplicates.forEach((item, index) => {
                tb += '<tr>'; // Start a new row for each duplicate

                // Populate the table cells with the details of each duplicate
                tb += `<td class="px-4 py-2">
            <p>Name: <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
            ${item.FIRSTNAME} ${item.MIDDLENAME} ${item.LASTNAME}</span></p>
        </td>`;

                tb += `<td class="px-4 py-2">
            <p>Sex: <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
            ${item.SEX}</span></p>
        </td>`;

                tb += `<td class="px-4 py-2">
            <p>Birthdate: <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
            ${item.BIRTHDATE}</span></p>
        </td>`;





                tb += `<td class="px-4 py-2">
            <p>Similarity: <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
            ${item.similarity}</span></p>
        </td>`;

                tb += '</tr>'; // Close the current row
            });

            tb += '</tbody></table>'; // Close the table
            tb += '<table>';
            tb += '<thead class="text-xs text-gray-700 uppercase ">';
            tb += '<tr>';
            tb += '<th class="px-4 py-2" colspan=4><b>Complete Address (' + data.RSBSASYSTEMGENERATEDNUMBER + ')</b></th>'; // Changed to 'Details' to reflect the new content
            tb += '</tr></thead>';
            tb += '<tbody>';
            data.duplicates.forEach((item, index) => {
                tb += '<tr>';
                tb += `<td class="px-4 py-2">
            <p>STREET/PUROK NO: <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
            ${item.STREETNO_PUROKNO}</span></p>
        </td>`;
                tb += `<td class="px-4 py-2">
            <p>BARANGAY: <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
            ${item.BARANGAY}</span></p>
        </td>`;
                tb += `<td class="px-4 py-2">
            <p>CITY/MUNICIPALITY: <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
            ${item.CITYMUNICIPALITY}</span></p>
        </td>`;
                tb += `<td class="px-4 py-2">
            <p>PROVINCE: <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
            ${item.PROVINCE}</span></p>
        </td>`;
                tb += `<td class="px-4 py-2">
            <p>REGION: <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
            ${item.REGION}</span></p>
        </td>`;
                tb += '</tr>';

            });
            tb += '</tbody>';
            tb += '</table>';

            return tb;
        },


        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
        },
    }
}
</script>
<template>
    <div v-if="activeTab === 'duplicate'"
        class="w-full h-auto p-4 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="relative overflow-x-auto">
            <div class="flex justify-start space-x-2">

                <button type="button" @click="getDuplicateDataFarmersReg"
                    class="flex items-center text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Run Data Duplication
                </button>
                <div class="relative inline-block text-left">
                    <!-- Main Button with dropdown icon -->
                    <button type="button" @click="exportDedup"
                        class="inline-flex items-center text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-l-full text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m-6 3.75 3 3m0 0 3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75" />
                        </svg>
                        Download
                    </button>

                    <!-- Dropdown toggle button -->
                    <button @click="toggleDropdown"
                        class="inline-flex items-center px-3 py-2.5 text-white bg-green-700 hover:bg-green-800 rounded-r-full focus:outline-none dark:bg-green-600 dark:hover:bg-green-700">
                        <svg class="w-4 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1l4 4 4-4" />
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div v-if="isDropdownOpen"
                        class="absolute right-0 z-10 mt-2 w-44 bg-white rounded-lg shadow divide-y divide-gray-100 dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                            <li>
                                <a @click="exportDedup" href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Export
                                    Data Duplication</a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>

            <ProgressBar ref="progressBar" />


            <div class="grid lg:grid-cols-2 md:grid-cols-2 gap-6 w-full  mt-4">
                <div class="flex items-center p-4 bg-gray-100 rounded">
                    <div class="flex flex-shrink-0 items-center justify-center bg-green-200 h-16 w-16 rounded">

                        <svg class="w-6 h-6 fill-current text-green-700" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 576 512">
                            <path
                                d="M96 80c0-26.5 21.5-48 48-48l288 0c26.5 0 48 21.5 48 48l0 304L96 384 96 80zm313 47c-9.4-9.4-24.6-9.4-33.9 0l-111 111-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l64 64c9.4 9.4 24.6 9.4 33.9 0L409 161c9.4-9.4 9.4-24.6 0-33.9zM0 336c0-26.5 21.5-48 48-48l16 0 0 128 448 0 0-128 16 0c26.5 0 48 21.5 48 48l0 96c0 26.5-21.5 48-48 48L48 480c-26.5 0-48-21.5-48-48l0-96z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex-grow flex flex-col ml-4">
                        <span class="text-xl font-bold">{{ totalDuplicateData_staging }}</span>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Total Duplicate Data on Staging</span>
                        </div>
                        <button type="button"
                            class="text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-xs px-2 py-1.5 text-center me-2 mb-2">View
                            more</button>

                    </div>
                </div>

                <!-- <div class="flex items-center p-4 bg-gray-100 rounded">
                    <div class="flex flex-shrink-0 items-center justify-center bg-green-200 h-16 w-16 rounded">

                        <svg class="w-6 h-6 fill-current text-green-700" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 576 512">
                            <path
                                d="M96 80c0-26.5 21.5-48 48-48l288 0c26.5 0 48 21.5 48 48l0 304L96 384 96 80zm313 47c-9.4-9.4-24.6-9.4-33.9 0l-111 111-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l64 64c9.4 9.4 24.6 9.4 33.9 0L409 161c9.4-9.4 9.4-24.6 0-33.9zM0 336c0-26.5 21.5-48 48-48l16 0 0 128 448 0 0-128 16 0c26.5 0 48 21.5 48 48l0 96c0 26.5-21.5 48-48 48L48 480c-26.5 0-48-21.5-48-48l0-96z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex-grow flex flex-col ml-4">
                        <span class="text-xl font-bold"></span>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Total Duplicate Data on Server</span>
                        </div>
                        <button type="button"
                            class="text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-xs px-2 py-1.5 text-center me-2 mb-2">View
                            more</button>
                    </div>
                </div> -->
            </div>

            <!-- duplicate data -->
            <div style="overflow-x: auto; max-width: 100%;">
                <div class="relative shadow-md sm:rounded-lg p-2">
                    <table id="dedup_table"
                        class="border-t w-32 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th style="width:5%;"></th>
                                <th class="px-4 py-2 text-left">RSBSA Number</th>
                                <th class="px-4 py-2 text-left">First Name</th>
                                <th class="px-4 py-2 text-left">Middle Name</th>
                                <th class="px-4 py-2 text-left">Last Name</th>
                                <th class="px-4 py-2 text-left">Sex</th>
                                <th class="px-4 py-2 text-left">Birthdate</th>
                                <th class="px-4 py-2 text-left">Street/Purok No.</th>
                                <th class="px-4 py-2 text-left">Barangay</th>
                                <th class="px-4 py-2 text-left">City/Municipality</th>
                                <th class="px-4 py-2 text-left">Province</th>
                                <th class="px-4 py-2 text-left">Region</th>
                                <!-- <th class="hidden text-left">Duplicate(s)</th -->
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <!--         
                    <tr v-for="(duplicate, index) in duplicates" :key="index"
                                :class="index % 2 === 0 ? 'bg-gray-100' : 'bg-gray-300'">
                                <td>{{ duplicate.RSBSASYSTEMGENERATEDNUMBER }}</td>
                                <td>{{ duplicate.FIRSTNAME }}</td>
                                <td>{{ duplicate.MIDDLENAME }}</td>
                                <td>{{ duplicate.LASTNAME }}</td>
                                <td>{{ duplicate.SEX }}</td>
                                <td>{{ duplicate.BIRTHDATE }}</td>
                                <td>{{ duplicate.STREETNO_PUROKNO }}</td>
                                <td>{{ duplicate.BARANGAY }}</td>
                                <td>{{ duplicate.CITYMUNICIPALITY }}</td>
                                <td>{{ duplicate.DISTRICT }}</td>
                                <td>{{ duplicate.PROVINCE }}</td>
                                <td>{{ duplicate.REGION }}</td>

                                
                            </tr>                    
                        </tbody>
                    </table> -->
                </div>
            </div>





        </div>
    </div>
</template>
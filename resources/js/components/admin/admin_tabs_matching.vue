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
            unmatched_data: [],
            matched_data: [],
            totalmatchData: 0,
            totalUnmatchData: 0,
            progressBar: null,
            isDropdownOpen: false,

        };
    },
    methods: {
        async initMatchDataTable() {
            try {
                this.$refs.progressBar.startProgress();
                const id = this.$route.query.id; // Get the 'id' from the URL query parameters
                
                const response = await axios.get(`api/getMatchData?id=${id}`, {
                    onDownloadProgress: (progressEvent) => {
                        if (progressEvent.lengthComputable) {
                            const progress = Math.floor(
                                (progressEvent.loaded / progressEvent.total) * 100
                            );
                            this.$refs.progressBar.setProgress(progress);
                        }
                    },
                });

                // Assume your API response structure has 'matched' and 'unmatched' arrays
                this.matched_data = response.data.data; // Adjust based on your API response
                this.unmatched_data = response.data.unmatched; // Adjust based on your API response
                this.totalmatchData = response.data.count;
                this.totalUnmatchData = response.data.countUnmatched;

                this.$refs.progressBar.completeProgress();

                // Initialize matching table
                if ($.fn.DataTable.isDataTable("#matching_table")) {
                    $("#matching_table").DataTable().clear().destroy();
                }
                const matchingTable = $("#matching_table").DataTable({
                    retrieve: true,
                    data: this.matched_data, // Populate with matched data
                    pageLength: 10,
                    columns: this.getTableColumns(), // Use a function to define columns
                    columnDefs: [{ width: "10px", targets: 0 }],
                    autoWidth: false,
                    createdRow: function (row, data) {
                        $(row).find(".invalid-field").css("color", "red");
                    },
                });

                // Initialize unmatching table
                if ($.fn.DataTable.isDataTable("#unmatching_table")) {
                    $("#unmatching_table").DataTable().clear().destroy();
                }
                const unmatchingTable = $("#unmatching_table").DataTable({
                    retrieve: true,
                    data: this.unmatched_data, // Populate with unmatched data
                    pageLength: 10,
                    columns: this.getTableColumns(), // Use the same function for columns
                    columnDefs: [{ width: "10px", targets: 0 }],
                    autoWidth: false,
                    createdRow: function (row, data) {
                        $(row).find(".invalid-field").css("color", "red");
                    },
                });

                // Event handlers for both tables
                this.setupDetailRowToggle(matchingTable, "#matching_table");
                this.setupDetailRowToggle(unmatchingTable, "#unmatching_table");

            } catch (error) {
                console.error("Error fetching data:", error);
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
                { data: "EXTENSIONNAME" },
                { data: "IDNUMBER" },
                { data: "GOVTIDTYPE" },
                { data: "STREETNO_PUROKNO" },
                { data: "BARANGAY" },
                { data: "CITYMUNICIPALITY" },
                { data: "DISTRICT" },
                { data: "PROVINCE" },
                { data: "REGION", visible: false },
                { data: "BIRTHDATE", visible: false },
                { data: "PLACEOFBIRTH", visible: false },
                { data: "MOBILENO", visible: false },
                { data: "SEX", visible: false },
                { data: "NATIONALITY", visible: false },
                { data: "PROFESSION", visible: false },
                { data: "SOURCEOFFUNDS", visible: false },
                { data: "MOTHERMAIDENNAME", visible: false },
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
            tb += '<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">';
            tb += '<tr>';
            tb += '<td class="px-4 py-2"><b>Birth Date</b></td>';
            tb += '<td class="px-4 py-2"><b>Place of Birth</b></td>';
            tb += '<td class="px-4 py-2"><b>Mobile No.</b></td>';
            tb += '<td class="px-4 py-2"><b>Sex</b></td>';
            tb += '<td class="px-4 py-2"><b>Nationality</b></td>';
            tb += '<td class="px-4 py-2"><b>Profession</b></td>';
            tb += '<td class="px-4 py-2"><b>Source of Funds</b></td>';
            tb += '<td class="px-4 py-2"><b>Mother Maiden Name</b></td>';
            tb += "</tr></thead><tbody><tr>";
            tb += '<td>' + data.BIRTHDATE + '</td>';
            tb += '<td>' + data.PLACEOFBIRTH + '</td>';
            tb += '<td>' + data.MOBILENO + '</td>';
            tb += '<td>' + data.SEX + '</td>';
            tb += '<td>' + data.NATIONALITY + '</td>';
            tb += '<td>' + data.PROFESSION + '</td>';
            tb += '<td>' + data.SOURCEOFFUNDS + '</td>';
            tb += '<td>' + data.MOTHERMAIDENNAME + '</td>';
            tb += "</tr></tbody></table>";
            return tb;
        },


        async exportUnmatch() {
            try {
                const id = this.$route.query.id; // Get the 'id' from the URL query parameters

                window.location.href = `./api/exportUnmatch?id=${id}?export=true`;

                // this.$refs.progressBar.startProgress();
                // const response = await axios.get(`api/exportUnmatch?id=${id}`,{
                //     onDownloadProgress: (progressEvent) => {
                //         if (progressEvent.lengthComputable) {
                //             const progress = Math.floor(
                //                 (progressEvent.loaded / progressEvent.total) * 100
                //             );
                //             this.$refs.progressBar.setProgress(progress);
                //         }
                //     },
                // },

                // {
                //     responseType: 'blob',
                // });

                // const url = window.URL.createObjectURL(new Blob([response.data]));
                // const link = document.createElement('a');
                // link.href = url;
                // link.setAttribute('download', 'unmatched_data.csv');
                // document.body.appendChild(link);
                // link.click();
                // document.body.removeChild(link);

                // this.$refs.progressBar.completeProgress();
            } catch (error) {
                console.error('Error exporting data:', error);
            }
        },

        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
        },
    },
    mounted() {
    },
};
</script>
<template>
    <div v-if="activeTab === 'unmatched'"
        class="w-full h-auto p-4 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="relative overflow-x-auto">
            <div class="flex justify-start space-x-2">
                <button type="button" @click="initMatchDataTable"
                    class="flex items-center text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Run Data Matches
                </button>
                <ProgressBar ref="progressBar" />
                <div class="relative inline-block text-left">
                    <!-- Main Button with dropdown icon -->
                    <button type="button" @click="exportUnmatch"
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
                                <a @click="exportUnmatch" href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Export
                                    Unmatched Data</a>
                            </li>
                        
                        </ul>
                    </div>
                </div>


            </div>
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
                        <span class="text-xl font-bold">{{ totalmatchData }}</span>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Total Match Data</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-gray-100 rounded">
                    <div class="flex flex-shrink-0 items-center justify-center bg-green-200 h-16 w-16 rounded">
                        <svg class="w-6 h-6 fill-current text-green-700" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex-grow flex flex-col ml-4">
                        <span class="text-xl font-bold">{{ totalUnmatchData }}</span>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Total No. of Unmatch Data</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- match data -->
            <div style="overflow-x: auto; max-width: 100%;">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
                    <h1>Matched Data</h1>
                    <table id="matching_table"
                        class="border-t w-32 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th style="width:5%;"></th>
                                <th class="px-4 py-2 w-32">RSBSA #</th>
                                <th class="px-4 py-2">First Name</th>
                                <th class="px-4 py-2">Middle Name</th>
                                <th class="px-4 py-2">Last Name</th>
                                <th class="px-4 py-2">Extension Name</th>
                                <th class="px-4 py-2">ID Number</th>
                                <th class="px-4 py-2">Government ID Type</th>
                                <th class="px-4 py-2">Street No. / Purok No.</th>
                                <th class="px-4 py-2">Barangay</th>
                                <th class="px-4 py-2">City/Municipality</th>
                                <th class="px-4 py-2">District</th>
                                <th class="px-4 py-2">Province</th>
                                <th class="px-4 py-2">Region</th>
                                <th class="hidden px-4 py-2">Birth Date</th>
                                <th class="hidden px-4 py-2">Place of Birth</th>
                                <th class="hidden px-4 py-2">Mobile No.</th>
                                <th class="hidden px-4 py-2">Sex</th>
                                <th class="hidden px-4 py-2">Nationality</th>
                                <th class="hidden px-4 py-2">Profession</th>
                                <th class="hidden px-4 py-2">Source of Funds</th>
                                <th class="hidden px-4 py-2">Mother Mainden Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr v-for="item in paginatedMatchData" :key="item.id" -->
                            <!-- <tr v-for="item in paginatedMatchData" :key="item.id"
                                class="bg-white dark:bg-gray-800 border-b">
                                <td class="px-4 py-2">{{ item.rsbsa_no }}</td>
                                <td class="px-4 py-2">{{ item.first_name }}</td>
                                <td class="px-4 py-2">{{ item.last_name }}</td>
                                <td class="px-4 py-2">{{ item.middle_name }}</td>
                                <td class="px-4 py-2">{{ item.mm_name }}</td>
                                <td class="px-4 py-2">{{ item.sex }}</td>
                                <td class="px-4 py-2">{{ item.birth_date }}</td>
                                <td class="px-4 py-2">{{ item.PLACEOFBIRTH }}</td>
                                <td class="px-4 py-2">{{ item.mobile_no }}</td>
                                <td class="px-4 py-2">{{ item.streetno }}</td>
                                <td class="px-4 py-2">{{ item.brgy }}</td>
                                <td class="px-4 py-2">{{ item.cm }}</td>
                                <td class="px-4 py-2">{{ item.district }}</td>
                                <td class="px-4 py-2">{{ item.province }}</td>
                                <td class="px-4 py-2">{{ item.REGION }}</td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- unmatch data -->
            <div style="overflow-x: auto; max-width: 100%;">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    Unmatched Data
                    <table id="unmatching_table"
                        class="border-t w-32 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th style="width:5%;"></th>
                                <th class="px-4 py-2 w-32">RSBSA #</th>
                                <th class="px-4 py-2">First Name</th>
                                <th class="px-4 py-2">Middle Name</th>
                                <th class="px-4 py-2">Last Name</th>
                                <th class="px-4 py-2">Extension Name</th>
                                <th class="px-4 py-2">ID Number</th>
                                <th class="px-4 py-2">Government ID Type</th>
                                <th class="px-4 py-2">Street No. / Purok No.</th>
                                <th class="px-4 py-2">Barangay</th>
                                <th class="px-4 py-2">City/Municipality</th>
                                <th class="px-4 py-2">District</th>
                                <th class="px-4 py-2">Province</th>
                                <th class="px-4 py-2">Region</th>
                                <th class="hidden px-4 py-2">Birth Date</th>
                                <th class="hidden px-4 py-2">Place of Birth</th>
                                <th class="hidden px-4 py-2">Mobile No.</th>
                                <th class="hidden px-4 py-2">Sex</th>
                                <th class="hidden px-4 py-2">Nationality</th>
                                <th class="hidden px-4 py-2">Profession</th>
                                <th class="hidden px-4 py-2">Source of Funds</th>
                                <th class="hidden px-4 py-2">Mother Mainden Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr v-for="item in paginatedData" :key="item.id" class="bg-white dark:bg-gray-800 border-b">
                                <td class="px-4 py-2">{{ item.RSBSASYSTEMGENERATEDNUMBER }}</td>
                                <td class="px-4 py-2">{{ item.FIRSTNAME }}</td>
                                <td class="px-4 py-2">{{ item.LASTNAME }}</td>
                                <td class="px-4 py-2">{{ item.MIDDLENAME }}</td>
                                <td class="px-4 py-2">{{ item.MOTHERMAIDENNAME }}</td>
                                <td class="px-4 py-2">{{ item.SEX }}</td>
                                <td class="px-4 py-2">{{ item.BIRTHDATE }}</td>
                                <td class="px-4 py-2">{{ item.PLACEOFBIRTH }}</td>
                                <td class="px-4 py-2">{{ item.MOBILENO }}</td>
                                <td class="px-4 py-2">{{ item.STREETNO_PUROKNO }}</td>
                                <td class="px-4 py-2">{{ item.BARANGAY }}</td>
                                <td class="px-4 py-2">{{ item.CITYMUNICIPALITY }}</td>
                                <td class="px-4 py-2">{{ item.DISTRICT }}</td>
                                <td class="px-4 py-2">{{ item.PROVINCE }}</td>
                                <td class="px-4 py-2">{{ item.REGION }}</td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>





        </div>
    </div>
</template>
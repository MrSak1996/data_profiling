<template>
    <div v-if="activeTab === 'total'"
        class="flex flex-col items-start h-auto p-4 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <button type="button" @click="getOnbintStaging"
            class="flex items-center text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Show Data
        </button>
        <ProgressBar ref="progressBar" />

        <div>
            <table id="example"
                class="border-t w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
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
                    <!-- Table content -->
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import ProgressBar from "../progressBar.vue";
import toast from "../toast.vue";

export default {
    components: {
        ProgressBar,
        toast,
    },
    props: ["activeTab"],
    data() {
        return {
            file: null,
            progressBar: null,
        };
    },
    methods: {
        async getOnbintStaging() {
            try {

                const id = this.$route.query.id; // Get the 'id' from the URL query parameters
                console.log(id);
                this.$refs.progressBar.startProgress();
                const response = await axios.get(`api/getOnbintStaging?id=${id}`, {
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

                // Arrays to hold valid and invalid rows
                const validRows = [];
                const invalidRows = [];

                // Validate data fields before initializing DataTable
                data.forEach((row) => {
                    let isValidRow = true;

                    // Iterate over each field and validate it
                    Object.keys(row).forEach((field) => {
                        if (!this.isValidField(field, row[field])) {
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

                const sortedData = [...invalidRows, ...validRows];

                if ($.fn.DataTable.isDataTable("#example")) {
                    $("#example").DataTable().clear().destroy();
                }
                this.$refs.progressBar.completeProgress();

                // Main DataTable initialization
                const table = $("#example").DataTable({
                    retrieve: true,
                    data: sortedData,

                    pageLength: 10,
                    columns: [
                        {
                            className: "details-control text-center",
                            orderable: false,
                            data: null,
                            defaultContent: `
                            <button type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="15" height="15" fill="currentColor">
                                    <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/>
                                </svg>
                            </button>
                        `,
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
                    ],
                    columnDefs: [{ width: "10px", targets: 0 }],
                    autoWidth: false,
                    createdRow: function (row, data, dataIndex) {
                        $(row).find(".invalid-field").css("color", "red");
                    },
                });

                // Move the event handler setup here, after initializing the DataTable
                $("#example tbody").on("click", "td.details-control", (event) => {
                    const tr = $(event.currentTarget).closest("tr");
                    const row = table.row(tr);
                    const tdf = tr.find("td:first");

                    tdf.html(""); // Clear content of first cell

                    if (row.child.isShown()) {
                        // This row is already open - close it
                        row.child.hide();

                        tdf.append(
                            ` <button type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="15" height="15" fill="currentColor">
                                    <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/>
                                </svg>
                            </button>`
                        );


                        tr.removeClass("shown");
                    } else {
                        // Open this row
                        row.child(this.format(row.data())).show();
                        tdf.append(
                            ` <button type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="15" height="15" fill="currentColor">
                                <path d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z"/>
                            </svg>
                            </button>`
                        );
                        tr.addClass("shown");
                        row.child().css("background-color", "#b4b4b4");
                    }
                });

            } catch (error) {
                console.error("Error fetching data:", error);
            }
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
            tb += "</tr>";
            tb += "<tr>";
            tb += '<td>' + data.BIRTHDATE + '</td>'; // Populate with data as needed
            tb += '<td>' + data.PLACEOFBIRTH + '</td>';
            tb += '<td>' + data.MOBILENO + '</td>';
            tb += '<td>' + data.SEX + '</td>';
            tb += '<td>' + data.NATIONALITY + '</td>';
            tb += '<td>' + data.PROFESSION + '</td>';
            tb += '<td>' + data.SOURCEOFFUNDS + '</td>';
            tb += '<td>' + data.MOTHERMAIDENNAME + '</td>';
            tb += "</tr>";
            tb += "</th>";
            return tb;
        },



        isValidField(field, value) {
            const alphanumericFields = ["FIRSTNAME", "MIDDLENAME", "LASTNAME"];
            const regex = /^[a-zA-Z\s]*$/; // Only letters and spaces
            if (alphanumericFields.includes(field)) {
                return regex.test(value); // Return true if value passes regex
            }
            return true; // Default: consider field valid if no rule applies
        },
    },
};
</script>

<style scoped>
/* Add any specific styles here if necessary */
</style>

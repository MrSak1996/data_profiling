<template>
    <div>
        <dash_main></dash_main>
    </div>
    
</template>

<script>
import dash_main from './admin/index.vue';
    import { ref, onMounted, computed,reactive } from 'vue';
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
            dash_main,
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
            const invalidData = ref([]);
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
            const onbintInvalid = ref([]);
            const invalid_data = ref([]);
            const matchedCount = ref(0);
            const processingTime = ref(0);
            const openModal = () => {
                isModalOpen.value = true;
            };


            const closeModal = () => {
                isModalOpen.value = false;
            };

       
            
            

           

        

            const getInvalidData = async () => {
                try {
                    const response = await axios.get('api/getInvalidData'); // Replace with your actual API endpoint
                    if(response.data.null_values == 0)
                {
                    invalidData.value = response.data;

                }else{
                    invalidData.value = response.data[0];

                }
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            }

           

            // const checkDataMatches = async () => {
            //     try {
            //         const response = await axios.get('/api/checkDataMatches'); // Your API endpoint
            //         const data = response.data;

            //         // Set the unmatched records
            //         unmatchedRecords.value = data.unmatched_records.map(record => record.onbint_model);
            //         matchedRecords.value = data.matched_records.map(record => record.onbint_model);

            //         // Initialize DataTable after data is set
            //     } catch (error) {
            //         console.error('Failed to fetch data:', error);
            //     }


            // };

            

            const getOnbintInvalid = async () => {
                try{
                    const response = await axios.get('api/getOnbintInvalid');
                    onbintInvalid.value = response.data;
                }catch(error)
                {
                    console.log('Error fetching data:',error)
                }
            }




            const onPageChange = async (page) => {
                currentPage.value = page;
                // Fetch data for the new page  
                // getOnbintStaging();
            }

            const displayedItems = computed(() => {
                const start = (currentPage.value - 1) * itemsPerPage.value;
                const end = start + itemsPerPage.value;
                return onbint_staging_data.value.slice(start, end);
            });



          

            // Computed property to format the total files count
            const formattedTotalRecords = computed(() => totalUploadedRecords.value);
            const formattedTotalFiles = computed(() => totalFiles.value.toLocaleString());

            // Fetch data when the component is mounted
            onMounted(() => {
           
                // getInvalidData();
                getOnbintInvalid();



            });

            return {
                exampleTable,
                file,
                invalid_data,
                onbint_staging_data,
                mainForm,
                modal_upload,
                isModalOpen,
                openModal,
                closeModal,
                uploadProgress,
                formattedTotalRecords,
                formattedTotalFiles,
                currentPage,
                itemsPerPage,
                getOnbintInvalid,
                onPageChange,
                displayedItems,
                startItem,
                endItem,
                totalItems,
                unmatchedRecords,
                onbintInvalid,
                invalidData,
                matchedRecords,
                uploadedFiles,
            };
        },
    };  
</script>

<style scoped>
.max-w-xs {
    max-width: 15rem !important;
}
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
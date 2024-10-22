<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        @click.self="closeModal">
        <form @submit.prevent="addRoles">
            <div class="relative p-4 w-full max-w-2xl bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Roles & Programs
                    </h3>
                    <button type="button" @click="closeModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-6">
                        <div class="relative">
                            <label for="programs" class="block text-sm font-medium text-gray-700">Programs</label>

                            <!-- Custom select dropdown -->
                            <div @click="toggleDropdown"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                                <span>{{ selectedPrograms?.program_title || 'Select Agency' }}</span>
                                <span class="float-right">▼</span>
                            </div>

                            <!-- Dropdown with search bar and scrollable options -->
                            <div v-if="showProgramsDropdown"
                                class="absolute w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg z-20">
                                <!-- Search input -->
                                <input v-model="searchQuery" type="text" placeholder="Search programs..."
                                    class="w-full px-4 py-2 border-b border-gray-300 focus:outline-none" />

                                <!-- Scrollable dropdown list -->
                                <ul class="max-h-48 overflow-y-auto">
                                    <li v-for="program in filteredPrograms" :key="program.id"
                                        @click="selectPrograms(program)" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                        {{ program.program_title }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <label for="user_role" class="block text-sm font-medium text-gray-700">Roles</label>
                            <select id="user_role"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="1">Super Admin</option>
                                <option value="2">Regional Admin</option>
                                <option value="3">User Validator</option>
                            </select>
                        </div>
                        <hr>


                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-white uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr style="background-color:#046C4E;">
                                        <th scope="col" class="px-6 py-3">
                                            Role
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Program
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Date Created
                                        </th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in data" :key="item.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ item.user_role }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ item.program_title }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{item.created_at}}
                                        </td>
                                       
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                        Save
                    </button>
                    <button type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                        @click="closeModal">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
        <event-toast ref="toast"></event-toast>

    </div>
</template>

<script>
import EventToast from "../EventToast.vue";
export default {
    data() {
        return {
            searchQuery: '',
            selected_id: '',
            data:[],
        };
    },
    components:{
        EventToast
    },
    props: {
        show: Boolean,
        user_id:Number,
        selectedPrograms: Object,
        programs: Array,
        showProgramsDropdown: Boolean,
    },
    computed: {
        filteredPrograms() {
            return this.programs.filter(program =>
                program.program_title.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        }
    },
    mounted() {
        this.getUserDetails();
    },
    methods: {
        addRoles: function(){
            axios.post('api/addRoles',{
                user_id: this.user_id,
                roles: this.selected_id
            })
                .then(response => {
                    this.triggerSuccess(response.data.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                })
                .catch(error => {
                    this.triggerError(error.response.data.message)
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                });
        },
        async getUserDetails(){
            try {
                const response = await axios.get('api/getUserDetails');
                this.data = response.data;
            } catch (error) {
                console.log(error)
            }
           
        },
        triggerSuccess(message) {
            this.$refs.toast.showToast(message, 'success');
        },
        triggerError(message) {
            this.$refs.toast.showToast(message, 'error');
        },
        toggleDropdown() {
            this.$emit('toggle-dropdown');
        },
        selectPrograms(program) {
            this.$emit('selected-programs', program);
            this.selected_id = program.id;
        },
        closeModal() {
        this.$emit("close");
      },
    }
};
</script>

<style scoped>
/* Additional styling if needed */
</style>
<script>
import ProgressBar from '../progressBar.vue';
import EventToast from "../EventToast.vue";

import axios from 'axios';

export default {
    name: 'create-user-form',
    components: {
        ProgressBar,
        EventToast,
    },
    data() {
        return {
            userId: null,
            apiToken: null,
            selectedAgencyName: null,
            selectedOfficeName: null,
            selectedServiceName: null,
            selectedDivisionName: null,
            selectedAgency: null,
            selectedOffice: null,
            selectedService: null,
            selectedDivision: null,
            showAgencyDropdown: false,
            showOfficeDropdown: false,
            showServiceDropdown: false,
            showDivisionDropdown: false,
            searchQuery: '',
            agency: [],
            office: [],
            service: [],
            division: [],
            formData: {
                agency: '',
                office: '',
                position: '',
                firstname: '',
                middlename: '',
                lastname: '',
                province: '',
                region: '',
                complete_address: '',
                sex: '',
                birthdate: '',
                mobile_number: '',
                email_address: '',
                user_role: '',
                emp_status: '',
                username: '',
                password: '',
            },

        };
    },
    computed: {
        filteredAgencies() {
            return this.agency.filter(agency =>
                agency.AGENCY.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        },
        filteredRegionOffice() {
            return this.office.filter(office =>
                office.INFO_REGION.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        },
        filteredServiceInfo() {
            return this.service.filter(service =>
                service.INFO_SERVICE.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        },
        filteredDivisionInfo() {
            return this.division.filter(division =>
                division.INFO_DIVISION.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        }
    },
    mounted() {
        this.apiToken = localStorage.getItem('api_token');
        this.userId = localStorage.getItem('userId');
        // this.getProfileInfo();
        this.getAgency();
        this.getRegionOffice();
        this.getServinceInfo();
        this.getDivision();
    },
    methods: {
        submitForm() {
            // Send formData to the server via Axios or another method
            axios.post('api/createUser', this.formData)
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
        async getAgency() {
            try {
                const response = await axios.get('api/getAgency');
                this.agency = response.data;

            } catch (error) {

            }
        },
        async getRegionOffice() {
            try {
                const response = await axios.get('api/getRegionOffice');
                this.office = response.data;

            } catch (error) {

            }
        },
        async getServinceInfo() {
            try {
                const response = await axios.get('api/getServiceInfo');
                this.service = response.data;
            } catch (error) {

            }
        },
        async getDivision() {
            try {
                const response = await axios.get('api/getDivision');
                this.division = response.data;
            } catch (error) {

            }
        },
        triggerSuccess(message) {
            this.$refs.toast.showToast(message, 'success');
        },
        triggerError(message) {
            this.$refs.toast.showToast(message, 'error');
        },
        generateUsername() {
            const firstInitial = this.formData.firstname ? this.formData.firstname[0].toLowerCase() : '';
            const middleInitial = this.formData.middlename ? this.formData.middlename[0].toLowerCase() : '';
            const lastName = this.formData.lastname ? this.formData.lastname.toLowerCase() : '';
            this.formData.username = `${firstInitial}${middleInitial}${lastName}`;
        },
        toggleAgencyDropdown() {
            this.showAgencyDropdown = !this.showAgencyDropdown;
        },
        toggleOfficeDropdown() {
            this.showOfficeDropdown = !this.showOfficeDropdown;
        },
        toggleServiceDropdown() {
            this.showServiceDropdown = !this.showServiceDropdown;
        },
        toggleDivisionDropdown() {
            this.showDivisionDropdown = !this.showDivisionDropdown;
        },
        selectAgency(agency) {
            this.formData.agency = agency.ID;
            this.selectedAgencyName = agency.AGENCY;
            this.showAgencyDropdown = false;
        },
        selectOffice(office) {
            this.formData.office = office.ID;
            this.selectedOfficeName = office.INFO_REGION;
            this.showOfficeDropdown = false;
        },
        selectService(service) {
            this.selectedService = service.ID;
            this.selectedServiceName = service.INFO_SERVICE;
            this.showServiceDropdown = false;
        },
        selectDivision(division) {
            this.selectedDivision = division.ID;
            this.selectedDivisionName = division.INFO_DIVISION;
            this.showDivisionDropdown = false;
        },
    },
};
</script>

<template>
    <ProgressBar ref="progressBar" />
    <form @submit.prevent="submitForm">
        <!-- Name Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div>
                <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                <input id="firstname" type="text" v-model="formData.firstname" @input="generateUsername"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="" />
            </div>

            <div>
                <label for="middlename" class="block text-sm font-medium text-gray-700">Middle Name</label>
                <input id="middlename" type="text" v-model="formData.middlename" @input="generateUsername"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="" />
            </div>

            <div>
                <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input id="lastname" type="text" v-model="formData.lastname" @input="generateUsername"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="" />
            </div>
            <div>
                <label for="ext_name" class="block text-sm font-medium text-gray-700">Extension Name</label>
                <input id="ext_name" type="text" v-model="formData.ext_name"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="" />
            </div>
        </div>

        <!-- Address Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div>
                <label for="province" class="block text-sm font-medium text-gray-700">Barangay</label>
                <input id="barangay" type="text" v-model="formData.barangay"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="" />
            </div>
            <div>
                <label for="province" class="block text-sm font-medium text-gray-700">Municipality</label>
                <input id="municipality" type="text" v-model="formData.municipality"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="" />
            </div>
            <div>
                <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                <input id="province" type="text" v-model="formData.province"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="" />
            </div>

            <div>
                <label for="region" class="block text-sm font-medium text-gray-700">Region</label>
                <input id="region" type="text" v-model="formData.region"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="" />
            </div>
        </div>

        <div class="mb-6">
            <label for="complete_address" class="block text-sm font-medium text-gray-700">Complete Address</label>
            <textarea id="complete_address" v-model="formData.complete_address"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                rows="3" placeholder="123 Street, Barangay, City"></textarea>
        </div>

        <!-- Personal Info Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div>
                <label for="sex" class="block text-sm font-medium text-gray-700">Sex</label>
                <select id="sex" v-model="formData.sex"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="1">Male</option>
                    <option value="2">Female</option>
                </select>
            </div>

            <div>
                <label for="birthdate" class="block text-sm font-medium text-gray-700">Birthdate</label>
                <input id="birthdate" type="date" v-model="formData.birthdate"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
            </div>

            <div>
                <label for="mobile_number" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                <input id="mobile_number" type="tel" v-model="formData.mobile_number"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="09123456789" />
            </div>
            <div>
                <label for="email_address" class="block text-sm font-medium text-gray-700">E-mail Address</label>
                <input id="email_address" type="email" v-model="formData.email_address"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="sample@gmail.com" />
            </div>
        </div>

        <!-- Work Info Section -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
            <div>
                <label for="user_role" class="block text-sm font-medium text-gray-700">User
                    Role</label>

                <select v-model="formData.user_role" id="user_role"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="1">Super Admin</option>
                    <option value="2">Regional Admin</option>
                    <option value="3">User Validator</option>
                </select>
            </div>
            <div class="relative">
                <label for="agency" class="block text-sm font-medium text-gray-700">Agency</label>

                <!-- Custom select dropdown -->
                <div @click="toggleAgencyDropdown"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                    <span>{{ selectedAgencyName || 'Select Agency' }}</span>
                    <span class="float-right">▼</span>
                </div>

                <!-- Dropdown with search bar and scrollable options -->
                <div v-if="showAgencyDropdown"
                    class="absolute w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg z-20">
                    <!-- Search input -->
                    <input type="text" placeholder="Search agency..."
                        class="w-full px-4 py-2 border-b border-gray-300 focus:outline-none" />

                    <!-- Scrollable dropdown list -->
                    <ul class="max-h-48 overflow-y-auto">
                        <li v-for="agency in filteredAgencies" :key="agency.id" @click="selectAgency(agency)"
                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            {{ agency.AGENCY }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="relative">
                <label for="region_office" class="block text-sm font-medium text-gray-700">Office Station</label>

                <!-- Custom select dropdown -->
                <div @click="toggleOfficeDropdown"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                    <span>{{ selectedOfficeName || 'Select Office Station' }}</span>
                    <span class="float-right">▼</span>
                </div>

                <!-- Dropdown with search bar and scrollable options -->
                <div v-if="showOfficeDropdown"
                    class="absolute w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg z-20">
                    <!-- Search input -->
                    <input v-model="searchQuery" type="text" placeholder="Search agency..."
                        class="w-full px-4 py-2 border-b border-gray-300 focus:outline-none" />

                    <!-- Scrollable dropdown list -->
                    <ul class="max-h-48 overflow-y-auto">
                        <li v-for="office in filteredRegionOffice" :key="office.id" @click="selectOffice(office)"
                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            {{ office.INFO_REGION }}
                        </li>
                    </ul>
                </div>
            </div>

            <div>
                <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                <input v-model="formData.position" id="position" type="text"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Software Developer" />
            </div>
            <div>
                <label for="emp_status" class="block text-sm font-medium text-gray-700">Status</label>
                <select v-model="formData.emp_status" id="emp_status"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="1">COS</option>
                    <option value="2">Permanent</option>
                    <option value="3">Job Order</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- <div class="relative">
                <label for="region_office" class="block text-sm font-medium text-gray-700">Service Information</label>

                <div @click="toggleServiceDropdown"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                    <span>{{ selectedServiceName || 'Select Service' }}</span>
                    <span class="float-right">▼</span>
                </div>

                <div v-if="showServiceDropdown"
                    class="absolute w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg z-20">
                    <input v-model="searchQuery" type="text" placeholder="Search agency..."
                        class="w-full px-4 py-2 border-b border-gray-300 focus:outline-none" />

                    <ul class="max-h-48 overflow-y-auto">
                        <li v-for="service in filteredServiceInfo" :key="service.id" @click="selectService(service)"
                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            {{ service.INFO_SERVICE }}
                        </li>
                    </ul>
                </div>
            </div> -->

            <!-- <div class="relative">
                <label for="region_office" class="block text-sm font-medium text-gray-700">Service Information</label> -->

            <!-- Custom select dropdown -->
            <!-- <div @click="toggleDivisionDropdown"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                    <span>{{ selectedDivisionName || 'Select Division' }}</span>
                    <span class="float-right">▼</span>
                </div> -->

            <!-- Dropdown with search bar and scrollable options -->
            <!-- <div v-if="showDivisionDropdown"
                    class="absolute w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg z-20"> -->
            <!-- Search input -->
            <!-- <input v-model="searchQuery" type="text" placeholder="Search agency..."
                        class="w-full px-4 py-2 border-b border-gray-300 focus:outline-none" /> -->

            <!-- Scrollable dropdown list -->
            <!-- <ul class="max-h-48 overflow-y-auto">
                        <li v-for="division in filteredDivisionInfo" :key="division.id"
                            @click="selectDivision(division)" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            {{ division.INFO_DIVISION }}
                        </li>
                    </ul> -->
            <!-- </div> -->
            <!-- </div> -->
        </div>

        <!-- Separator -->
        <hr class="my-6 border-gray-300" />
        <h2 class="text-2xl font-bold mb-6 text-gray-700">
            User Information
        </h2>
        <!-- Account Info Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input id="username" type="text" v-model="formData.username" disabled
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Username" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input v-model="formData.password" id="password" type="password"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="********" />
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                Submit
            </button>
        </div>
    </form>
    <event-toast ref="toast"></event-toast>

</template>

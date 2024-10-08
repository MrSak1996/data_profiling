<script>
import ProgressBar from '../progressBar.vue';
import axios from 'axios';

export default {
    name: 'profile-form',
    components: {
        ProgressBar,
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
        async getProfileInfo() {
            try {
                this.$refs.progressBar.startProgress();

                const response = await axios.get(`api/getProfileInfo?user_id=${this.userId}&api_token=${this.apiToken}`, {
                    onDownloadProgress: (progressEvent) => {
                        if (progressEvent.lengthComputable) {
                            const progress = Math.floor(
                                (progressEvent.loaded / progressEvent.total) * 100
                            );
                            this.$refs.progressBar.setProgress(progress);
                        }
                    },
                });

                this.$refs.progressBar.completeProgress();
                console.log(response.data);

            } catch (error) {
                console.error('Error fetching profile info:', error);
            }
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
        async getServinceInfo(){
            try {
                const response = await axios.get('api/getServiceInfo');
                this.service = response.data;
            } catch (error) {

            }
        },
        async getDivision(){
            try {
                const response = await axios.get('api/getDivision');
                this.division = response.data;
            } catch (error) {

            }
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
            this.selectedAgency = agency.id;
            this.selectedAgencyName = agency.AGENCY;
            this.showAgencyDropdown = false;
        },
        selectOffice(office) {
            this.selectedOffice = office.id;
            this.selectedOfficeName = office.INFO_REGION;
            this.showOfficeDropdown = false;
        },
        selectService(service) {
            this.selectedService = service.id;
            this.selectedServiceName = service.INFO_SERVICE;
            this.showServiceDropdown = false;
        },
        selectDivision(division) {
            this.selectedDivision = division.id;
            this.selectedDivisionName = division.INFO_DIVISION;
            this.showDivisionDropdown = false;
        },
    },
};
</script>

<template>
    <ProgressBar ref="progressBar" />
    <form action="#">
        <!-- Name Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label for="firstname" class="block text-sm font-medium text-gray-700">First
                    Name</label>
                <input id="firstname" type="text"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="John" />
            </div>

            <div>
                <label for="middlename" class="block text-sm font-medium text-gray-700">Middle
                    Name</label>
                <input id="middlename" type="text"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Doe" />
            </div>

            <div>
                <label for="lastname" class="block text-sm font-medium text-gray-700">Last
                    Name</label>
                <input id="lastname" type="text"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Smith" />
            </div>
        </div>

        <!-- Address Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                <input id="province" type="text"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Laguna" />
            </div>

            <div>
                <label for="region" class="block text-sm font-medium text-gray-700">Region</label>
                <input id="region" type="text"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Region IV-A" />
            </div>
        </div>

        <div class="mb-6">
            <label for="complete_address" class="block text-sm font-medium text-gray-700">Complete
                Address</label>
            <textarea id="complete_address"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                rows="3" placeholder="123 Street, Barangay, City"></textarea>
        </div>

        <!-- Personal Info Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div>
                <label for="sex" class="block text-sm font-medium text-gray-700">Sex</label>
                <select id="sex"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option>Male</option>
                    <option>Female</option>
                </select>
            </div>

            <div>
                <label for="birthdate" class="block text-sm font-medium text-gray-700">Birthdate</label>
                <input id="birthdate" type="date"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
            </div>

            <div>
                <label for="mobile_number" class="block text-sm font-medium text-gray-700">Mobile
                    Number</label>
                <input id="mobile_number" type="tel"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="09123456789" />
            </div>
            <div>
                <label for="email_address" class="block text-sm font-medium text-gray-700">E-mail Address</label>
                <input id="email_address" type="email"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="09123456789" />
            </div>
        </div>

        <!-- Work Info Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div>
                <label for="user_role" class="block text-sm font-medium text-gray-700">User
                    Role</label>

                <select id="user_role"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option>Admin</option>
                    <option>Employee</option>
                    <option>Manager</option>
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
                    <input v-model="searchQuery" type="text" placeholder="Search agency..."
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
                <input id="position" type="text"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Software Developer" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="relative">
                <label for="region_office" class="block text-sm font-medium text-gray-700">Service Information</label>

                <!-- Custom select dropdown -->
                <div @click="toggleServiceDropdown"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                    <span>{{ selectedServiceName || 'Select Service' }}</span>
                    <span class="float-right">▼</span>
                </div>

                <!-- Dropdown with search bar and scrollable options -->
                <div v-if="showServiceDropdown"
                    class="absolute w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg z-20">
                    <!-- Search input -->
                    <input v-model="searchQuery" type="text" placeholder="Search agency..."
                        class="w-full px-4 py-2 border-b border-gray-300 focus:outline-none" />

                    <!-- Scrollable dropdown list -->
                    <ul class="max-h-48 overflow-y-auto">
                        <li v-for="service in filteredServiceInfo" :key="service.id" @click="selectService(service)"
                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            {{ service.INFO_SERVICE }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="relative">
                <label for="region_office" class="block text-sm font-medium text-gray-700">Service Information</label>

                <!-- Custom select dropdown -->
                <div @click="toggleDivisionDropdown"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                    <span>{{ selectedDivisionName || 'Select Division' }}</span>
                    <span class="float-right">▼</span>
                </div>

                <!-- Dropdown with search bar and scrollable options -->
                <div v-if="showDivisionDropdown"
                    class="absolute w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg z-20">
                    <!-- Search input -->
                    <input v-model="searchQuery" type="text" placeholder="Search agency..."
                        class="w-full px-4 py-2 border-b border-gray-300 focus:outline-none" />

                    <!-- Scrollable dropdown list -->
                    <ul class="max-h-48 overflow-y-auto">
                        <li v-for="division in filteredDivisionInfo" :key="division.id" @click="selectDivision(division)"
                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            {{ division.INFO_DIVISION}}
                        </li>
                    </ul>
                </div>
            </div>
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
                <input readonly disabled="username" type="text"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="johndoe" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password"
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
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            region_opts: [], // Array for holding the options fetched from the API
            selectedRegion: '', // Optional: to bind the selected value
            showRegCodeDropdown: false,
            selectedRegName: null,
            showRegCodeDropdown: false,



        }
    },
    mounted() {
        this.getRegionOffice();
    },
    computed:{
        filteredRegCode() {
            return this.region_opts.filter(region_opts =>
                region_opts.reg_name.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        },
    },
    methods: {
        async getRegionOffice() {
            try {
                const response = await axios.get('api/getRegionCode');
                this.region_opts = response.data;
            } catch (error) {
                console.error('Error fetching region office:', error);
            }
        },
        toggleRegCodeDropdown() {
            this.showRegCodeDropdown = !this.showRegCodeDropdown;
        },
        selectRegionCode(region_opts) {
            this.selectedRegName = region_opts.reg_name;
            this.showRegCodeDropdown = false;
        },
    },
}
</script>

<template>
    <div>
        <div class="relative">
                <label for="agency" class="block text-sm font-medium text-gray-700">Agency</label>

                <!-- Custom select dropdown -->
                <div @click="toggleRegCodeDropdown"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                    <span>{{ selectedRegName || 'Select Region' }}</span>
                    <span class="float-right">▼</span>
                </div>

                <!-- Dropdown with search bar and scrollable options -->
                <div v-if="showRegCodeDropdown"
                    class="absolute w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg z-20">
                    <!-- Search input -->
                    <input type="text" placeholder="Search agency..."
                        class="w-full px-4 py-2 border-b border-gray-300 focus:outline-none" />

                    <!-- Scrollable dropdown list -->
                    <ul class="max-h-48 overflow-y-auto">
                        <li v-for="region_opts in filteredRegCode" :key="region_opts.geo_code" @click="selectRegionCode(region_opts)"
                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            {{ region_opts.reg_name }}
                        </li>
                    </ul>
                </div>
            </div>
    </div>
</template>

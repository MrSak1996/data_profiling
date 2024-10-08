<script>
    import { ref, onMounted, computed } from 'vue';
    import axios from 'axios';
   import adminTabsUpload from './admin_tabs_upload.vue';
   import adminTabsRecords from './admin_tabs_records.vue';
   import adminTabsInvalid from './admin_tabs_invalid.vue';
   import adminTabsMatching from './admin_tabs_matching.vue';
   import adminTabsDedup from './admin_tabs_dedup.vue';
    export default {
        components:{
            adminTabsUpload,
            adminTabsRecords,
            adminTabsInvalid,
            adminTabsMatching,
            adminTabsDedup
        },
        data() {
            return {
                activeTab: 'upload',
            }
        },
        methods: {
            setActiveTab(tab) {
                this.activeTab = tab;
            },
            tabClass(tab) {
            return this.activeTab === tab
                ? 'border-blue-600 text-blue-600 dark:border-blue-500 dark:text-blue-500'
                : 'border-transparent text-gray-500 dark:text-gray-400';
        },
        }
    }
</script>
<template>
    <div class="flex flex-col  col-span-full border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
            <li class="me-2">
                <a href="#" @click.prevent="setActiveTab('upload')"
                    :class="{ 'text-blue-600 border-blue-600': activeTab === 'upload', 'border-transparent': activeTab !== 'upload' }"
                    class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                    <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                    </svg>
                    Upload Records
                </a>
            </li>
            <li class="me-2">
                <a href="#" @click.prevent="setActiveTab('total')"
                    :class="{ 'text-blue-600 border-blue-600': activeTab === 'total', 'border-transparent': activeTab !== 'total' }"
                    class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                    <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                    </svg>
                    Total Records
                </a>
            </li>
            <li class="me-2">
                <a href="#" @click.prevent="setActiveTab('invalid')"
                    :class="{ 'text-blue-600 border-blue-600': activeTab === 'invalid', 'border-transparent': activeTab !== 'invalid' }"
                    class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                    <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 18 18">
                        <path
                            d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z" />
                    </svg>
                    Invalid Data
                </a>
            </li>
            <li class="me-2">
                <a href="#" @click.prevent="setActiveTab('unmatched')"
                    :class="{ 'text-blue-600 border-blue-600': activeTab === 'unmatched', 'border-transparent': activeTab !== 'unmatched' }"
                    class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                    <svg class="w-4 h-4 me-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                        <path
                            d="M5 11.424V1a1 1 0 1 0-2 0v10.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.228 3.228 0 0 0 0-6.152ZM19.25 14.5A3.243 3.243 0 0 0 17 11.424V1a1 1 0 0 0-2 0v10.424a3.227 3.227 0 0 0 0 6.152V19a1 1 0 1 0 2 0v-1.424a3.243 3.243 0 0 0 2.25-3.076Zm-6-9A3.243 3.243 0 0 0 11 2.424V1a1 1 0 0 0-2 0v1.424a3.228 3.228 0 0 0 0 6.152V19a1 1 0 1 0 2 0V8.576A3.243 3.243 0 0 0 13.25 5.5Z" />
                    </svg>
                    Match vs Unmatched Data
                </a>
            </li>
            <li class="me-2">
                <a href="#" @click.prevent="setActiveTab('duplicate')"
                    :class="{ 'text-blue-600 border-blue-600': activeTab === 'duplicate', 'border-transparent': activeTab !== 'duplicate' }"
                    class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">

                    <svg class="w-4 h-4 me-2 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path
                            d="M208 0L332.1 0c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9L448 336c0 26.5-21.5 48-48 48l-192 0c-26.5 0-48-21.5-48-48l0-288c0-26.5 21.5-48 48-48zM48 128l80 0 0 64-64 0 0 256 192 0 0-32 64 0 0 48c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 176c0-26.5 21.5-48 48-48z" />
                    </svg>

                    Duplicate Data
                </a>
            </li>
            <li>
                <a
                    class="inline-block p-4 text-gray-400 rounded-t-lg cursor-not-allowed dark:text-gray-500">Disabled</a>
            </li>
        </ul>
    </div>
    <adminTabsUpload :activeTab="activeTab" />
    <adminTabsRecords :activeTab="activeTab" />
    <adminTabsInvalid :activeTab="activeTab" />
    <adminTabsMatching :activeTab="activeTab" />
    <adminTabsDedup :activeTab="activeTab" />
   
</template>

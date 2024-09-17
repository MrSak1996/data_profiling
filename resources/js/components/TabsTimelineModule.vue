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
    <div v-if="activeTab === 'upload'"
        class="flex flex-col items-start h-auto p-4 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <form @submit.prevent="saveExcelData" ref="mainForm" enctype="multipart/form-data"
            class="flex flex-col items-center w-full space-y-4">
            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file"
                    class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                            <span class="font-semibold">Click to upload</span> or drag and
                            drop
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            SVG, PNG, JPG or GIF (MAX. 800x400px)
                        </p>
                    </div>
                    <input type="file" id="dropzone-file" name="selected_file" @change="handleFileChange"
                        ref="fileInput" accept=".xlsx, .xls, .csv" class="hidden" />
                </label>
            </div>
            <div class="flex justify-end w-full">
                <button type="submit" name="upload" @click.prevent="saveExcelData"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Upload File
                </button>
            </div>
        </form>
    </div>
    <div v-else-if="activeTab === 'total'"
        class="flex flex-col items-start h-auto p-4 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div style="width:1500px; overflow-x: auto;">
                <table id="example"
                    class="border-t min-w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
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
                            <th class="px-4 py-2">Birth Date</th>
                            <th class="px-4 py-2">Place of Birth</th>
                            <th class="px-4 py-2">Mobile No.</th>
                            <th class="px-4 py-2">Sex</th>
                            <th class="px-4 py-2">Nationality</th>
                            <th class="px-4 py-2">Profession</th>
                            <th class="px-4 py-2">Source of Funds</th>
                            <th class="px-4 py-2">Mother Mainden Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table content -->
                    </tbody>
                </table>
            </div>

    </div>
    <div v-else-if="activeTab === 'invalid'"
        class="w-full h-auto p-4 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-900 uppercase bg-blue-100 dark:bg-blue-700 dark:text-blue-400">
                    <tr>
                        <th scope="col" class="px-3 py-3 rounded-s-lg">File Name</th>
                        <th scope="col" class="px-3 py-3">Null Values</th>
                        <th scope="col" class="px-3 py-3">Below 2 Characters</th>
                        <th scope="col" class="px-3 py-3">Unwanted Characters</th>
                        <th scope="col" class="px-3 py-3">Invalid Date Format</th>
                        <th scope="col" class="px-3 py-3">String with Special Characters</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Iterate through the invalid_data prop to display data -->
                    <tr v-for="item in invalid_data" :key="item.id" class="bg-white dark:bg-gray-800">
                        <td class="px-3 py-4">{{ item.filename }}</td>
                        <td class="px-3 py-4">{{ item.null_values }}</td>
                        <td class="px-3 py-4">{{ item.below_2letters }}</td>
                        <td class="px-3 py-4">{{ item.unwanted_char }}</td>
                        <td class="px-3 py-4">{{ item.date_format }}</td>
                        <td class="px-3 py-4">{{ item.specialchar }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div v-else-if="activeTab === 'unmatched'"
        class="w-full h-auto p-4 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="relative overflow-x-auto">
            <div class="flex justify-start space-x-2">
                <button type="button" @click="checkDataMatches"
                    class="flex items-center text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Run Data Matches
                </button>



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
                                <a @click="exportmatch" href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Export
                                    Matched Data</a>
                            </li>
                            <li>
                                <a @click="ëxportUnmatch"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Export
                                    Unmatched Data</a>
                            </li>
                        </ul>
                    </div>
                </div>


            </div>
            <!-- https://codepen.io/robstinson/pen/MWexYPG -->
            <!-- Component Start -->
            <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-6 w-full  mt-4">

                <!-- Tile 1 -->
                <div class="flex items-center p-4 bg-gray-100 rounded">
                    <div class="flex flex-shrink-0 items-center justify-center bg-green-200 h-16 w-16 rounded">

                        <svg class="w-6 h-6 fill-current text-green-700" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 448 512">
                            <path
                                d="M448 80l0 48c0 44.2-100.3 80-224 80S0 172.2 0 128L0 80C0 35.8 100.3 0 224 0S448 35.8 448 80zM393.2 214.7c20.8-7.4 39.9-16.9 54.8-28.6L448 288c0 44.2-100.3 80-224 80S0 332.2 0 288L0 186.1c14.9 11.8 34 21.2 54.8 28.6C99.7 230.7 159.5 240 224 240s124.3-9.3 169.2-25.3zM0 346.1c14.9 11.8 34 21.2 54.8 28.6C99.7 390.7 159.5 400 224 400s124.3-9.3 169.2-25.3c20.8-7.4 39.9-16.9 54.8-28.6l0 85.9c0 44.2-100.3 80-224 80S0 476.2 0 432l0-85.9z" />
                        </svg>
                    </div>
                    <div class="flex-grow flex flex-col ml-4">
                        <span class="text-xl font-bold">{{ totalRecords }}</span>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Total No. of Records</span>
                            <span class="text-green-500 text-sm font-semibold ml-2">as of Sept 2024</span>
                        </div>
                    </div>
                </div>

                <!-- Tile 2 -->
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
                            <span class="text-red-500 text-sm font-semibold ml-2">as of Sept 2024</span>
                        </div>
                    </div>
                </div>

                <!-- Tile 3 -->
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
                            <span class="text-green-500 text-sm font-semibold ml-2">as of Sept 2024</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- match data -->
            <div style="overflow-x: auto; max-width: 100%;">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
                    <h1>Matched Data</h1>
                    <table class="border-t w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-2 w-32">RSBS Number</th>
                                <th class="px-4 py-2">First Name</th>
                                <th class="px-4 py-2">Last Name</th>
                                <th class="px-4 py-2">Middle Name</th>
                                <th class="px-4 py-2 w-64">Mother Maiden Name</th>
                                <th class="px-4 py-2">Sex</th>
                                <th class="px-4 py-2">Birthdate</th>
                                <th class="px-4 py-2 w-32">Place of Birth</th>
                                <th class="px-4 py-2">Mobile No</th>
                                <th class="px-4 py-2">Barangay</th>
                                <th class="px-4 py-2">City/Municipality</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in paginatedMatchData" :key="item.id"
                                class="bg-white dark:bg-gray-800 border-b">
                                <td class="px-4 py-2">{{ item.RSBSASYSTEMGENERATEDNUMBER }}</td>
                                <td class="px-4 py-2">{{ item.FIRSTNAME }}</td>
                                <td class="px-4 py-2">{{ item.LASTNAME }}</td>
                                <td class="px-4 py-2">{{ item.MIDDLENAME }}</td>
                                <td class="px-4 py-2">{{ item.MOTHERMAIDENNAME }}</td>
                                <td class="px-4 py-2">{{ item.SEX }}</td>
                                <td class="px-4 py-2">{{ item.BIRTHDATE }}</td>
                                <td class="px-4 py-2">{{ item.PLACEOFBIRTH }}</td>
                                <td class="px-4 py-2">{{ item.MOBILENO }}</td>
                                <td class="px-4 py-2">{{ item.BARANGAY }}</td>
                                <td class="px-4 py-2">{{ item.CITYMUNICIPALITY }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination controls -->
                <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                    <div class="flex flex-1 justify-between sm:hidden">
                        <a href="#" @click.prevent="changePage(currentMatchPage - 1)"
                            :class="{ 'cursor-not-allowed opacity-50': currentMatchPage === 1 }"
                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Previous
                        </a>
                        <a href="#" @click.prevent="changePage(currentMatchPage + 1)"
                            :class="{ 'cursor-not-allowed opacity-50': currentMatchPage === totalMatchPages }"
                            class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Next
                        </a>
                    </div>

                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium">{{ startMatch }}</span>
                                to
                                <span class="font-medium">{{ endMatch }}</span>
                                of
                                <span class="font-medium">{{ totalmatchData }}</span>
                                results
                            </p>
                        </div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <a href="#" @click.prevent="changePage(currentMatchPage - 1)"
                                :class="{ 'cursor-not-allowed opacity-50': currentMatchPage === 1 }"
                                class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Previous</span>
                                <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                            </a>

                            <a v-for="page in visiblePages" :key="page" href="#" @click.prevent="changePage(page)"
                                :class="{ 'z-10 bg-indigo-600 text-white': currentMatchPage === page }"
                                class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                {{ page }}
                            </a>

                            <a href="#" @click.prevent="changePage(currentMatchPage + 1)"
                                :class="{ 'cursor-not-allowed opacity-50': currentMatchPage === totalMatchPages }"
                                class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Next</span>
                                <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- unmatch data -->
            <div style="overflow-x: auto; max-width: 100%;">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    Unmatched Data
                    <table class="border-t w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-2 w-32">RSBS Number</th>
                                <th class="px-4 py-2">First Name</th>
                                <th class="px-4 py-2">Last Name</th>
                                <th class="px-4 py-2">Middle Name</th>
                                <th class="px-4 py-2 w-64">Mother Maiden Name</th>
                                <th class="px-4 py-2">Sex</th>
                                <th class="px-4 py-2">Birthdate</th>
                                <th class="px-4 py-2 w-32">Place of Birth</th>
                                <th class="px-4 py-2">Mobile No</th>
                                <th class="px-4 py-2">Barangay</th>
                                <th class="px-4 py-2">City/Municipality</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in paginatedData" :key="item.id" class="bg-white dark:bg-gray-800 border-b">
                                <td class="px-4 py-2">{{ item.RSBSASYSTEMGENERATEDNUMBER }}</td>
                                <td class="px-4 py-2">{{ item.FIRSTNAME }}</td>
                                <td class="px-4 py-2">{{ item.LASTNAME }}</td>
                                <td class="px-4 py-2">{{ item.MIDDLENAME }}</td>
                                <td class="px-4 py-2">{{ item.MOTHERMAIDENNAME }}</td>
                                <td class="px-4 py-2">{{ item.SEX }}</td>
                                <td class="px-4 py-2">{{ item.BIRTHDATE }}</td>
                                <td class="px-4 py-2">{{ item.PLACEOFBIRTH }}</td>
                                <td class="px-4 py-2">{{ item.MOBILENO }}</td>
                                <td class="px-4 py-2">{{ item.BARANGAY }}</td>
                                <td class="px-4 py-2">{{ item.CITYMUNICIPALITY }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination controls -->
                <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                    <div class="flex flex-1 justify-between sm:hidden">
                        <a href="#" @click.prevent="changeUnmatchPage(currentPage - 1)"
                            :class="{ 'cursor-not-allowed opacity-50': currentPage === 1 }"
                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Previous
                        </a>
                        <a href="#" @click.prevent="changeUnmatchPage(currentPage + 1)"
                            :class="{ 'cursor-not-allowed opacity-50': currentPage === totalPages }"
                            class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Next
                        </a>
                    </div>

                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium">{{ start }}</span>
                                to
                                <span class="font-medium">{{ end }}</span>
                                of
                                <span class="font-medium">{{ totalUnmatchData }}</span>
                                results
                            </p>
                        </div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <a href="#" @click.prevent="changeUnmatchPage(currentPage - 1)"
                                :class="{ 'cursor-not-allowed opacity-50': currentPage === 1 }"
                                class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Previous</span>
                                <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                            </a>

                            <a v-for="page in visiblePages" :key="page" href="#"
                                @click.prevent="changeUnmatchPage(page)"
                                :class="{ 'z-10 bg-indigo-600 text-white': currentPage === page }"
                                class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                {{ page }}
                            </a>

                            <a href="#" @click.prevent="changeUnmatchPage(currentPage + 1)"
                                :class="{ 'cursor-not-allowed opacity-50': currentPage === totalPages }"
                                class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Next</span>
                                <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                            </a>
                        </nav>
                    </div>
                </div>
            </div>





        </div>
    </div>
    <div v-else-if="activeTab === 'duplicate'"
        class="w-full h-auto p-4 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="relative overflow-x-auto">
            <div class="flex justify-start space-x-2">
                <button type="button" @click="checkDataDuplication"
                    class="flex items-center text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Run Data Duplication
                </button>

                <div class="relative inline-block text-left">
                    <!-- Main Button with dropdown icon -->
                    <button type="button"
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
                                <a  href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Export
                                    Data Duplication</a>
                            </li>
                        
                        </ul>
                    </div>
                </div>


            </div>
            <!-- https://codepen.io/robstinson/pen/MWexYPG -->
            <!-- Component Start -->
            <div class="grid lg:grid-cols-2 md:grid-cols-2 gap-6 w-full  mt-4">

                <!-- Tile 1 -->
                <div class="flex items-center p-4 bg-gray-100 rounded">
                    <div class="flex flex-shrink-0 items-center justify-center bg-green-200 h-16 w-16 rounded">

                        <svg class="w-6 h-6 fill-current text-green-700" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 448 512">
                            <path
                                d="M448 80l0 48c0 44.2-100.3 80-224 80S0 172.2 0 128L0 80C0 35.8 100.3 0 224 0S448 35.8 448 80zM393.2 214.7c20.8-7.4 39.9-16.9 54.8-28.6L448 288c0 44.2-100.3 80-224 80S0 332.2 0 288L0 186.1c14.9 11.8 34 21.2 54.8 28.6C99.7 230.7 159.5 240 224 240s124.3-9.3 169.2-25.3zM0 346.1c14.9 11.8 34 21.2 54.8 28.6C99.7 390.7 159.5 400 224 400s124.3-9.3 169.2-25.3c20.8-7.4 39.9-16.9 54.8-28.6l0 85.9c0 44.2-100.3 80-224 80S0 476.2 0 432l0-85.9z" />
                        </svg>
                    </div>
                    <div class="flex-grow flex flex-col ml-4">
                        <span class="text-xl font-bold">{{ totalRecords }}</span>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Total No. of Records</span>
                        </div>
                    </div>
                </div>

                <!-- Tile 2 -->
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
                            <span class="text-gray-500">Total Duplicate Data</span>
                        </div>
                    </div>
                </div>

            
            </div>
          
            <!-- unmatch data -->
            <div style="overflow-x: auto; max-width: 100%;">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    
                    <table class="border-t w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-2 w-32">RSBSA #</th>
                                <th class="px-4 py-2">First Name</th>
                                <th class="px-4 py-2">Last Name</th>
                                <th class="px-4 py-2">Middle Name</th>
                                <th class="px-4 py-2 w-64">Mother Maiden Name</th>
                                <th class="px-4 py-2">Sex</th>
                                <th class="px-4 py-2">Birthdate</th>
                                <th class="px-4 py-2 w-32">Place of Birth</th>
                                <th class="px-4 py-2">Mobile No</th>
                                <th class="px-4 py-2">Barangay</th>
                                <th class="px-4 py-2">City/Municipality</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in paginatedData" :key="item.id" class="bg-white dark:bg-gray-800 border-b">
                                <td class="px-4 py-2">{{ item.RSBSASYSTEMGENERATEDNUMBER }}</td>
                                <td class="px-4 py-2">{{ item.FIRSTNAME }}</td>
                                <td class="px-4 py-2">{{ item.LASTNAME }}</td>
                                <td class="px-4 py-2">{{ item.MIDDLENAME }}</td>
                                <td class="px-4 py-2">{{ item.MOTHERMAIDENNAME }}</td>
                                <td class="px-4 py-2">{{ item.SEX }}</td>
                                <td class="px-4 py-2">{{ item.BIRTHDATE }}</td>
                                <td class="px-4 py-2">{{ item.PLACEOFBIRTH }}</td>
                                <td class="px-4 py-2">{{ item.MOBILENO }}</td>
                                <td class="px-4 py-2">{{ item.BARANGAY }}</td>
                                <td class="px-4 py-2">{{ item.CITYMUNICIPALITY }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination controls -->
                <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                    <div class="flex flex-1 justify-between sm:hidden">
                        <a href="#" @click.prevent="changeUnmatchPage(currentPage - 1)"
                            :class="{ 'cursor-not-allowed opacity-50': currentPage === 1 }"
                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Previous
                        </a>
                        <a href="#" @click.prevent="changeUnmatchPage(currentPage + 1)"
                            :class="{ 'cursor-not-allowed opacity-50': currentPage === totalPages }"
                            class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Next
                        </a>
                    </div>

                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium">{{ start }}</span>
                                to
                                <span class="font-medium">{{ end }}</span>
                                of
                                <span class="font-medium">{{ totalUnmatchData }}</span>
                                results
                            </p>
                        </div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <a href="#" @click.prevent="changeUnmatchPage(currentPage - 1)"
                                :class="{ 'cursor-not-allowed opacity-50': currentPage === 1 }"
                                class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Previous</span>
                                <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                            </a>

                            <a v-for="page in visiblePages" :key="page" href="#"
                                @click.prevent="changeUnmatchPage(page)"
                                :class="{ 'z-10 bg-indigo-600 text-white': currentPage === page }"
                                class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                {{ page }}
                            </a>

                            <a href="#" @click.prevent="changeUnmatchPage(currentPage + 1)"
                                :class="{ 'cursor-not-allowed opacity-50': currentPage === totalPages }"
                                class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Next</span>
                                <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                            </a>
                        </nav>
                    </div>
                </div>
            </div>





        </div>
    </div>

</template>


<script>
import axios from 'axios';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid';

export default {
    props: {
        invalid_data: Array,
        checkDataMatches: Function,
        totalRecords: Number
    },
    components: {
        ChevronLeftIcon,
        ChevronRightIcon,
    },
    data() {
        return {
            activeTab: 'total',
            currentPage: 1,
            currentMatchPage: 1,
            itemsPerPage: 5,
            itemsUnmatchPerPage: 5,

            unmatched_data: [], // Define `unmatched_data`
            matched_data: [],   // Define `matched_data`

            totalmatchData: 0,
            totalUnmatchData: 0,

            visiblePages: [],
            isDropdownOpen: false,

        };
    },
    computed: {
        totalPages() {
            return Math.ceil(this.totalUnmatchData / this.itemsUnmatchPerPage);
        },
        totalMatchPages() {
            return Math.ceil(this.totalmatchData / this.itemsPerPage);
        },
        start() {
            return (this.currentPage - 1) * this.itemsUnmatchPerPage + 1;
        },
        startMatch() {
            return (this.currentMatchPage - 1) * this.itemsPerPage + 1;
        },
        end() {
            return Math.min(this.currentPage * this.itemsPerPage, this.totalUnmatchData);
        },
        endMatch() {
            return Math.min(this.currentMatchPage * this.itemsPerPage, this.totalmatchData);
        },

        paginatedData() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.unmatched_data.slice(start, start + this.itemsPerPage);
        },
        paginatedMatchData() {
            const start = (this.currentMatchPage - 1) * this.itemsPerPage;
            return this.matched_data.slice(start, start + this.itemsPerPage);
        }
    },
    methods: {
        async initDataTable() {
            try {
                // Fetch data from the API
                const response = await axios.get('api/getUnmatchData');
                const data = response.data;
                this.unmatched_data = data.data;
                this.totalUnmatchData = data.count;

                // Check if there are records
                if (data.count === 0) {
                    console.error('No records found.');
                    return;
                }

                this.calculateVisiblePages();
            } catch (error) {
                console.error('Failed to fetch data:', error);
            }
        },
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
        },

        async initMatchDataTable() {
            try {
                // Fetch data from the API
                const response = await axios.get('api/getMatchData');
                const data = response.data;
                this.matched_data = data.data;
                this.totalmatchData = data.count;

                // Check if there are records
                if (data.count === 0) {
                    console.error('No records found.');
                    return;
                }

                this.calculateVisiblePages();
            } catch (error) {
                console.error('Failed to fetch data:', error);
            }
        },

        changePage(page) {
            if (page >= 1 && page <= this.totalMatchPages) {
                this.currentMatchPage = page;
                this.calculateMatchVisiblePages();
            }
        },

        changeUnmatchPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.calculateVisiblePages();
            }
        },

        calculateVisiblePages() {
            const pages = [];
            const totalPages = this.totalPages;
            const currentPage = this.currentPage;

            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 2);

            if (currentPage <= 3) {
                endPage = Math.min(5, totalPages);
            } else if (currentPage >= totalPages - 2) {
                startPage = Math.max(1, totalPages - 4);
            }

            for (let page = startPage; page <= endPage; page++) {
                pages.push(page);
            }

            this.visiblePages = pages;
        },

        calculateMatchVisiblePages() {
            const pages = [];
            const totalPages = this.totalMatchPages;
            const currentPage = this.currentMatchPage;

            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 2);

            if (currentPage <= 3) {
                endPage = Math.min(5, totalPages);
            } else if (currentPage >= totalPages - 2) {
                startPage = Math.max(1, totalPages - 4);
            }

            for (let page = startPage; page <= endPage; page++) {
                pages.push(page);
            }

            this.visiblePages = pages;
        },

        setActiveTab(tab) {
            this.activeTab = tab;
        },

        async ëxportUnmatch() {
            try {
                const response = await axios.get('api/exportUnmatch', {
                    responseType: 'blob', // If the response is a file download
                });
                // Create a downloadable link for the file
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'unmatched_data.csv'); // Specify the file name
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                console.log('Data exported successfully!');
            } catch (error) {
                console.error('Error exporting data:', error);
            }

        }
    },
    mounted() {
        this.initDataTable();
        this.initMatchDataTable();
    }
};
</script>

<style>
/* Add any styles needed for pagination controls and tabs */
.tabs {
    margin-bottom: 1rem;
}

.tabs button {
    padding: 0.5rem 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    cursor: pointer;
}

.tabs .active {
    background-color: #f3f4f6;
    border-color: #ddd;
}

/* Ensure the table takes full width */
table {
    width: 100%;
}

/* Make the table responsive by adding horizontal scrolling */
.overflow-x-auto {
    overflow-x: auto;
}
</style>
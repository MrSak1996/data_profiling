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
        <ProgressBar ref="progressBar" />

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
                <button type="submit" name="upload"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Upload File
                </button>
            </div>
        </form>
    </div>
    <div v-else-if="activeTab === 'total'"
        class="flex flex-col items-start h-auto p-4 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <button type="button" @click="getOnbintStaging"
            class="flex items-center text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Show Data
        </button>
        <ProgressBar ref="progressBar" />

        <div>
            <table id="example" class="border-t text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
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
   
    <div v-else-if="activeTab === 'unmatched'"
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
                            <!-- <li>
                                <a @click="exportmatch" href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Export
                                    Matched Data</a>
                            </li> -->
                            <li>
                                <a @click="exportUnmatch()"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Export
                                    Matched Data</a>
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
                                <th class="px-4 py-2 w-32">RSBSA Number</th>
                                <th class="px-4 py-2">First Name</th>
                                <th class="px-4 py-2">Last Name</th>
                                <th class="px-4 py-2">Middle Name</th>
                                <th class="px-4 py-2 w-64">Mother Maiden Name</th>
                                <th class="px-4 py-2">Sex</th>
                                <th class="px-4 py-2">Birthdate</th>
                                <th class="px-4 py-2 w-32">Place of Birth</th>
                                <th class="px-4 py-2">Mobile No</th>
                                <th class="px-4 py-2">Street No./ Purok No.</th>
                                <th class="px-4 py-2">Barangay</th>
                                <th class="px-4 py-2">City/Municipality</th>
                                <th class="px-4 py-2">District</th>
                                <th class="px-4 py-2">Province</th>
                                <th class="px-4 py-2">Region</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr v-for="item in paginatedMatchData" :key="item.id" -->
                            <tr v-for="item in paginatedMatchData" :key="item.id"
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

                            <a v-for="page in visiblePagesMatch" :key="page" href="#" @click.prevent="changePage(page)"
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
                                <th class="px-4 py-2 w-32">RSBSA Number</th>
                                <th class="px-4 py-2">First Name</th>
                                <th class="px-4 py-2">Last Name</th>
                                <th class="px-4 py-2">Middle Name</th>
                                <th class="px-4 py-2 w-64">Mother Maiden Name</th>
                                <th class="px-4 py-2">Sex</th>
                                <th class="px-4 py-2">Birthdate</th>
                                <th class="px-4 py-2 w-32">Place of Birth</th>
                                <th class="px-4 py-2">Mobile No</th>
                                <th class="px-4 py-2">Street No./ Purok No.</th>
                                <th class="px-4 py-2">Barangay</th>
                                <th class="px-4 py-2">City/Municipality</th>
                                <th class="px-4 py-2">District</th>
                                <th class="px-4 py-2">Province</th>
                                <th class="px-4 py-2">Region</th>
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
                                <td class="px-4 py-2">{{ item.STREETNO_PUROKNO }}</td>
                                <td class="px-4 py-2">{{ item.BARANGAY }}</td>
                                <td class="px-4 py-2">{{ item.CITYMUNICIPALITY }}</td>
                                <td class="px-4 py-2">{{ item.DISTRICT }}</td>
                                <td class="px-4 py-2">{{ item.PROVINCE }}</td>
                                <td class="px-4 py-2">{{ item.REGION }}</td>
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

                            <a v-for="page in visiblePagesUnmatch" :key="page" href="#"
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
            <button type="button" @click="duplicateDataStaging"
                class="flex items-center text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Run Data Duplication
            </button>

            <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-6 w-full  mt-4">

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
                        <button type="button"
                            class="text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-xs px-2 py-1.5 text-center me-2 mb-2">View
                            more</button>

                    </div>
                </div>

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
                            <span class="text-gray-500">Total Duplicate Data on Server</span>
                        </div>
                        <button type="button"
                            class="text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-xs px-2 py-1.5 text-center me-2 mb-2">View
                            more</button>
                    </div>
                </div>



            </div>

            <!-- duplicate data -->
            <div style="overflow-x: auto; max-width: 100%;">
                <div class="relative shadow-md sm:rounded-lg p-2">
                    <table border="1"
                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 ">
                        <thead class="text-sm text-gray-900 uppercase bg-blue-100 dark:bg-blue-700 dark:text-blue-400">
                            <tr>
                                <th class="text-left">RSBSA Number</th>
                                <th class="text-left">First Name</th>
                                <th class="text-left">Middle Name</th>
                                <th class="text-left">Last Name</th>
                                <th class="text-left">Sex</th>
                                <th class="text-left">Birthdate</th>
                                <th class="text-left">Street/Purok No.</th>
                                <th class="text-left">Barangay</th>
                                <th class="text-left">City/Municipality</th>
                                <th class="text-left">District</th>
                                <th class="text-left">Province</th>
                                <th class="text-left">Region</th>
                                <th class="text-left">Duplicate(s)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(duplicate, index) in paginatedDuplicateStagingData" :key="index"
                                :class="index % 2 === 0 ? 'bg-gray-100' : 'bg-gray-300'">
                                <!-- Display the main record -->
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

                                <!-- Display all duplicates dynamically -->
                                <td>
                                    <ul>
                                        <li v-for="(item, duplicateIndex) in duplicate.duplicates"
                                            :key="duplicateIndex">
                                            <p>Name: <span
                                                    class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                                    {{ item.FIRSTNAME }} {{ item.MIDDLENAME }} {{ item.LASTNAME
                                                    }}</span>
                                            </p>
                                            <p>Sex: <span
                                                    class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                                    {{ item.SEX }}</span>
                                            </p>
                                            <p>Birthdate: <span
                                                    class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                                    {{ item.BIRTHDATE }} </span>
                                            </p>
                                            <p>Similarity: <span
                                                    class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                                    {{ item.similarity }}</span>
                                            </p>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination controls -->
                <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                    <div class="flex flex-1 justify-between sm:hidden">
                        <a href="#" @click.prevent="changeDuplicateStagingPage(currentDuplicateStagingPage - 1)"
                            :class="{ 'cursor-not-allowed opacity-50': currentDuplicateStagingPage === 1 }"
                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Previous
                        </a>
                        <a href="#" @click.prevent="changeDuplicateStagingPage(currentDuplicateStagingPage + 1)"
                            :class="{ 'cursor-not-allowed opacity-50': currentDuplicateStagingPage === totalPages }"
                            class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Next
                        </a>
                    </div>

                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium">{{ startDuplicate_staging }}</span>
                                to
                                <span class="font-medium">{{ endDuplicate_staging }}</span>
                                of
                                <span class="font-medium">{{ totalDuplicateData_staging }}</span>
                                results
                            </p>
                        </div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <!-- Previous Button -->
                            <a href="#" @click.prevent="changeDuplicateStagingPage(currentDuplicateStagingPage - 1)"
                                :class="{ 'cursor-not-allowed opacity-50': currentDuplicateStagingPage === 1 }"
                                class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Previous</span>
                                <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                            </a>

                            <!-- Page Numbers -->
                            <a v-for="page in visiblePagesDuplicateStaging" :key="page" href="#"
                                @click.prevent="changeDuplicateStagingPage(page)"
                                :class="{ 'z-10 bg-indigo-600 text-white': currentDuplicateStagingPage === page }"
                                class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                {{ page }}
                            </a>

                            <!-- Next Button -->
                            <a href="#" @click.prevent="changeDuplicateStagingPage(currentDuplicateStagingPage + 1)"
                                :class="{ 'cursor-not-allowed opacity-50': currentDuplicateStagingPage === totalDuplicateStagingPages }"
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
import ProgressBar from './progressBar.vue';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid';

export default {

    props: {
        invalidData: {
            type: Object,
            required: true,
            default: 0
        },
        fetchOnbintInvalid: {
            type: Object,
            required: true,
        },
        checkDataMatches: Function,
        totalRecords: Number,


    },

    components: {
        ProgressBar,
        ChevronLeftIcon,
        ChevronRightIcon,
    },
    data() {
        return {
            userId:null,
            file: null,
            activeTab: 'upload',
            currentPage: 1,
            currentMatchPage: 1,
            currentDuplicateStagingPage: 1,
            itemsPerPage: 1000,
            itemsPerInvalidPage: 1000,
            itemsUnmatchPerPage: 1000,
            unmatched_data: [],
            matched_data: [],
            duplicate_data: [],
            duplicates: [],
            totalmatchData: 0,
            totalUnmatchData: 0,
            totalDuplicateData_staging: 0,
            totalDuplicateData_farmersdb: 0,
            visiblePagesUnmatch: [],
            visiblePagesMatch: [],
            visiblePagesDuplicateStaging: [],
            isDropdownOpen: false,

            invalidCurrentPage: 1,
            invalidDataVisiblePages: [],



        };
    },
    created() {
        this.userId = localStorage.getItem('userId');
    },
    computed: {
        invalidTotalPages() {
            return Math.ceil(this.invalidDataTotalRecords / this.itemsPerInvalidPage);
        },

        invalidStart() {
            return (this.invalidCurrentPage - 1) * this.itemsPerInvalidPage + 1;
        },

        invalidEnd() {
            return Math.min(this.invalidCurrentPage * this.itemsPerInvalidPage, this.invalidDataTotalRecords);
        },

        invalidDataPaginated() {
            const start = (this.invalidCurrentPage - 1) * this.itemsPerInvalidPage;
            return this.fetchOnbintInvalid.slice(start, start + this.itemsPerInvalidPage);
        },
        totalPages() {
            return Math.ceil(this.totalUnmatchData / this.itemsUnmatchPerPage);
        },

        totalMatchPages() {
            return Math.ceil(this.totalmatchData / this.itemsPerPage);
        },

        totalDuplicateStagingPages() {
            return Math.ceil(100 / this.itemsPerPage);
        },


        start() {
            return (this.currentPage - 1) * this.itemsUnmatchPerPage + 1;
        },

        startMatch() {
            return (this.currentMatchPage - 1) * this.itemsPerPage + 1;
        },

        startDuplicate_staging() {
            return (this.currentDuplicateStagingPage - 1) * this.itemsPerPage + 1;
        },

        end() {
            return Math.min(this.currentPage * this.itemsPerPage, this.totalUnmatchData);
        },

        endMatch() {
            return Math.min(this.currentMatchPage * this.itemsPerPage, this.totalmatchData);
        },

        endDuplicate_staging() {
            return Math.min(this.currentDuplicateStagingPage * this.itemsPerPage, this.totalDuplicateData_staging);
        },

        paginatedData() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.unmatched_data.slice(start, start + this.itemsPerPage);
        },

        paginatedMatchData() {
            const start = (this.currentMatchPage - 1) * this.itemsPerPage;
            return this.matched_data.slice(start, start + this.itemsPerPage);
        },

        paginatedDuplicateStagingData() {
            const start = (this.currentDuplicateStagingPage - 1) * this.itemsPerPage;
            return this.duplicates.slice(start, start + this.itemsPerPage);
        }

    },

    methods: {
        async saveExcelData() {
            if (!this.file) {
                alert("Please select a file.");
                return;
            }

            try {
                this.$refs.progressBar.startProgress();
                const formData = new FormData();
                formData.append("file", this.file); // Make sure this.file is a valid file object
                formData.append("filename", this.file.name);
                formData.append('userId',this.userId);

                const response = await axios.post("api/import_excel", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                    onUploadProgress: (progressEvent) => {
                        if (progressEvent.lengthComputable) {
                            const progress = Math.floor((progressEvent.loaded / progressEvent.total) * 100);
                            this.$refs.progressBar.setProgress(progress);
                        }
                    }
                });

                // Show the toast
                const toast = document.getElementById('toast-success');
                    if (toast) {
                    console.log("Toast element found");
                    toast.classList.remove('hidden');
                    console.log("Toast is now visible");

                    // Optionally hide the toast after 1 second
                    setTimeout(() => {
                        toast.classList.add('hidden');
                        location.reload();
                    }, 1000); // 1 second delay
                } else {
                    console.error("Toast element not found");
                }
                this.$refs.progressBar.completeProgress();

            } catch (error) {
                console.error("Error importing data:", error.response.data);
                alert("An error occurred: " + error.response.data.message);
            }
        },

        async checkValidation() {
            try {
                this.$refs.progressBar.startProgress();

                const response = await axios.post("api/checkValidation", {
                    onDownloadProgress: (progressEvent) => {
                        if (progressEvent.lengthComputable) {
                            const progress = Math.floor((progressEvent.loaded / progressEvent.total) * 100);
                            this.$refs.progressBar.setProgress(progress);
                        }
                    }
                });
                this.$refs.progressBar.completeProgress();

            } catch (error) {
                console.log('Failed to validate:', error);
            }
        },
        async initMatchDataTable() {
            try {
                // Start progress
                this.$refs.progressBar.startProgress();

                const response = await axios.get('api/getMatchData', {
                    // Track download progress
                    onDownloadProgress: (progressEvent) => {
                        if (progressEvent.lengthComputable) {
                            // Calculate the percentage and update the progress bar
                            const progress = Math.floor((progressEvent.loaded / progressEvent.total) * 100);
                            this.$refs.progressBar.setProgress(progress);
                        }
                    }
                });

                // Set matched data once the response is completed
                this.matched_data = response.data.data;
                this.totalmatchData = response.data.count;

                this.unmatched_data = response.data.unmatched;
                this.totalUnmatchData = response.data.countUnmatched;

                // Ensure the progress bar reaches 100% when data retrieval is done
                this.$refs.progressBar.completeProgress();

                if (this.totalmatchData === 0) {
                    console.error('No records found.');
                    return;
                }

                // Additional actions like pagination calculation and table initialization
                this.calculateVisiblePagesMatch();


            } catch (error) {
                // Complete the progress if there's an error
                this.$refs.progressBar.completeProgress();
                console.error('Failed to fetch data:', error);
            }
        },
       
        async getDuplicateDataFarmersReg() {
            try {
                const response = await axios.get('api/findDuplicates');
                const data = response.data;
                this.duplicates = response.data;
                this.totalDuplicateData_staging = data.length;

                if (data.count === 0) {
                    console.error('No records found.');
                    return;
                }

                this.calculateVisiblePagesUnmatch();
            } catch (error) {
                console.error('Failed to fetch data:', error);
            }
        },
        async getOnbintStaging() {
            try {
                this.$refs.progressBar.startProgress();
                const response = await axios.get('api/getOnbintStaging', {
                    onDownloadProgress: (progressEvent) => {
                        if (progressEvent.lengthComputable) {
                            const progress = Math.floor((progressEvent.loaded / progressEvent.total) * 100);
                            this.$refs.progressBar.setProgress(progress);
                        }
                    }
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

                if ($.fn.DataTable.isDataTable('#example')) {
                    $('#example').DataTable().clear().destroy();
                }
                this.$refs.progressBar.completeProgress();

                $('#example').DataTable({
                    retrieve: true,
                    data: sortedData,
                    ordering: false,
                    paging: true,
                    pageLength: 10,
                    columns: [
                        { data: 'RSBSASYSTEMGENERATEDNUMBER' },
                        { data: 'FIRSTNAME' },
                        { data: 'MIDDLENAME' },
                        { data: 'LASTNAME' },
                        { data: 'EXTENSIONNAME' },
                        { data: 'IDNUMBER' },
                        { data: 'GOVTIDTYPE' },
                        { data: 'STREETNO_PUROKNO' },
                        { data: 'BARANGAY' },
                        { data: 'CITYMUNICIPALITY' },
                        { data: 'DISTRICT' },
                        { data: 'PROVINCE' },
                        { data: 'REGION' },
                        { data: 'BIRTHDATE' },
                        { data: 'PLACEOFBIRTH' },
                        { data: 'MOBILENO' },
                        { data: 'SEX' },
                        { data: 'NATIONALITY' },
                        { data: 'PROFESSION' },
                        { data: 'SOURCEOFFUNDS' },
                        { data: 'MOTHERMAIDENNAME' },
                    ],
                    columnDefs: [
                        { width: '150px', targets: 0 },
                    ],
                    autoWidth: false,
                    createdRow: function (row, data, dataIndex) {
                        $(row).find('.invalid-field').css('color', 'red');
                    },
                });

            } catch (error) {
                console.error('Error fetching data:', error);
            }
        },
        handleFileChange(event) {
            this.file = event.target.files[0];
        },

        // Add your validation method here
        isValidField(field, value) {
            const alphanumericFields = ['FIRSTNAME', 'MIDDLENAME', 'LASTNAME'];
            const regex = /^[a-zA-Z\s]*$/; // Only letters and spaces
            if (alphanumericFields.includes(field)) {
                return regex.test(value); // Return true if value passes regex
            }
            return true; // Default: consider field valid if no rule applies
        },
        // Function to return the class for a field
        getFieldClass(fieldName, field) {
            return !isValidField(fieldName, field) ? 'bg-red-500 text-white' : ''; // Apply red background if invalid
        },

        // Function to determine the row class (optional)
        getRowClass(item) {
            // Example: if the item has any invalid field, make the row background red
            return Object.keys(item).some((key) => !isValidField(key, item[key]))
                ? 'bg-red-100' // Apply light red background if any field is invalid
                : '';
        },

        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
        },



        isValidField(fieldName, field) {
            // Ensure field is treated as a string
            const fieldStr = String(field || '').trim();

            if (fieldStr === '') {
                return false; // Check for null, undefined, or empty string
            }

            switch (fieldName) {
                case 'FIRSTNAME':
                case 'MIDDLENAME':
                case 'LASTNAME':
                    return /^[a-zA-Z\s]+$/.test(fieldStr) && fieldStr.length >= 2; // Only letters and spaces, and at least 2 letters

                case 'BIRTHDATE':
                    return /^\d{4}-\d{2}-\d{2}$/.test(fieldStr); // Date format yyyy-mm-dd

                case 'MOBILENO':
                    return /^\d{11}$/.test(fieldStr); // Exactly 11 digits

                default:
                    return true;
            }
        },
        changePage(page) {
            if (page >= 1 && page <= this.totalMatchPages) {
                this.currentMatchPage = page;
                this.calculateVisiblePagesMatch();
            }
        },

        changeUnmatchPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.calculateVisiblePagesUnmatch();
            }
        },

        changeDuplicateStagingPage(page) {
            if (page >= 1 && page <= this.totalDuplicateStagingPages) {
                this.currentDuplicateStagingPage = page;
                this.calculateVisiblePagesDuplicateStaging();
            }
        },

        calculateVisiblePagesUnmatch() {
            this.visiblePagesUnmatch = this.getVisiblePages(this.totalPages, this.currentPage);
        },

        calculateVisiblePagesMatch() {
            this.visiblePagesMatch = this.getVisiblePages(this.totalMatchPages, this.currentMatchPage);
        },

        calculateVisiblePagesDuplicateStaging() {
            this.visiblePagesDuplicateStaging = this.getVisiblePages(this.totalDuplicateStagingPages, this.currentDuplicateStagingPage);
        },

        getVisiblePages(totalPages, currentPage) {
            const pages = [];
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

            return pages;
        },

        setActiveTab(tab) {
            this.activeTab = tab;
        },

        async exportUnmatch() {
            try {
                const response = await axios.get('api/exportUnmatch', {
                    responseType: 'blob',
                });

                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'unmatched_data.csv');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                console.log('Data exported successfully!');
            } catch (error) {
                console.error('Error exporting data:', error);
            }
        },
        onInvalidDataPageChange(page) {
            if (page >= 1 && page <= this.invalidTotalPages) {
                this.invalidCurrentPage = page;
                this.calculateInvalidDataVisiblePages();
            }
        },

        calculateInvalidDataVisiblePages() {
            this.invalidDataVisiblePages = this.getInvalidDataVisiblePages(this.invalidTotalPages, this.invalidCurrentPage);
        },

        getInvalidDataVisiblePages(totalPages, currentPage) {
            const pages = [];
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

            return pages;
        }

    },

    mounted() {

        Promise.all([this.getDuplicateDataFarmersReg(), this.duplicateDataStaging()]);
        //  this.initMatchDataTable()

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


.striped-table tbody tr:nth-child(even) {
    background-color: #f9fafb;
    /* Light gray for even rows */
}

.striped-table tbody tr:nth-child(odd) {
    background-color: #ffffff;
    /* White for odd rows */
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
</style>
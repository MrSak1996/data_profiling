<template>
  <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
    <div class="flex flex-1 justify-between sm:hidden">
      <!-- Mobile Previous and Next Buttons -->
      <a href="#" :class="{ 'cursor-not-allowed opacity-50': currentPage === 1 }"
        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
        Previous
      </a>
      <a href="#" @click.prevent="changePage(currentPage - 1)"
        class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
        <span class="sr-only">Previous</span>
      </a>
      <a href="#" @click.prevent="changePage(currentPage + 1)"
        :class="{ 'cursor-not-allowed opacity-50': currentPage === totalPages }"
        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
        Next
      </a>
    </div>

    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <!-- Pagination Information -->
      <div>
        <p class="text-sm text-gray-700">
          Showing
          <span class="font-medium">{{ start }}</span>
          to
          <span class="font-medium">{{ end }}</span>
          of
          <span class="font-medium">{{ totalItems }}</span>
          results
        </p>
      </div>

      <!-- Pagination Controls -->
      <div>
        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
          <!-- Previous Page Button -->
          <a href="#" @click.prevent="changePage(currentPage - 1)"
            :class="{ 'cursor-not-allowed opacity-50': currentPage === 1 }"
            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
            <span class="sr-only">Previous</span>
            <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
          </a>

          <!-- Render Visible Pages -->
          <a v-for="page in visiblePages" :key="page" href="#" @click.prevent="changePage(page)"
            :class="{ 'z-10 bg-indigo-600 text-white': currentPage === page }"
            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
            {{ page }}
          </a>

          <!-- Next Page Button -->
          <a href="#" @click.prevent="changePage(currentPage + 1)"
            :class="{ 'cursor-not-allowed opacity-50': currentPage === totalPages }"
            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
            <span class="sr-only">Next</span>
            <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
          </a>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'

export default {
  components:{
    ChevronLeftIcon,
    ChevronRightIcon
  },
  props: {
    total: {
      type: Number,
      required: true,
    },
    start: {
      type: Number,
      required: true,
    },
    end: {
      type: Number,
      required: true,
    },
    totalItems: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      currentPage: 1,
      itemsPerPage: 5,
   
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.total / this.itemsPerPage);
    },
    
    // Calculate the visible pages based on the current page
    visiblePages() {
      const visiblePages = [];
      const totalPages = this.totalPages;
      const currentPage = this.currentPage;

      let startPage = Math.max(1, currentPage - 2);
      let endPage = Math.min(totalPages, currentPage + 2);

      

      // Adjust start and end page if they are at the extremes
      if (currentPage <= 3) {
        endPage = Math.min(5, totalPages);
      } else if (currentPage >= totalPages - 2) {
        startPage = Math.max(1, totalPages - 4);
      }

      for (let page = startPage; page <= endPage; page++) {
        visiblePages.push(page);
      }

      return visiblePages;
    },
  },
  methods: {
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
        this.$emit('pageChange', page);
      }
    },
  },
};
</script>
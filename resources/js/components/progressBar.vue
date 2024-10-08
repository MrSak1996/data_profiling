<template>
  <!-- Modal -->
  <div v-if="isLoading" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" role="dialog"
    tabindex="-1" aria-labelledby="progress-modal">
    <div
      class="bg-white dark:bg-neutral-800 border dark:border-neutral-700 shadow-sm rounded-xl w-full max-w-4xl mx-4 lg:mx-auto transition-transform duration-500 transform">
      <!-- Modal Header -->
      <div class="modal-content flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
        <h3 class="text-lg font-semibold">{{ currentMessage }}</h3> <!-- Dynamic Message -->
      </div>
      <!-- Modal Body -->
      <div class="flex flex-col justify-center items-center gap-x-2 py-6 px-4">
        <!-- Progress Bar Container -->
        <div class="w-full bg-gray-200 rounded-full h-4">
          <div class="bg-teal-500 h-4 rounded-full transition-all" :style="{ width: progress + '%' }"></div>
        </div>
        <!-- Progress Percentage -->
        <p class="mt-2 text-gray-700 dark:text-gray-300">{{ progress }}%</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      progress: 0, // Progress bar percentage
      isLoading: false, // Controls the visibility of the modal and progress bar
      messages: [
        'Loading, please wait...',
        'Processing data...',
        'Initializing data...',
        'Fetching resources...',
        'Preparing your data...',
        'Almost there, hang tight...'
      ], // Array of dynamic messages
      currentMessage: 'Loading, please wait...', // Default message
    };
  },
  methods: {
    startProgress() {
      this.progress = 0;
      this.isLoading = true; // Show modal
      this.updateMessage(); // Set initial message
      const interval = setInterval(() => {
        if (this.progress < 90) {
          this.progress += Number((Math.random() * 10).toFixed(2)); // Simulate progress increase
          this.updateMessage(); // Update message periodically
        } else {
          clearInterval(interval); // Stop increasing when close to 100%
        }
      }, 500); // Adjust speed of progress increase
    },
    completeProgress() {
      this.progress = 100;
      setTimeout(() => {
        this.isLoading = false; // Hide modal when complete
      }, 500);
    },
    updateMessage() {
      // Change the message randomly or you can cycle through the array sequentially
      const randomIndex = Math.floor(Math.random() * this.messages.length);
      this.currentMessage = this.messages[randomIndex];
    }
  },
};
</script>

<style scoped>
/* Transition for smooth progress bar animation */
.progress-bar {
  transition: width 0.2s ease-in-out;
}
</style>

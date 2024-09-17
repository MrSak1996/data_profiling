<template>
    <div>
     
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        loading: true,
        data: null,
        processingTime: null,
        progress: 0,
      };
    },
    async created() {
      try {
        const response = await fetch('api/checkDataMatches'); // Update this with your actual API endpoint
        const result = await response.json();
        this.data = result;
        this.processingTime = result.processing_time;
  
        // Simulate progress (For demonstration, use actual progress if available)
        this.progress = 100; // Assuming full completion
      } catch (error) {
        console.error('Error fetching data:', error);
      } finally {
        this.loading = false;
      }
    }
  };
  </script>
  
  <style scoped>
  /* Add your styles for the progress bar */
  progress {
    width: 100%;
  }
  </style>
  